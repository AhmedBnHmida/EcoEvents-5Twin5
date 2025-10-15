@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Modifier la catégorie de feedback</h6>
                    <a href="{{ route('feedback.categories.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('feedback.categories.update', $category->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="color" class="form-control-label">Couleur</label>
                                    <div class="input-group">
                                        <span class="input-group-text p-0">
                                            <input type="color" class="form-control form-control-color border-0" id="color_picker" value="{{ old('color', $category->color) }}" title="Choisir une couleur">
                                        </span>
                                        <input class="form-control @error('color') is-invalid @enderror" type="text" id="color" name="color" value="{{ old('color', $category->color) }}">
                                    </div>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon" class="form-control-label">Icône (classe Font Awesome)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i id="icon_preview" class="{{ old('icon', $category->icon) }}"></i></span>
                                        <input class="form-control @error('icon') is-invalid @enderror" type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="fas fa-star">
                                    </div>
                                    <small class="form-text text-muted">Ex: fas fa-star, fas fa-comment, etc.</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_order" class="form-control-label">Ordre d'affichage</label>
                                    <input class="form-control @error('display_order') is-invalid @enderror" type="number" id="display_order" name="display_order" value="{{ old('display_order', $category->display_order) }}" min="0">
                                    @error('display_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-control-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $category->active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Actif</label>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Mettre à jour la catégorie</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update color input when color picker changes
        document.getElementById('color_picker').addEventListener('input', function() {
            document.getElementById('color').value = this.value;
        });
        
        // Update icon preview when icon input changes
        document.getElementById('icon').addEventListener('input', function() {
            document.getElementById('icon_preview').className = this.value;
        });
    });
</script>
@endpush
@endsection
