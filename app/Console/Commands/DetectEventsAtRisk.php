<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventRiskDetectionService;
use Illuminate\Support\Facades\Log;

class DetectEventsAtRisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:detect-risks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect events at risk based on negative feedback using AI analysis';

    /**
     * Execute the console command.
     */
    public function handle(EventRiskDetectionService $riskService)
    {
        $this->info('Starting detection of events at risk...');
        
        try {
            $eventsAtRisk = $riskService->detectEventsAtRisk();
            
            $count = count($eventsAtRisk);
            
            if ($count > 0) {
                $this->info("✅ Detected {$count} events at risk.");
                
                foreach ($eventsAtRisk as $eventData) {
                    $this->line('');
                    $this->line('🚨 Event: ' . $eventData['event']->title);
                    $this->line('📊 Negative feedback count: ' . $eventData['negative_count']);
                    $this->line('📝 Analysis summary: ' . substr($eventData['analysis'], 0, 100) . '...');
                }
                
                $this->info('');
                $this->info('Notifications have been sent to organizers.');
            } else {
                $this->info('✅ No events at risk detected.');
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error detecting events at risk: ' . $e->getMessage());
            Log::error('Error in DetectEventsAtRisk command: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return Command::FAILURE;
        }
    }
}
