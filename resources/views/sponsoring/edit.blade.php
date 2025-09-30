<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Modifier Sponsoring</h6>
                                    <p class="text-sm mb-0">Mettre à jour les informations du sponsoring</p>
                                </div>
                                <a href="{{ route('sponsoring.index') }}" class="btn btn-white btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sponsoring.update', $sponsoring->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="partenaire_id" class="form-control-label">Partenaire <span class="text-danger">*</span></label>
                                            <select class="form-control @error('partenaire_id') is-invalid @enderror" id="partenaire_id" name="partenaire_id" required>
                                                <option value="">Sélectionnez un partenaire</option>
                                                @foreach($partners as $partner)
                                                    <option value="{{ $partner->id }}" {{ old('partenaire_id', $sponsoring->partenaire_id) == $partner->id ? 'selected' : '' }}>
                                                        {{ $partner->nom }} ({{ $partner->type }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('partenaire_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="evenement_id" class="form-control-label">Événement <span class="text-danger">*</span></label>
                                            <select class="form-control @error('evenement_id') is-invalid @enderror" id="evenement_id" name="evenement_id" required>
                                                <option value="">Sélectionnez un événement</option>
                                                @foreach($events as $event)
                                                    <option value="{{ $event->id }}" {{ old('evenement_id', $sponsoring->evenement_id) == $event->id ? 'selected' : '' }}>
                                                        {{ $event->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('evenement_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_sponsoring" class="form-control-label">Type de Sponsoring <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type_sponsoring') is-invalid @enderror" id="type_sponsoring" name="type_sponsoring" required>
                                                <option value="">Sélectionnez un type</option>
                                                @foreach($typesSponsorings as $type)
                                                    <option value="{{ $type->value }}" {{ old('type_sponsoring', $sponsoring->type_sponsoring?->value) == $type->value ? 'selected' : '' }}>
                                                        {{ $type->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type_sponsoring')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="montant" class="form-control-label">Montant (€) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" class="form-control @error('montant') is-invalid @enderror" 
                                                   id="montant" name="montant" value="{{ old('montant', $sponsoring->montant) }}" required>
                                            @error('montant')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date" class="form-control-label">Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                                   id="date" name="date" value="{{ old('date', $sponsoring->date) }}" required>
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Mettre à Jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
