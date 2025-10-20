<x-app-layout>
    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Nouveau Partenaire</h6>
                                    <p class="text-sm mb-0">Ajouter un nouveau partenaire</p>
                                </div>
                                <a href="{{ route('partenaires.index') }}" class="btn btn-white btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Retour
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('partenaires.store') }}" method="POST" id="partnerForm" enctype="multipart/form-data">
                                @csrf
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
                                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Si un utilisateur est sélectionné, ses informations seront utilisées automatiquement</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nom" class="form-control-label">Nom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                   id="nom" name="nom" value="{{ old('nom') }}" required>
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
                                                <option value="Entreprise" {{ old('type') == 'Entreprise' ? 'selected' : '' }}>Entreprise</option>
                                                <option value="Association" {{ old('type') == 'Association' ? 'selected' : '' }}>Association</option>
                                                <option value="Institution" {{ old('type') == 'Institution' ? 'selected' : '' }}>Institution</option>
                                                <option value="ONG" {{ old('type') == 'ONG' ? 'selected' : '' }}>ONG</option>
                                                <option value="Autre" {{ old('type') == 'Autre' ? 'selected' : '' }}>Autre</option>
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
                                                   id="contact" name="contact" value="{{ old('contact') }}">
                                            @error('contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Email <span class="text-danger" id="email-required">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}">
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
                                                   id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                            @error('telephone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="form-control-label">Logo / Photo</label>
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                                   id="logo" name="logo" accept="image/*" style="display: none;">
                                            <input type="hidden" id="cropped-logo" name="cropped_logo">
                                            
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('logo').click()">
                                                    <i class="fas fa-upload me-2"></i>Choisir une image
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" id="remove-logo-btn" style="display: none;">
                                                    <i class="fas fa-trash me-2"></i>Supprimer
                                                </button>
                                            </div>
                                            
                                            @error('logo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Formats acceptés: JPG, PNG, GIF (Max: 2MB) - Vous pourrez recadrer l'image</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Logo Preview -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="logo-preview-container" class="mb-3" style="display: none;">
                                            <label class="form-control-label">Aperçu du logo:</label>
                                            <div class="mt-2 text-center">
                                                <img id="final-preview" src="" alt="Logo preview" 
                                                     style="max-width: 200px; max-height: 200px; border: 2px solid #667eea; border-radius: 8px; padding: 10px; background: white;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Créer le Partenaire
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de Crop -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-crop me-2"></i>Recadrer et Ajuster le Logo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Astuce:</strong> Utilisez la molette de la souris pour zoomer, glissez pour déplacer l'image
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div style="max-height: 400px; overflow: hidden;">
                                <img id="crop-image" src="" alt="Image à recadrer" style="max-width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="zoom-in">
                                    <i class="fas fa-search-plus"></i> Zoom +
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="zoom-out">
                                    <i class="fas fa-search-minus"></i> Zoom -
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="rotate-left">
                                    <i class="fas fa-undo"></i> Rotation ←
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="rotate-right">
                                    <i class="fas fa-redo"></i> Rotation →
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="reset-crop">
                                    <i class="fas fa-sync"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <label class="form-label text-sm">Ratio d'aspect:</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm ratio-btn" data-ratio="free">
                                    <i class="fas fa-expand"></i> Libre
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm ratio-btn active" data-ratio="1">
                                    <i class="fas fa-square"></i> Carré (1:1)
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm ratio-btn" data-ratio="1.5">
                                    <i class="fas fa-rectangle-landscape"></i> 3:2
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm ratio-btn" data-ratio="1.7778">
                                    <i class="fas fa-tv"></i> 16:9
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-primary" id="crop-confirm">
                        <i class="fas fa-check me-2"></i>Valider le Recadrage
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    <script>
        let cropper = null;
        let cropModal = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
            
            // User ID change handler
            document.getElementById('user_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const contactInput = document.getElementById('contact');
                const emailInput = document.getElementById('email');
                
                if (this.value) {
                    contactInput.value = selectedOption.getAttribute('data-name');
                    emailInput.value = selectedOption.getAttribute('data-email');
                    contactInput.readOnly = true;
                    emailInput.readOnly = true;
                    contactInput.classList.add('bg-light');
                    emailInput.classList.add('bg-light');
                    document.getElementById('contact-required').style.display = 'none';
                    document.getElementById('email-required').style.display = 'none';
                } else {
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

            // Logo file input change
            document.getElementById('logo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('L\'image ne doit pas dépasser 2MB');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const image = document.getElementById('crop-image');
                        image.src = event.target.result;
                        
                        // Destroy previous cropper if exists
                        if (cropper) {
                            cropper.destroy();
                        }
                        
                        // Show modal
                        cropModal.show();
                        
                        // Initialize cropper when modal is shown
                        setTimeout(() => {
                            cropper = new Cropper(image, {
                                aspectRatio: 1,
                                viewMode: 2,
                                dragMode: 'move',
                                autoCropArea: 0.8,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        }, 300);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Zoom controls
            document.getElementById('zoom-in').addEventListener('click', function() {
                if (cropper) cropper.zoom(0.1);
            });

            document.getElementById('zoom-out').addEventListener('click', function() {
                if (cropper) cropper.zoom(-0.1);
            });

            // Rotation controls
            document.getElementById('rotate-left').addEventListener('click', function() {
                if (cropper) cropper.rotate(-45);
            });

            document.getElementById('rotate-right').addEventListener('click', function() {
                if (cropper) cropper.rotate(45);
            });

            // Reset
            document.getElementById('reset-crop').addEventListener('click', function() {
                if (cropper) cropper.reset();
            });

            // Aspect ratio buttons
            document.querySelectorAll('.ratio-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.ratio-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const ratio = this.getAttribute('data-ratio');
                    if (cropper) {
                        if (ratio === 'free') {
                            cropper.setAspectRatio(NaN);
                        } else {
                            cropper.setAspectRatio(parseFloat(ratio));
                        }
                    }
                });
            });

            // Confirm crop
            document.getElementById('crop-confirm').addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        maxWidth: 800,
                        maxHeight: 800,
                        fillColor: '#fff',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });
                    
                    canvas.toBlob(function(blob) {
                        // Create a new File object
                        const file = new File([blob], 'logo.jpg', { type: 'image/jpeg' });
                        
                        // Create a FileList-like object
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        document.getElementById('logo').files = dataTransfer.files;
                        
                        // Show preview
                        const previewUrl = canvas.toDataURL('image/jpeg');
                        document.getElementById('final-preview').src = previewUrl;
                        document.getElementById('logo-preview-container').style.display = 'block';
                        document.getElementById('remove-logo-btn').style.display = 'inline-block';
                        
                        // Close modal
                        cropModal.hide();
                        
                        // Destroy cropper
                        cropper.destroy();
                        cropper = null;
                    }, 'image/jpeg', 0.9);
                }
            });

            // Remove logo
            document.getElementById('remove-logo-btn').addEventListener('click', function() {
                document.getElementById('logo').value = '';
                document.getElementById('logo-preview-container').style.display = 'none';
                this.style.display = 'none';
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });

            // Cleanup on modal close
            document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });
        });
    </script>
</x-app-layout>
