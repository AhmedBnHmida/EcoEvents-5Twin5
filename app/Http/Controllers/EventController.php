<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Ressource;
use App\Models\Fournisseur;
use App\EventStatus;
use App\TypeRessource;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display events for public website
     */
    public function publicIndex()
    {
        $events = Event::with('category')
            ->where('is_public', true)
            ->where('status', '!=', \App\EventStatus::CANCELLED)
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->paginate(9);

        return view('events.public-index', compact('events'));
    }

    /**
     * Display single event for public website
     */
    public function publicShow(string $id)
    {
        $event = Event::with('category')
            ->where('is_public', true)
            ->findOrFail($id);

        return view('events.public-show', compact('event'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('category')->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $statuses = EventStatus::cases();
        $fournisseurs = Fournisseur::all();
        $resourceTypes = TypeRessource::cases();
        return view('events.create', compact('categories', 'statuses', 'fournisseurs', 'resourceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'capacity_max' => 'required|integer|min:1',
            'categorie_id' => 'required|exists:categories,id',
            'status' => 'required|in:' . implode(',', array_column(EventStatus::cases(), 'value')),
            'registration_deadline' => 'required|date|before:start_date',
            'price' => 'required|numeric|min:0',
            'is_public' => 'boolean',
            'images' => 'nullable|array',
            'resources' => 'nullable|array',
            'resources.*.nom' => 'required_with:resources|string|max:255',
            'resources.*.type' => 'required_with:resources|in:' . implode(',', array_column(TypeRessource::cases(), 'value')),
            'resources.*.fournisseur_id' => 'required_with:resources|exists:fournisseurs,id',
        ]);

        $eventData = $request->except('resources');
        $event = Event::create($eventData);

        if ($request->has('resources')) {
            foreach ($request->resources as $resourceData) {
                $event->ressources()->create([
                    'nom' => $resourceData['nom'],
                    'type' => $resourceData['type'],
                    'fournisseur_id' => $resourceData['fournisseur_id'],
                    'event_id' => $event->id,
                ]);
            }
        }

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with(['category', 'ressources.fournisseur'])->findOrFail($id);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::with('ressources')->findOrFail($id);
        $categories = Category::all();
        $statuses = EventStatus::cases();
        $fournisseurs = Fournisseur::all();
        $resourceTypes = TypeRessource::cases();
        return view('events.edit', compact('event', 'categories', 'statuses', 'fournisseurs', 'resourceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'capacity_max' => 'required|integer|min:1',
            'categorie_id' => 'required|exists:categories,id',
            'status' => 'required|in:' . implode(',', array_column(EventStatus::cases(), 'value')),
            'registration_deadline' => 'required|date|before:start_date',
            'price' => 'required|numeric|min:0',
            'is_public' => 'boolean',
            'images' => 'nullable|array',
            'resources' => 'nullable|array',
            'resources.*.nom' => 'required_with:resources|string|max:255',
            'resources.*.type' => 'required_with:resources|in:' . implode(',', array_column(TypeRessource::cases(), 'value')),
            'resources.*.fournisseur_id' => 'required_with:resources|exists:fournisseurs,id',
            'resources.*.id' => 'nullable|exists:ressources,id',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->except('resources'));

        // Get existing resource IDs
        $existingResourceIds = $event->ressources()->pluck('id')->toArray();
        $submittedResourceIds = [];

        // Handle resources
        if ($request->has('resources')) {
            foreach ($request->resources as $resourceData) {
                if (isset($resourceData['id']) && !empty($resourceData['id'])) {
                    // Update existing resource
                    $resource = Ressource::findOrFail($resourceData['id']);
                    $resource->update([
                        'nom' => $resourceData['nom'],
                        'type' => $resourceData['type'],
                        'fournisseur_id' => $resourceData['fournisseur_id'],
                    ]);
                    $submittedResourceIds[] = $resourceData['id'];
                } else {
                    // Create new resource
                    $newResource = $event->ressources()->create([
                        'nom' => $resourceData['nom'],
                        'type' => $resourceData['type'],
                        'fournisseur_id' => $resourceData['fournisseur_id'],
                        'event_id' => $event->id,
                    ]);
                    $submittedResourceIds[] = $newResource->id;
                }
            }
        }

        // Delete resources that were removed from the form
        $resourcesToDelete = array_diff($existingResourceIds, $submittedResourceIds);
        if (!empty($resourcesToDelete)) {
            Ressource::whereIn('id', $resourcesToDelete)->delete();
        }

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }
}