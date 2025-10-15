<?php

namespace App\Http\Controllers;

use App\Models\FeedbackCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $categories = FeedbackCategory::orderBy('display_order')->get();
        return view('feedback.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('feedback.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);
        
        FeedbackCategory::create($request->all());
        
        return redirect()->route('feedback.categories.index')
            ->with('success', 'Catégorie de feedback créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $category = FeedbackCategory::findOrFail($id);
        $feedbacks = $category->feedbacks()->with(['event', 'participant'])->paginate(10);
        
        return view('feedback.categories.show', compact('category', 'feedbacks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $category = FeedbackCategory::findOrFail($id);
        return view('feedback.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);
        
        $category = FeedbackCategory::findOrFail($id);
        $category->update($request->all());
        
        return redirect()->route('feedback.categories.index')
            ->with('success', 'Catégorie de feedback mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $category = FeedbackCategory::findOrFail($id);
        
        // Set category_id to null for all related feedbacks
        $category->feedbacks()->update(['category_id' => null]);
        
        $category->delete();
        
        return redirect()->route('feedback.categories.index')
            ->with('success', 'Catégorie de feedback supprimée avec succès.');
    }
}
