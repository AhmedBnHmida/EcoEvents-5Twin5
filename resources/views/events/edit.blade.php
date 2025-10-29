<x-app-layout>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <x-app.navbar />
    <div class="container-fluid py-4 px-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-xs border">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="font-weight-semibold text-lg mb-0">Edit Event</h6>
                                <p class="text-sm mb-0">Update event information</p>
                            </div>
                            <a href="{{ route('events.index') }}" class="btn btn-white btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        

                        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" id="edit-event-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="form-control-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                               id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="categorie_id" class="form-control-label">Category</label>
                                        <select class="form-control @error('categorie_id') is-invalid @enderror" 
                                                id="categorie_id" name="categorie_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('categorie_id', $event->categorie_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categorie_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description" class="form-control-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" required>{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date" class="form-control-label">Start Date</label>
                                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                               id="start_date" name="start_date" 
                                               value="{{ old('start_date', $event->start_date?->format('Y-m-d\TH:i') ?? '') }}" required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date" class="form-control-label">End Date</label>
                                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                               id="end_date" name="end_date" 
                                               value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i') ?? '') }}" required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="registration_deadline" class="form-control-label">Registration Deadline</label>
                                        <input type="datetime-local" class="form-control @error('registration_deadline') is-invalid @enderror" 
                                               id="registration_deadline" name="registration_deadline" 
                                               value="{{ old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i') ?? '') }}" required>
                                        @error('registration_deadline')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="location" class="form-control-label">Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                               id="location" name="location" value="{{ old('location', $event->location) }}" required>
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="capacity_max" class="form-control-label">Max Capacity</label>
                                        <input type="number" class="form-control @error('capacity_max') is-invalid @enderror" 
                                               id="capacity_max" name="capacity_max" value="{{ old('capacity_max', $event->capacity_max) }}" min="1" required>
                                        @error('capacity_max')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price" class="form-control-label">Price (TND)</label>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', $event->price) }}" min="0" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->value }}" {{ old('status', $event->status?->value ?? '') == $status->value ? 'selected' : '' }}>
                                                    {{ $status->value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Visibility</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                                   {{ old('is_public', $event->is_public) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_public">
                                                Public Event
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="font-weight-semibold text-lg mb-0">Event Images</h6>
                                    <p class="text-sm mb-0">Manage event images</p>
                                </div>
                                <div class="card-body">
                                    <!-- Current Images -->
                                    @if($event->images && count($event->images) > 0)
                                        <div class="mb-4">
                                            <h6 class="text-sm font-weight-bold mb-3">Current Images</h6>
                                            <div class="row">
                                                @foreach($event->images as $index => $imagePath)
                                                    <div class="col-md-3 mb-3">
                                                        <div class="position-relative">
                                                            <img src="{{ str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath) }}" 
                                                                 class="img-thumbnail w-100" style="height: 150px; object-fit: cover;"
                                                                 onerror="this.src='https://via.placeholder.com/150?text=Image+Not+Found'">
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="remove_images[]" value="{{ $imagePath }}" 
                                                                       id="remove_image_{{ $index }}">
                                                                <label class="form-check-label text-danger" for="remove_image_{{ $index }}">
                                                                    Remove
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- New Images Upload -->
                                    <div class="form-group">
                                        <label for="images" class="form-control-label">Add New Images</label>
                                        <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                               id="images" name="images[]" multiple accept="image/*">
                                        @error('images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Select additional images (JPEG, PNG, JPG, GIF, WebP, max: 2MB each)</small>
                                    </div>
                                    
                                    <div id="images-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="font-weight-semibold text-lg mb-0">Resources</h6>
                                    <p class="text-sm mb-0">Manage resources for the event</p>
                                </div>
                                <div class="card-body">
                                    <div id="resources-container">
    @foreach($event->ressources as $index => $resource)
    <div class="resource-row mb-3" data-index="{{ $index }}">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label">Resource Name</label>
                    <input type="text" class="form-control @error('resources.'.$index.'.nom') is-invalid @enderror" 
                           name="resources[{{ $index }}][nom]" value="{{ old('resources.'.$index.'.nom', $resource->nom) }}" required>
                    @error('resources.'.$index.'.nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label">Resource Type</label>
                    <select class="form-control @error('resources.'.$index.'.type') is-invalid @enderror" 
                            name="resources[{{ $index }}][type]" required>
                        <option value="">Select Type</option>
                        @foreach($resourceTypes as $type)
                            <option value="{{ $type }}" {{ old('resources.'.$index.'.type', $resource->type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('resources.'.$index.'.type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label">Quantity</label>
                    <input type="number" class="form-control @error('resources.'.$index.'.quantite') is-invalid @enderror" 
                           name="resources[{{ $index }}][quantite]" value="{{ old('resources.'.$index.'.quantite', $resource->quantite ?? 1) }}" min="1" required>
                    @error('resources.'.$index.'.quantite')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label">Supplier</label>
                    <select class="form-control @error('resources.'.$index.'.fournisseur_id') is-invalid @enderror" 
                            name="resources[{{ $index }}][fournisseur_id]" required>
                        <option value="">Select Supplier</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ old('resources.'.$index.'.fournisseur_id', $resource->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->nom_societe }}
                            </option>
                        @endforeach
                    </select>
                    @error('resources.'.$index.'.fournisseur_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-resource w-100">Remove</button>
            </div>
        </div>
        <input type="hidden" name="resources[{{ $index }}][id]" value="{{ $resource->id }}">
    </div>
@endforeach
</div>
                                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-resource">Add Resource</button>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-white">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('events.index') }}" class="btn btn-white">Cancel</a>
                                        <button type="submit" class="btn btn-dark">Update Event</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    console.log('Edit form loaded. Debug mode active.');

    // Data for dynamic templates (passed from Blade)
    const resourceTypes = @json($resourceTypes);
    const fournisseurs = @json($fournisseurs->map(fn($f) => ['id' => $f->id, 'nom_societe' => $f->nom_societe]));

    // Add Resource Button
    document.getElementById('add-resource').addEventListener('click', function() {
        const container = document.getElementById('resources-container');
        const index = container.querySelectorAll('.resource-row').length;
        console.log('Adding new resource at index:', index);
        
        // Build selects dynamically in JS
        let typesOptions = '<option value="">Select Type</option>';
        resourceTypes.forEach(type => {
            typesOptions += `<option value="${type}">${type}</option>`;
        });

        let fournisseursOptions = '<option value="">Select Supplier</option>';
        fournisseurs.forEach(fournisseur => {
            fournisseursOptions += `<option value="${fournisseur.id}">${fournisseur.nom_societe}</option>`;
        });

        const template = `
            <div class="resource-row mb-3" data-index="${index}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Resource Name</label>
                            <input type="text" class="form-control" name="resources[${index}][nom]" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-control-label">Resource Type</label>
                            <select class="form-control" name="resources[${index}][type]" required>
                                ${typesOptions}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-control-label">Quantity</label>
                            <input type="number" class="form-control" name="resources[${index}][quantite]" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-control-label">Supplier</label>
                            <select class="form-control" name="resources[${index}][fournisseur_id]" required>
                                ${fournisseursOptions}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-resource w-100">Remove</button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        console.log('New resource template inserted.');
    });

    // Remove Resource
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-resource')) {
            const row = e.target.closest('.resource-row');
            const index = row.dataset.index;
            console.log('Removing resource at index:', index);
            row.remove();
            console.log('Resource removed.');
        }
    });

    // Image Preview
    document.getElementById('images').addEventListener('change', function(e) {
        console.log('Image files selected:', e.target.files.length);
        const previewContainer = document.getElementById('images-preview');
        previewContainer.innerHTML = '';

        Array.from(this.files).forEach((file, index) => {
            console.log(`Processing file ${index + 1}:`, file.name, file.size);
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'position-relative col-md-3 mb-3';
                col.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/150?text=Preview+Error'">
                    <small class="d-block text-center text-xs mt-1">${file.name} (${(file.size / 1024).toFixed(1)} KB)</small>
                `;
                previewContainer.appendChild(col);
            };
            reader.onerror = function() {
                console.error('Error reading file:', file.name);
            };
            reader.readAsDataURL(file);
        });
    });

    // Form Submission Debug
    document.getElementById('edit-event-form').addEventListener('submit', function(e) {
        console.log('Form submitting...');
        // Log resources data
        const resources = [];
        document.querySelectorAll('.resource-row').forEach((row, idx) => {
            const nom = row.querySelector(`input[name="resources[${idx}][nom]"]`).value;
            const type = row.querySelector(`select[name="resources[${idx}][type]"]`).value;
            const quantite = row.querySelector(`input[name="resources[${idx}][quantite]"]`).value;
            const fournisseur_id = row.querySelector(`select[name="resources[${idx}][fournisseur_id]"]`).value;
            const id = row.querySelector(`input[name="resources[${idx}][id]"]`)?.value || 'new';
            resources.push({ index: idx, nom, type, quantite, fournisseur_id, id });
        });
        console.log('Resources data:', resources);
        console.log('Remove images selected:', Array.from(document.querySelectorAll('input[name="remove_images[]"]:checked')).map(cb => cb.value));
        console.log('New images count:', document.getElementById('images').files.length);
        console.log('Form data summary complete.');
    });
</script>
</x-app-layout>