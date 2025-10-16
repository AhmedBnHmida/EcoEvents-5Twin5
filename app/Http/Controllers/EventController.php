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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventFournisseurNotification; // Assure-toi que ton Mailable existe

use App\Services\OpenRouterAiService;


class EventController extends Controller
{




/**
 * Generate complete event with ALL fields
 */
public function generateCompleteEvent(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'capacity' => 'required|integer|min:1'
    ]);

    try {
        $category = Category::findOrFail($request->category_id);
        $aiService = new OpenRouterAiService();
        
        $eventData = [
            'title' => $request->title,
            'category' => $category->name,
            'capacity' => $request->capacity
        ];

        $completeEvent = $aiService->generateCompleteEvent($eventData);

        return response()->json([
            'success' => true,
            'event' => $completeEvent,
            'source' => 'openrouter-ai-complete'
        ]);

    } catch (\Exception $e) {
        Log::error('Complete Event generation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error generating complete event: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Predict event success
 */
public function predictEventSuccess(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'capacity' => 'required|integer|min:1'
    ]);

    try {
        $category = Category::findOrFail($request->category_id);
        $aiService = new OpenRouterAiService();
        
        $eventData = [
            'title' => $request->title,
            'category' => $category->name,
            'capacity' => $request->capacity
        ];

        $prediction = $aiService->predictEventSuccess($eventData);

        return response()->json([
            'success' => true,
            'prediction' => $prediction,
            'source' => 'openrouter-ai-prediction'
        ]);

    } catch (\Exception $e) {
        Log::error('Event prediction error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error generating prediction: ' . $e->getMessage()
        ], 500);
    }
}

public function generateDescription(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id'
    ]);

    try {
        $category = Category::findOrFail($request->category_id);
        $aiService = new OpenRouterAiService();
        
        $description = $aiService->generateEventDescription(
            $request->title, 
            $category->name
        );

        return response()->json([
            'success' => true,
            'description' => $description,
            'source' => 'openrouter-ai'
        ]);

    } catch (\Exception $e) {
        Log::error('OpenRouter Description generation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error generating description: ' . $e->getMessage()
        ], 500);
    }
}

public function generateEvent(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'capacity' => 'required|integer|min:1'
    ]);

    try {
        $category = Category::findOrFail($request->category_id);
        $aiService = new OpenRouterAiService();
        
        $eventData = [
            'title' => $request->title,
            'category' => $category->name,
            'capacity' => $request->capacity
        ];

        $generatedEvent = $aiService->generateCompleteEvent($eventData);

        return response()->json([
            'success' => true,
            'event' => $generatedEvent,
            'source' => 'openrouter-ai'
        ]);

    } catch (\Exception $e) {
        Log::error('OpenRouter Event generation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error generating event: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Display events for public website with real-time filters
     */
    public function publicIndex(Request $request)
    {
        $query = Event::with('category')
            ->where('is_public', true)
            ->where('status', '!=', EventStatus::CANCELLED);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('location') && !empty($request->location)) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('categorie_id', $request->category);
        }

        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        $query->orderBy('start_date', 'asc');

        $events = $query->paginate(9)->appends($request->except('page'));
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

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('categorie_id', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('end_date', '<=', $request->end_date);
        }

        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $events = $query->paginate(10)->appends($request->except('page'));
        $categories = Category::all();
        $statuses = EventStatus::cases();

        return view('events.index', compact('events', 'categories', 'statuses'));
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
        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $uploadedImages[] = $image->store('events/images', 'public');
            }
            $eventData['images'] = $uploadedImages;
        }

        $event = Event::create($eventData);
        Log::info('Event créé: ID=' . $event->id);

        if ($request->has('resources')) {
            foreach ($request->resources as $resourceData) {
                $ressource = $event->ressources()->create([
                    'nom' => $resourceData['nom'],
                    'type' => $resourceData['type'],
                    'fournisseur_id' => $resourceData['fournisseur_id'],
                    'quantite' => $resourceData['quantite'] ?? 1,
                    'event_id' => $event->id,
                ]);

                // Log pour vérification mail
                $fournisseur = $ressource->fournisseur;
                if ($fournisseur && $fournisseur->email) {
                    Log::info('Prêt à envoyer mail au fournisseur: ' . $fournisseur->email);
                    try {
                        Mail::to($fournisseur->email)->send(new EventFournisseurNotification($event, $ressource));
                        Log::info('Mail envoyé avec succès à ' . $fournisseur->email);
                    } catch (\Exception $e) {
                        Log::error('Erreur lors de l\'envoi du mail: ' . $e->getMessage());
                    }
                } else {
                    Log::warning('Fournisseur sans email pour la ressource: ' . $ressource->nom);
                }
            }
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
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

        $currentImages = $event->images ?? [];
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageToRemove) {
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }
                $currentImages = array_filter($currentImages, fn($image) => $image !== $imageToRemove);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $currentImages[] = $image->store('events/images', 'public');
            }
        }

        $eventData['images'] = $currentImages;
        $event->update($eventData);
        Log::info('Event mis à jour: ID=' . $event->id);

        $existingResourceIds = $event->ressources()->pluck('id')->toArray();
        $submittedResourceIds = [];

        if ($request->has('resources')) {
            foreach ($request->resources as $resourceData) {
                if (isset($resourceData['id']) && !empty($resourceData['id'])) {
                    $resource = Ressource::findOrFail($resourceData['id']);
                    $resource->update([
                        'nom' => $resourceData['nom'],
                        'type' => $resourceData['type'],
                        'fournisseur_id' => $resourceData['fournisseur_id'],
                        'quantite' => $resourceData['quantite'] ?? 1,
                    ]);
                    $submittedResourceIds[] = $resourceData['id'];
                } else {
                    $newResource = $event->ressources()->create([
                        'nom' => $resourceData['nom'],
                        'type' => $resourceData['type'],
                        'fournisseur_id' => $resourceData['fournisseur_id'],
                        'quantite' => $resourceData['quantite'] ?? 1,
                        'event_id' => $event->id,
                    ]);
                    $submittedResourceIds[] = $newResource->id;

                    $fournisseur = $newResource->fournisseur;
                    if ($fournisseur && $fournisseur->email) {
                        Log::info('Prêt à envoyer mail au fournisseur: ' . $fournisseur->email);
                        try {
                            Mail::to($fournisseur->email)->send(new EventFournisseurNotification($event, $newResource));
                            Log::info('Mail envoyé avec succès à ' . $fournisseur->email);
                        } catch (\Exception $e) {
                            Log::error('Erreur lors de l\'envoi du mail: ' . $e->getMessage());
                        }
                    } else {
                        Log::warning('Fournisseur sans email pour la ressource: ' . $newResource->nom);
                    }
                }
            }
        }

        $resourcesToDelete = array_diff($existingResourceIds, $submittedResourceIds);
        if (!empty($resourcesToDelete)) {
            Ressource::whereIn('id', $resourcesToDelete)->delete();
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * Delete individual images
     */
    public function deleteImage(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $imageToDelete = $request->image_path;

        $currentImages = $event->images ?? [];
        $updatedImages = array_filter($currentImages, fn($image) => $image !== $imageToDelete);

        if (Storage::disk('public')->exists($imageToDelete)) {
            Storage::disk('public')->delete($imageToDelete);
        }

        $event->update(['images' => array_values($updatedImages)]);

        return response()->json(['success' => true]);
    }

    public function exportHistory()
    {
        $events = Event::with('ressources')->get();
        Storage::put('events_history.json', $events->toJson(JSON_PRETTY_PRINT));
        return response()->json(['status' => 'ok']);
    }


      /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $statuses = EventStatus::cases();
        $fournisseurs = Fournisseur::all();
        $resourceTypes = TypeRessource::allTypes();

        return view('events.create', compact('categories', 'statuses', 'fournisseurs', 'resourceTypes'));
    }



public function suggestResources(Request $request)
{
    try {
        Log::info('SuggestResources called with:', $request->all());

        $request->validate([
            'categorie_id' => 'required|integer|exists:categories,id',
            'capacity_max' => 'required|integer|min:1',
        ]);

        $categorieId = $request->categorie_id;
        $capacityMax = $request->capacity_max;

        // Query DB pour events similaires (catégorie exacte + capacité ±25%)
        $similarEvents = Event::with('ressources.fournisseur')
            ->where('categorie_id', $categorieId)
            ->where('capacity_max', '>=', $capacityMax * 0.75)  // Min 75% de capacity_max
            ->where('capacity_max', '<=', $capacityMax * 1.25)  // Max 125%
            ->get();

        Log::info('Similar events found: ' . $similarEvents->count());

        // Fallback : tous les events de la catégorie si aucun similaire
        if ($similarEvents->isEmpty()) {
            $similarEvents = Event::with('ressources.fournisseur')
                ->where('categorie_id', $categorieId)
                ->get();
            Log::info('Fallback - All events in category: ' . $similarEvents->count());
        }

        // Agrège ressources par type : sums, counts, ET fournisseurs par type (pour recommandation)
        $resourceSums = [];
        $resourceCounts = [];
        $representativeNoms = [];  // Noms descriptifs
        $supplierCounts = [];  // Comptage fournisseurs par type (pour le plus utilisé)

        foreach ($similarEvents as $event) {
            $eventResources = [];  // Par type pour cet event
            foreach ($event->ressources as $res) {
                $nom = $res->nom ?? 'Inconnu';
                $type = $res->type ?? '';
                $qty = (int) ($res->quantite ?? 1);
                $fournisseurId = $res->fournisseur_id ?? null;

                if ($type && $nom) {
                    $eventResources[$type] = ($eventResources[$type] ?? 0) + $qty;

                    // Nom représentatif (comme avant)
                    if (!isset($representativeNoms[$type])) {
                        $representativeNoms[$type] = $nom;
                    } elseif ($representativeNoms[$type] === 'Test' && $nom !== 'Test') {
                        $representativeNoms[$type] = $nom;
                    } elseif ($nom !== 'Test' && $representativeNoms[$type] !== 'Test') {
                        if (strlen($nom) > strlen($representativeNoms[$type])) {
                            $representativeNoms[$type] = $nom;
                        }
                    } elseif ($nom === 'Test' && $representativeNoms[$type] !== 'Test') {
                        // Garde non-Test
                    } elseif (strlen($nom) > strlen($representativeNoms[$type])) {
                        $representativeNoms[$type] = $nom;
                    }

                    // Comptage fournisseurs par type
                    if ($fournisseurId) {
                        $supplierCounts[$type][$fournisseurId] = ($supplierCounts[$type][$fournisseurId] ?? 0) + 1;
                    }
                }
            }

            // Ajoute à totaux quantités
            foreach ($eventResources as $type => $eventQty) {
                $resourceSums[$type] = ($resourceSums[$type] ?? 0) + $eventQty;
                $resourceCounts[$type] = ($resourceCounts[$type] ?? 0) + 1;
            }
        }

        Log::info('Resource types found: ' . json_encode(array_keys($resourceSums)));

        $suggestions = [];
        foreach ($resourceSums as $type => $totalQty) {
            $avgQty = (int) ($totalQty / $resourceCounts[$type]);
            if ($avgQty > 0) {
                $repNom = $representativeNoms[$type] ?? $type;

                // Recommandation fournisseur : le plus utilisé pour ce type
                $recommendedSupplierId = null;
                $recommendedSupplierName = 'Non spécifié';
                if (isset($supplierCounts[$type])) {
                    arsort($supplierCounts[$type]);  // Trie par count descendant
                    $topSupplierId = key($supplierCounts[$type]);
                    $recommendedSupplierId = $topSupplierId;
                    $recommendedSupplierName = Fournisseur::find($topSupplierId)->nom_societe ?? 'Inconnu';
                }

                $suggestions[] = [
                    'nom' => $repNom,
                    'type' => $type,
                    'quantite' => $avgQty,
                    'fournisseur' => [
                        'id' => $recommendedSupplierId,
                        'nom_societe' => $recommendedSupplierName
                    ]
                ];
                Log::info("Suggesting {$avgQty} of '{$repNom}' (type: {$type}) with supplier {$recommendedSupplierName}");
            }
        }

        Log::info('Total suggestions generated: ' . count($suggestions));

        // Fallback seulement si zéro events dans catégorie
        if (empty($suggestions)) {
            Log::info('No suggestions from data - using defaults');
            $suggestions = [
                ['nom' => 'Chaise', 'type' => 'Chaise', 'quantite' => $capacityMax, 'fournisseur' => ['id' => 1, 'nom_societe' => 'Défaut']],
                ['nom' => 'Table', 'type' => 'Table', 'quantite' => max(1, (int) ($capacityMax / 10)), 'fournisseur' => ['id' => 1, 'nom_societe' => 'Défaut']]
            ];
        }

        $response = ['resources' => $suggestions];

        return response()->json($response);

    } catch (\Exception $e) {
        Log::error('Exception in suggestResources: ' . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
    }
}



}
