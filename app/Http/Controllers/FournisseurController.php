<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $query = Fournisseur::query();

    // Filters
    if ($request->filled('nom_societe')) {
        $query->where('nom_societe', 'like', '%' . $request->nom_societe . '%');
    }
    if ($request->filled('domaine_service')) {
        $query->where('domaine_service', $request->domaine_service);
    }
    if ($request->filled('adresse')) {
        $query->where('adresse', 'like', '%' . $request->adresse . '%');
    }

    // Paginate and keep filters in pagination links
    $fournisseurs = $query->paginate(10)->appends($request->except('page'));

    // Stats
    $totalFournisseurs = Fournisseur::count();
    $totalRessources = \App\Models\Ressource::count();
    $averageRessourcesPerFournisseur = $totalFournisseurs ? round($totalRessources / $totalFournisseurs, 2) : 0;
    $totalTypes = count(\App\Models\TypeRessource::allTypes());

    return view('fournisseurs.index', compact(
        'fournisseurs',
        'totalFournisseurs',
        'totalRessources',
        'averageRessourcesPerFournisseur',
        'totalTypes'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fournisseurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_societe'     => 'required|string|max:255',
            'domaine_service' => 'required|string|max:255',
            'adresse'         => 'required|string|max:255',
            'email'           => 'required|email|unique:fournisseurs,email',
            'telephone'       => 'required|string|max:9|min:8',
        ]);

        Fournisseur::create($validated);

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur)
    {
        return view('fournisseurs.show', compact('fournisseur'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom_societe'     => 'required|string|max:255',
            'domaine_service' => 'required|string|max:255',
            'adresse'         => 'required|string|max:255',
            'email'           => 'required|email|unique:fournisseurs,email,' . $fournisseur->id,
            'telephone'       => 'required|string|max:20',
        ]);

        $fournisseur->update($validated);

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur supprimé avec succès.');
    }
}
