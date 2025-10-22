<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\EventStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-statuses';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Automatically update event statuses based on current date and time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting event status update...');
        Log::info('CRON: Starting automatic event status update');

        $now = now();
        $updatedCount = 0;

        try {
            // Update events that should be ONGOING (start_date <= now < end_date)
            $ongoingEvents = Event::where('status', EventStatus::UPCOMING)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>', $now)
                ->get();

            foreach ($ongoingEvents as $event) {
                $event->update(['status' => EventStatus::ONGOING]);
                $this->info("Event {$event->id} ({$event->title}) status updated to ONGOING");
                Log::info("CRON: Event {$event->id} status updated to ONGOING");
                $updatedCount++;
            }

            // Update events that should be COMPLETED (end_date <= now)
            $completedEvents = Event::whereIn('status', [EventStatus::UPCOMING, EventStatus::ONGOING])
                ->where('end_date', '<=', $now)
                ->get();

            foreach ($completedEvents as $event) {
                $event->update(['status' => EventStatus::COMPLETED]);
                $this->info("Event {$event->id} ({$event->title}) status updated to COMPLETED");
                Log::info("CRON: Event {$event->id} status updated to COMPLETED");
                $updatedCount++;
            }

            // Update events that are at risk (registration deadline approaching)
            $riskThreshold = now()->addDays(3); // 3 days before registration deadline
            $atRiskEvents = Event::where('status', EventStatus::UPCOMING)
                ->where('registration_deadline', '<=', $riskThreshold)
                ->where('registration_deadline', '>', $now)
                ->where('at_risk', false)
                ->get();

            foreach ($atRiskEvents as $event) {
                $event->update(['at_risk' => true]);
                $this->info("Event {$event->id} ({$event->title}) marked as at risk");
                Log::info("CRON: Event {$event->id} marked as at risk");
                $updatedCount++;
            }

            // Reset at_risk for events that are no longer at risk
            $noLongerAtRiskEvents = Event::where('at_risk', true)
                ->where(function($query) use ($now) {
                    $query->where('registration_deadline', '>', $now->addDays(3))
                          ->orWhere('status', '!=', EventStatus::UPCOMING);
                })
                ->get();

            foreach ($noLongerAtRiskEvents as $event) {
                $event->update(['at_risk' => false]);
                $this->info("Event {$event->id} ({$event->title}) no longer at risk");
                Log::info("CRON: Event {$event->id} no longer at risk");
                $updatedCount++;
            }

            $this->info("Event status update completed. {$updatedCount} events updated.");
            Log::info("CRON: Event status update completed. {$updatedCount} events updated.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error updating event statuses: " . $e->getMessage());
            Log::error("CRON: Error updating event statuses: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}