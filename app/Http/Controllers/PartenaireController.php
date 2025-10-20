<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartenaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Partner::with('sponsorings');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Filter linked users only
        if ($request->has('has_user') && $request->has_user == '1') {
            $query->whereNotNull('user_id');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $partners = $query->paginate(10)->appends($request->except('page'));
        
        // Get unique types for filter
        $types = Partner::select('type')->distinct()->pluck('type');

        return view('partenaires.index', compact('partners', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin can create partners
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        
        // Get users who are not already partners
        $users = \App\Models\User::whereDoesntHave('partnerProfile')->get();
        
        return view('partenaires.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create partners
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:partners,user_id',
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'contact' => 'nullable|required_without:user_id|string|max:255',
            'email' => 'nullable|required_without:user_id|email|max:255',
            'telephone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('logo');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partners/logos', 'public');
            $data['logo'] = $logoPath;
        }

        Partner::create($data);

        return redirect()->route('partenaires.index')
            ->with('success', 'Partenaire créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $partner = Partner::with(['sponsorings.event'])->findOrFail($id);
        return view('partenaires.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Only admin can edit partners
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $partner = Partner::findOrFail($id);
        
        // Get users who are not already partners (excluding current partner's user)
        $users = \App\Models\User::whereDoesntHave('partnerProfile')
            ->orWhere('id', $partner->user_id)
            ->get();
        
        return view('partenaires.edit', compact('partner', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Only admin can update partners
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $partner = Partner::findOrFail($id);

        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:partners,user_id,' . $id,
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'contact' => 'nullable|required_without:user_id|string|max:255',
            'email' => 'nullable|required_without:user_id|email|max:255',
            'telephone' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('logo');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($partner->logo && \Storage::disk('public')->exists($partner->logo)) {
                \Storage::disk('public')->delete($partner->logo);
            }
            
            $logoPath = $request->file('logo')->store('partners/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $partner->update($data);

        return redirect()->route('partenaires.index')
            ->with('success', 'Partenaire modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Only admin can delete partners
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $partner = Partner::findOrFail($id);
        
        // Delete logo if exists
        if ($partner->logo && \Storage::disk('public')->exists($partner->logo)) {
            \Storage::disk('public')->delete($partner->logo);
        }
        
        $partner->delete();

        return redirect()->route('partenaires.index')
            ->with('success', 'Partenaire supprimé avec succès.');
    }
}
