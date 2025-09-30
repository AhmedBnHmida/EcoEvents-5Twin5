<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use App\Models\Feedback;
use App\Models\Ressource;
use App\Models\Partner;
use App\Models\Sponsoring;
use App\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get statistics based on user role
        $stats = $this->getStatistics($user);
        
        return view('dashboard', compact('stats'));
    }

    /**
     * Get statistics based on user role
     */
    private function getStatistics($user)
    {
        $stats = [];

        if ($user->isAdmin()) {
            $stats = $this->getAdminStatistics();
        } elseif ($user->isFournisseur()) {
            $stats = $this->getFournisseurStatistics($user);
        } elseif ($user->isOrganisateur()) {
            $stats = $this->getOrganisateurStatistics($user);
        } elseif ($user->isParticipant()) {
            $stats = $this->getParticipantStatistics($user);
        }

        return $stats;
    }

    /**
     * Get admin statistics
     */
    private function getAdminStatistics()
    {
        return [
            // Event Statistics
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('status', EventStatus::UPCOMING)->count(),
            'ongoing_events' => Event::where('status', EventStatus::ONGOING)->count(),
            'completed_events' => Event::where('status', EventStatus::COMPLETED)->count(),
            'cancelled_events' => Event::where('status', EventStatus::CANCELLED)->count(),
            
            // Registration Statistics
            'total_registrations' => Registration::count(),
            'confirmed_registrations' => Registration::where('status', 'confirmed')->count(),
            'attended_registrations' => Registration::where('status', 'attended')->count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            
            // Revenue Statistics
            'total_revenue' => $this->calculateTotalRevenue(),
            'revenue_this_month' => $this->calculateRevenueThisMonth(),
            'average_event_price' => Event::avg('price'),
            
            // User Statistics
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'organisateurs' => User::where('role', 'organisateur')->count(),
            'fournisseurs' => User::where('role', 'fournisseur')->count(),
            'participants' => User::where('role', 'participant')->count(),
            
            // Feedback Statistics
            'total_feedback' => Feedback::count(),
            'average_rating' => Feedback::avg('note'),
            
            // Resource Statistics
            'total_ressources' => Ressource::count(),
            
            // Partner & Sponsoring Statistics
            'total_partners' => Partner::count(),
            'total_sponsorings' => Sponsoring::count(),
            'total_sponsoring_amount' => Sponsoring::sum('montant'),
            
            // Popular Events (Top 5 by registrations)
            'popular_events' => $this->getPopularEvents(5),
            
            // Recent Events
            'recent_events' => Event::latest()->take(5)->get(),
            
            // Attendance Rate
            'attendance_rate' => $this->calculateAttendanceRate(),
        ];
    }

    /**
     * Get fournisseur statistics
     */
    private function getFournisseurStatistics($user)
    {
        return [
            'total_ressources' => $user->ressources()->count(),
            'ressources_by_type' => $user->ressources()
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get(),
            'events_supplied' => Event::whereHas('ressources', function($query) use ($user) {
                $query->where('fournisseur_id', $user->id);
            })->count(),
        ];
    }

    /**
     * Get organisateur statistics
     */
    private function getOrganisateurStatistics($user)
    {
        $userEvents = Event::where('organisateur_id', $user->id);
        
        return [
            'total_events' => $userEvents->count(),
            'upcoming_events' => (clone $userEvents)->where('status', EventStatus::UPCOMING)->count(),
            'ongoing_events' => (clone $userEvents)->where('status', EventStatus::ONGOING)->count(),
            'completed_events' => (clone $userEvents)->where('status', EventStatus::COMPLETED)->count(),
            'total_registrations' => Registration::whereIn('event_id', (clone $userEvents)->pluck('id'))->count(),
            'total_revenue' => $this->calculateUserRevenue($user),
            'average_rating' => Feedback::whereIn('id_evenement', (clone $userEvents)->pluck('id'))->avg('note'),
            'total_ressources' => $user->ressources()->count(),
            'popular_events' => $this->getUserPopularEvents($user, 3),
        ];
    }

    /**
     * Get participant statistics
     */
    private function getParticipantStatistics($user)
    {
        return [
            'total_registrations' => $user->registrations()->count(),
            'confirmed_registrations' => $user->registrations()->where('status', 'confirmed')->count(),
            'attended_registrations' => $user->registrations()->where('status', 'attended')->count(),
            'pending_registrations' => $user->registrations()->where('status', 'pending')->count(),
            'total_feedback' => $user->feedbacks()->count(),
            'average_rating_given' => $user->feedbacks()->avg('note'),
            'upcoming_events' => Event::whereHas('registrations', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', EventStatus::UPCOMING)->count(),
        ];
    }

    /**
     * Calculate total revenue from all events
     */
    private function calculateTotalRevenue()
    {
        return Registration::whereIn('registrations.status', ['confirmed', 'attended'])
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->sum('events.price');
    }

    /**
     * Calculate revenue for current month
     */
    private function calculateRevenueThisMonth()
    {
        return Registration::whereIn('registrations.status', ['confirmed', 'attended'])
            ->whereMonth('registrations.created_at', now()->month)
            ->whereYear('registrations.created_at', now()->year)
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->sum('events.price');
    }

    /**
     * Calculate revenue for a specific user's events
     */
    private function calculateUserRevenue($user)
    {
        return Registration::whereIn('registrations.status', ['confirmed', 'attended'])
            ->whereIn('registrations.event_id', Event::where('organisateur_id', $user->id)->pluck('id'))
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->sum('events.price');
    }

    /**
     * Get popular events by registration count
     */
    private function getPopularEvents($limit = 5)
    {
        return Event::withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get popular events for a specific user
     */
    private function getUserPopularEvents($user, $limit = 3)
    {
        return Event::where('organisateur_id', $user->id)
            ->withCount('registrations')
            ->orderBy('registrations_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Calculate overall attendance rate
     */
    private function calculateAttendanceRate()
    {
        $totalRegistrations = Registration::count();
        if ($totalRegistrations == 0) return 0;
        
        $attendedRegistrations = Registration::where('registrations.status', 'attended')->count();
        
        return round(($attendedRegistrations / $totalRegistrations) * 100, 2);
    }

    /**
     * API endpoint to get statistics in JSON format
     */
    public function getStats(Request $request)
    {
        $user = auth()->user();
        $stats = $this->getStatistics($user);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * API endpoint to get event statistics by date range
     */
    public function getEventStats(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Event::query();

        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('start_date', '<=', $request->end_date);
        }

        $events = $query->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_events' => $events->count(),
                'total_revenue' => $events->sum(function($event) {
                    return $event->registrations()->whereIn('status', ['confirmed', 'attended'])->count() * $event->price;
                }),
                'total_registrations' => $events->sum(function($event) {
                    return $event->registrations()->count();
                }),
                'events' => $events
            ]
        ]);
    }
}