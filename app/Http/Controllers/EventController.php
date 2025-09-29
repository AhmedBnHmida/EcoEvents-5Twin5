<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\EventStatus;
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
        return view('events.create', compact('categories', 'statuses'));
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
            'images' => 'nullable|array'
        ]);

        Event::create($request->all());

        return redirect()->route('events.index') // UPDATED
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with('category')->findOrFail($id);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::all();
        $statuses = EventStatus::cases();
        return view('events.edit', compact('event', 'categories', 'statuses'));
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
            'images' => 'nullable|array'
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect()->route('events.index') // UPDATED
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index') // UPDATED
            ->with('success', 'Event deleted successfully.');
    }
}