@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Catégorie: {{ $category->name }}</h6>
                        <div class="d-flex align-items-center">
                            @if($category->icon)
                                <i class="{{ $category->icon }} me-2" style="color: {{ $category->color }}"></i>
                            @endif
                            <span class="badge me-2" style="background-color: {{ $category->color }}">{{ $category->color }}</span>
                            @if($category->active)
                                <span class="badge bg-gradient-success me-2">Actif</span>
                            @else
                                <span class="badge bg-gradient-secondary me-2">Inactif</span>
                            @endif
                            <span class="text-sm text-secondary">Ordre: {{ $category->display_order }}</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('feedback.categories.edit', $category->id) }}" class="btn btn-sm btn-info me-2">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('feedback.categories.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0 pb-2">
                    @if($category->description)
                        <div class="p-3 bg-light rounded mb-4 mt-3">
                            <p class="mb-0">{{ $category->description }}</p>
                        </div>
                    @endif
                    
                    <h6 class="mb-3">Feedbacks dans cette catégorie ({{ $feedbacks->total() }})</h6>
                    
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Participant</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Événement</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Note</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Commentaire</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($feedbacks as $feedback)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $feedback->participant->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $feedback->participant->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $feedback->event->title }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $feedback->event->start_date->format('d/m/Y') }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 text-xs font-weight-bold">{{ $feedback->note }}/5</span>
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $feedback->note)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ Str::limit($feedback->commentaire, 50) }}</p>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $feedback->date_feedback->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('feedback.edit', $feedback->id_feedback) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit feedback">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-secondary mb-0">Aucun feedback dans cette catégorie.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
