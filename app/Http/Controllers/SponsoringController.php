<?php

namespace App\Http\Controllers;

use App\Models\Sponsoring;
use App\Models\Partner;
use App\Models\Event;
use App\TypeSponsoring;
use Illuminate\Http\Request;

class SponsoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sponsoring::with(['partner', 'event']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%")
                  ->orWhereHas('partner', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type_sponsoring', $request->type);
        }

        // Filter by partner
        if ($request->has('partner_id') && $request->partner_id != '') {
            $query->where('partenaire_id', $request->partner_id);
        }

        // Filter by event
        if ($request->has('event_id') && $request->event_id != '') {
            $query->where('evenement_id', $request->event_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sponsorings = $query->paginate(10)->appends($request->except('page'));
        
        // Get data for filters
        $types = TypeSponsoring::cases();
        $partners = Partner::all();
        $events = Event::all();

        return view('sponsoring.index', compact('sponsorings', 'types', 'partners', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only organizer and admin can create sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $partners = Partner::all();
        $events = Event::all();
        $typesSponsorings = TypeSponsoring::cases();
        
        return view('sponsoring.create', compact('partners', 'events', 'typesSponsorings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only organizer and admin can create sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'type_sponsoring' => 'required|in:argent,materiel,logistique,autre',
            'date' => 'required|date',
            'partenaire_id' => 'required|exists:partners,id',
            'evenement_id' => 'required|exists:events,id',
        ]);

        Sponsoring::create($request->all());

        return redirect()->route('sponsoring.index')
            ->with('success', 'Sponsoring créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sponsoring = Sponsoring::with(['partner', 'event'])->findOrFail($id);
        return view('sponsoring.show', compact('sponsoring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Only organizer and admin can edit sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $sponsoring = Sponsoring::findOrFail($id);
        $partners = Partner::all();
        $events = Event::all();
        $typesSponsorings = TypeSponsoring::cases();
        
        return view('sponsoring.edit', compact('sponsoring', 'partners', 'events', 'typesSponsorings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Only organizer and admin can update sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'type_sponsoring' => 'required|in:argent,materiel,logistique,autre',
            'date' => 'required|date',
            'partenaire_id' => 'required|exists:partners,id',
            'evenement_id' => 'required|exists:events,id',
        ]);

        $sponsoring = Sponsoring::findOrFail($id);
        $sponsoring->update($request->all());

        return redirect()->route('sponsoring.index')
            ->with('success', 'Sponsoring modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Only admin can delete sponsorings
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $sponsoring = Sponsoring::findOrFail($id);
        $sponsoring->delete();

        return redirect()->route('sponsoring.index')
            ->with('success', 'Sponsoring supprimé avec succès.');
    }
}
