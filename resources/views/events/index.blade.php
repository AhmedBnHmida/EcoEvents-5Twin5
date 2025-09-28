@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des Événements</h1>
    
    <div class="row">
        @foreach($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">
                            <small class="text-muted">
                                {{ $event->start_date->format('d/m/Y H:i') }}
                            </small>
                        </p>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                        <p class="card-text">
                            <strong>Lieu:</strong> {{ $event->location }}
                        </p>
                        @if($event->category)
                            <span class="badge bg-primary">{{ $event->category->name }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Simple Pagination -->
    <div class="d-flex justify-content-center">
        {{ $events->links() }}
    </div>
</div>
@endsection