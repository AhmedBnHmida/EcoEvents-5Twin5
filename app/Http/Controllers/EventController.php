<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['category'])
                    ->orderBy('start_date', 'asc')
                    ->paginate(12);

        return view('events.index', compact('events'));
    }
}