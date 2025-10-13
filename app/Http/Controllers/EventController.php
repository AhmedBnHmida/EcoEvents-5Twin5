<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Ressource;
use App\Models\Fournisseur;
use App\Models\User;
use App\EventStatus;
use App\Models\TypeRessource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log; // Ajouté pour logging

class EventController extends Controller
{
    /**
     * Display events for public website with real-time filters
     */
    public function publicIndex(Request $request)
    {
        $query = Event::with('category')
            ->where('is_public', true)
            ->where('status', '!=', \App\EventStatus::CANCELLED);

        // Search functionality - title, category only (location is separate)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('category', function($categoryQuery) use ($search) {
                    $categoryQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by location
        if ($request->has('location') && !empty($request->location)) {
            $location = $request->location;
            $query->where('location', 'like', "%{$location}%");
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('categorie_id', $request->category);
        }

        // Filter by price range
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by date range (BETWEEN)
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        // Sort by start date (default)
        $query->orderBy('start_date', 'asc');

        $events = $query->paginate(9)->appends($request->except('page'));
        
        // Get categories for filter dropdown
        $categories = Category::all();

        return view('events.public-index', compact('events', 'categories'));
    }

    /**
     * Display single event for public website
     */
    public function publicShow(string $id)
    {
        $event = Event::with(['category', 'sponsorings.partner', 'partners'])
            ->where('is_public', true)
            ->findOrFail($id);

        return view('events.public-show', compact('event'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with('category');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('categorie_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $events = $query->paginate(10)->appends($request->except('page'));
        
        // Get categories and statuses for filter dropdowns
        $categories = Category::all();
        $statuses = EventStatus::cases();

        return view('events.index', compact('events', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
  public function create()
{
    $categories = Category::all();
    $statuses = EventStatus::cases();
    $fournisseurs = Fournisseur::all(); // fetch from fournisseur table
     $resourceTypes = TypeRessource::allTypes(); // <-- ici

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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'resources' => 'nullable|array',
            'resources.*.nom' => 'required_with:resources|string|max:255',
           'resources.*.type' => 'required_with:resources|in:' . implode(',', TypeRessource::allTypes()),
            'resources.*.fournisseur_id' => 'required_with:resources|exists:users,id',
            'resources.*.quantite' => 'required_with:resources|integer|min:1',
        ]);

        $eventData = $request->except(['resources', 'images']);

        // Handle multiple images upload - store in images array
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedImages[] = $image->store('events/images', 'public');
            }
            $eventData['images'] = $uploadedImages;
        }

        $event = Event::create($eventData);

        if ($request->has('resources')) {
            foreach ($request->resources as $resourceData) {
                $event->ressources()->create([
                    'nom' => $resourceData['nom'],
                    'type' => $resourceData['type'],
                    'fournisseur_id' => $resourceData['fournisseur_id'],
                    'quantite' => $resourceData['quantite'] ?? 1,
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
        $fournisseurs = User::where('role', 'fournisseur')->get();
        $resourceTypes = TypeRessource::allTypes();
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'resources' => 'nullable|array',
            'resources.*.nom' => 'required_with:resources|string|max:255',
            'resources.*.type' => 'required_with:resources|in:' . implode(',', TypeRessource::allTypes()),
            'resources.*.fournisseur_id' => 'required_with:resources|exists:users,id',
            'resources.*.quantite' => 'required_with:resources|integer|min:1',
            'resources.*.id' => 'nullable|exists:ressources,id',
        ]);

        $event = Event::findOrFail($id);
        $eventData = $request->except(['resources', 'images', 'remove_images']);

        // Handle image removal
        $currentImages = $event->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                // Delete file from storage
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }
                // Remove from array
                $currentImages = array_filter($currentImages, function($image) use ($imageToRemove) {
                    return $image !== $imageToRemove;
                });
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $currentImages[] = $image->store('events/images', 'public');
            }
        }

        $eventData['images'] = $currentImages;
        $event->update($eventData);

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
                        'quantite' => $resourceData['quantite'] ?? 1,
                    ]);
                    $submittedResourceIds[] = $resourceData['id'];
                } else {
                    // Create new resource
                    $newResource = $event->ressources()->create([
                        'nom' => $resourceData['nom'],
                        'type' => $resourceData['type'],
                        'fournisseur_id' => $resourceData['fournisseur_id'],
                        'quantite' => $resourceData['quantite'] ?? 1,
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


    // Add method to delete individual images
    public function deleteImage(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $imageToDelete = $request->image_path;

        $currentImages = $event->images ?? [];
        
        // Remove image from array
        $updatedImages = array_filter($currentImages, function($image) use ($imageToDelete) {
            return $image !== $imageToDelete;
        });

        // Delete file from storage
        if (Storage::disk('public')->exists($imageToDelete)) {
            Storage::disk('public')->delete($imageToDelete);
        }

        $event->update(['images' => array_values($updatedImages)]);

        return response()->json(['success' => true]);
    }








    // Other methods for managing resources can be added here
    public function exportHistory()
{
    $events = \App\Models\Event::with('ressources')->get();
    \Storage::put('events_history.json', $events->toJson(JSON_PRETTY_PRINT));
    return response()->json(['status' => 'ok']);
}

    /**
     * Suggest resources based on category and capacity using Python script.
     */
   /**
 * Suggest resources based on category and capacity using Python script.
 */
/**
 * Suggest resources based on category and capacity using Python script.
 */
public function suggestResources(Request $request)
{
    try {
        Log::info('SuggestResources called with:', $request->all());

        $request->validate([
            'categorie_id' => 'required|integer|exists:categories,id',
            'capacity_max' => 'required|integer|min:1',
        ]);

        $historyFile = storage_path('app/events_history.json');
        Log::info('History file path: ' . $historyFile);

        if (!file_exists($historyFile)) {
            Log::info('History file not found, generating...');
            $events = Event::with('ressources')->get();
            $json = $events->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); // Fix UTF-8: unescape unicode
            Storage::put('events_history.json', $json);
            Log::info('Generated history JSON length: ' . strlen($json) . ' | Preview: ' . substr($json, 0, 200));
        } else {
            $json = Storage::get('events_history.json');
            Log::info('Existing history JSON length: ' . strlen($json) . ' | Preview: ' . substr($json, 0, 200));
        }

        // Chemin précis pour le script
        $scriptPath = base_path('app/Http/Scripts/suggest_ressources.py');
        Log::info('Script path: ' . $scriptPath);
        if (!file_exists($scriptPath)) {
            Log::error('Python script not found at: ' . $scriptPath);
            return response()->json(['error' => 'Script Python introuvable. Vérifiez le chemin: ' . $scriptPath], 500);
        }

        // Force cwd au root Laravel pour cohérence
        $command = sprintf('cd %s && python3 %s %d %d 2>&1', escapeshellarg(base_path()), escapeshellarg($scriptPath), $request->categorie_id, $request->capacity_max);
        Log::info('Executing command: ' . $command);

        $output = shell_exec($command);
        Log::info('Shell exec raw output: ' . ($output ? substr($output, 0, 500) : 'empty'));

        if ($output === null || empty(trim($output))) {
            Log::error('Empty output from script');
            return response()->json(['error' => 'Erreur lors de l\'exécution du script (output vide)'], 500);
        }

        // Fix UTF-8 pour decode: force encoding si besoin
        $decoded = json_decode($output, true, 512, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error: ' . json_last_error_msg() . ' | Raw output: ' . $output);
            // Fallback si JSON invalide: renvoie defaults manuellement
        $suggestions = [
'resources' => [
['nom' => 'Chaise', 'type' => 'Chaise', 'quantite' => $request->capacity_max],
['nom' => 'Table', 'type' => 'Table', 'quantite' => max(1, (int) ($request->capacity_max / 10))]
]
];
            Log::info('Fallback suggestions due to JSON error: ', $suggestions);
        } else {
            $suggestions = $decoded;
        }

        return response()->json($suggestions);

    } catch (\Exception $e) {
        Log::error('Exception in suggestResources: ' . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
    } catch (\Throwable $t) {
        Log::error('Throwable in suggestResources: ' . $t->getMessage());
        return response()->json(['error' => 'Erreur inattendue'], 500);
    }
}

}