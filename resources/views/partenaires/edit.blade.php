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
                                    <h6 class="font-weight-semibold text-lg mb-0">Modifier Partenaire</h6>
                                    <p class="text-sm mb-0">Mettre à jour les informations du partenaire</p>
                                </div>
                                <a href="{{ route('partenaires.index') }}" class="btn btn-white btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('partenaires.update', $partner->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="user_id" class="form-control-label">Lier à un utilisateur (optionnel)</label>
                                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                                <option value="">Saisie manuelle</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" 
                                                            data-name="{{ $user->name }}" 
                                                            data-email="{{ $user->email }}"
                                                            {{ old('user_id', $partner->user_id) == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nom" class="form-control-label">Nom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                   id="nom" name="nom" value="{{ old('nom', $partner->nom) }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="form-control-label">Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="">Sélectionnez un type</option>
                                                <option value="Entreprise" {{ old('type', $partner->type) == 'Entreprise' ? 'selected' : '' }}>Entreprise</option>
                                                <option value="Association" {{ old('type', $partner->type) == 'Association' ? 'selected' : '' }}>Association</option>
                                                <option value="Institution" {{ old('type', $partner->type) == 'Institution' ? 'selected' : '' }}>Institution</option>
                                                <option value="ONG" {{ old('type', $partner->type) == 'ONG' ? 'selected' : '' }}>ONG</option>
                                                <option value="Autre" {{ old('type', $partner->type) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact" class="form-control-label">Personne de Contact <span class="text-danger" id="contact-required">*</span></label>
                                            <input type="text" class="form-control @error('contact') is-invalid @enderror" 
                                                   id="contact" name="contact" value="{{ old('contact', $partner->contact) }}"
                                                   {{ $partner->user_id ? 'readonly' : '' }} 
                                                   class="{{ $partner->user_id ? 'bg-light' : '' }}">
                                            @error('contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Email <span class="text-danger" id="email-required">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email', $partner->email) }}"
                                                   {{ $partner->user_id ? 'readonly' : '' }}
                                                   class="{{ $partner->user_id ? 'bg-light' : '' }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telephone" class="form-control-label">Téléphone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                                                   id="telephone" name="telephone" value="{{ old('telephone', $partner->telephone) }}" required>
                                            @error('telephone')
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

    <script>
        document.getElementById('user_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const contactInput = document.getElementById('contact');
            const emailInput = document.getElementById('email');
            
            if (this.value) {
                // User selected, auto-fill and disable
                contactInput.value = selectedOption.getAttribute('data-name');
                emailInput.value = selectedOption.getAttribute('data-email');
                contactInput.readOnly = true;
                emailInput.readOnly = true;
                contactInput.classList.add('bg-light');
                emailInput.classList.add('bg-light');
                document.getElementById('contact-required').style.display = 'none';
                document.getElementById('email-required').style.display = 'none';
            } else {
                // Manual entry
                contactInput.value = '';
                emailInput.value = '';
                contactInput.readOnly = false;
                emailInput.readOnly = false;
                contactInput.classList.remove('bg-light');
                emailInput.classList.remove('bg-light');
                document.getElementById('contact-required').style.display = 'inline';
                document.getElementById('email-required').style.display = 'inline';
            }
        });
    </script>
</x-app-layout>
