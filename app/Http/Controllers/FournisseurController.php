<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
    {
        // Replace ::all() with ::paginate()
        $fournisseurs = Fournisseur::paginate(10); // Adjust the number per page as needed (e.g., 10 items per page)
        return view('fournisseurs.index', compact('fournisseurs'));
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
            'telephone'       => 'required|string|max:20',
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
