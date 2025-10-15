@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Catégories de Feedback</h6>
                    <a href="{{ route('feedback.categories.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouvelle Catégorie
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        @if(session('success'))
                            <div class="alert alert-success mx-4 mt-3" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Icône</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Couleur</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ordre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Statut</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Feedbacks</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $category->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ Str::limit($category->description, 50) }}</p>
                                    </td>
                                    <td>
                                        @if($category->icon)
                                            <i class="{{ $category->icon }}" style="color: {{ $category->color }}"></i>
                                        @else
                                            <span class="text-xs text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="color-box me-2" style="display:inline-block; width:15px; height:15px; background-color:{{ $category->color }}; border-radius:3px;"></span>
                                            <span class="text-xs">{{ $category->color }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs text-secondary mb-0">{{ $category->display_order }}</span>
                                    </td>
                                    <td>
                                        @if($category->active)
                                            <span class="badge bg-gradient-success">Actif</span>
                                        @else
                                            <span class="badge bg-gradient-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-info">{{ $category->feedbacks->count() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <a href="{{ route('feedback.categories.show', $category->id) }}" class="btn btn-link text-info px-1 mb-0" data-bs-toggle="tooltip" title="Voir les feedbacks">
                                                <i class="fas fa-eye text-info me-2"></i>
                                            </a>
                                            <a href="{{ route('feedback.categories.edit', $category->id) }}" class="btn btn-link text-dark px-1 mb-0" data-bs-toggle="tooltip" title="Modifier">
                                                <i class="fas fa-pencil-alt text-dark me-2"></i>
                                            </a>
                                            <form action="{{ route('feedback.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger px-1 mb-0" data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="fas fa-trash text-danger me-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                @if($categories->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <p class="text-secondary mb-0">Aucune catégorie de feedback n'a été créée.</p>
                                        <a href="{{ route('feedback.categories.create') }}" class="btn btn-sm btn-primary mt-3">
                                            <i class="fas fa-plus me-2"></i>Créer la première catégorie
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
