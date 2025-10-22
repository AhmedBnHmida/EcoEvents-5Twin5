<x-app-layout>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            /* Styles pour la validation en temps réel */
            .form-control.valid {
                border-color: #28a745;
                box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            }
            
            .form-control.invalid {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            }
            
            .validation-feedback {
                display: none;
                font-size: 0.875em;
                margin-top: 0.25rem;
            }
            
            .validation-feedback.valid {
                color: #28a745;
                display: block;
            }
            
            .validation-feedback.invalid {
                color: #dc3545;
                display: block;
            }
            
            .character-count {
                font-size: 0.75rem;
                text-align: right;
                margin-top: 0.25rem;
            }
            
            .character-count.near-limit {
                color: #ffc107;
            }
            
            .character-count.over-limit {
                color: #dc3545;
            }
        </style>
    </head>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-xs border">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="font-weight-semibold text-lg mb-0">Create Event</h6>
                                    <p class="text-sm mb-0">Add a new event</p>
                                </div>
                                <a href="{{ route('events.index') }}" class="btn btn-white btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="event-form" novalidate>
                                @csrf
                                
                                <!-- AI Assistant Section -->
                                <div class="alert alert-info mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-robot fa-2x me-3"></i>
                                        <div>
                                            <h6 class="alert-heading mb-1">AI Event Assistant</h6>
                                            <p class="mb-0">Let AI help you create a professional event quickly</p>
                                            <button type="button" class="btn btn-outline-primary" id="generate-complete-event-btn">
                                                <i class="fas fa-robot me-1"></i> Generate Complete Event
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- AI Loading Indicator -->
                                <div id="ai-loading" class="alert alert-info" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i> AI is generating content...
                                </div>

                                <!-- Title & Category -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="form-control-label">Title *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                       id="title" name="title" value="{{ old('title') }}" 
                                                       required minlength="5" maxlength="255"
                                                       pattern="^[A-Za-z0-9\s\-_,\.!?&()]+$"
                                                       title="Title must contain only letters, numbers, spaces, and basic punctuation">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span id="title-length">0</span>/255
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="validation-feedback" id="title-feedback"></div>
                                            @error('title')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">5-255 characters, letters, numbers, spaces and basic punctuation only</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="categorie_id" class="form-control-label">Category *</label>
                                            <select class="form-control @error('categorie_id') is-invalid @enderror" 
                                                    id="categorie_id" name="categorie_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('categorie_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="validation-feedback" id="categorie_id-feedback"></div>
                                            @error('categorie_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description *</label>
                                    <div class="input-group">
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="5" 
                                                  required minlength="50" maxlength="2000">{{ old('description') }}</textarea>
                                        <button type="button" class="btn btn-outline-secondary" id="generate-description-btn">
                                            <i class="fas fa-magic me-1"></i> AI Generate
                                        </button>
                                    </div>
                                    <div class="character-count" id="description-count">
                                        <span id="description-length">0</span>/2000 characters
                                    </div>
                                    <div class="validation-feedback" id="description-feedback"></div>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum 50 characters, maximum 2000 characters</small>
                                </div>

                                <!-- Dates -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date" class="form-control-label">Start Date *</label>
                                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" 
                                                   required min="{{ now()->addHour()->format('Y-m-d\TH:i') }}">
                                            <div class="validation-feedback" id="start_date-feedback"></div>
                                            @error('start_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="end_date" class="form-control-label">End Date *</label>
                                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                            <div class="validation-feedback" id="end_date-feedback"></div>
                                            @error('end_date')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="registration_deadline" class="form-control-label">Registration Deadline *</label>
                                            <input type="datetime-local" class="form-control @error('registration_deadline') is-invalid @enderror" 
                                                   id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}" required>
                                            <div class="validation-feedback" id="registration_deadline-feedback"></div>
                                            @error('registration_deadline')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Location & Capacity & Price -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="location" class="form-control-label">Location *</label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                                   id="location" name="location" value="{{ old('location') }}" 
                                                   required minlength="5" maxlength="500">
                                            <div class="validation-feedback" id="location-feedback"></div>
                                            @error('location')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Minimum 5 characters</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="capacity_max" class="form-control-label">Max Capacity *</label>
                                            <input type="number" class="form-control @error('capacity_max') is-invalid @enderror" 
                                                   id="capacity_max" name="capacity_max" value="{{ old('capacity_max') }}" 
                                                   required min="1" max="100000" step="1">
                                            <div class="validation-feedback" id="capacity_max-feedback"></div>
                                            @error('capacity_max')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">1 - 100,000 attendees</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price" class="form-control-label">Price ($) *</label>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                id="price" name="price" value="{{ old('price', 0) }}" 
                                                required min="0" max="10000" step="0.01">
                                            <div class="validation-feedback" id="price-feedback"></div>
                                            @error('price')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">0 - 10,000 USD (0 for free events)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Visibility -->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="form-control-label">Status *</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="">Select Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                        {{ $status->value }}
                    </option>
                @endforeach
            </select>
            <div class="validation-feedback" id="status-feedback"></div>
            @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <!-- Only show visibility toggle for admin -->
    @if(auth()->user()->role === 'admin')
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-control-label">Visibility</label>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_public">
                    Public Event
                </label>
            </div>
        </div>
    </div>
    @else
    <!-- Hidden field for organizers - always false -->
    <input type="hidden" name="is_public" value="0">
    @endif
</div>

                                <!-- Event Images -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="font-weight-semibold text-lg mb-0">Event Images</h6>
                                        <p class="text-sm mb-0">Upload images for the event</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="images" class="form-control-label">Event Images</label>
                                            <input type="file" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                                   id="images" name="images[]" multiple accept="image/*"
                                                   data-max-size="2097152" data-max-files="10">
                                            <div class="validation-feedback" id="images-feedback"></div>
                                            @error('images')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('images.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">You can select up to 10 images (JPEG, PNG, JPG, GIF, WebP, max: 2MB each)</small>
                                        </div>
                                        
                                        <div id="images-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                                        <div id="file-errors" class="alert alert-danger mt-2" style="display: none;"></div>
                                    </div>
                                </div>

                                <!-- Resources -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h6 class="font-weight-semibold text-lg mb-0">Resources</h6>
                                        <p class="text-sm mb-0">Add resources for the event. Suggestions auto-générées basées sur l'historique.</p>
                                    </div>
                                    <div class="card-body">
                                        <div id="resources-container">
                                            <!-- Row vide par défaut, sera remplacée par suggestions -->
                                            <div class="resource-row mb-3" data-index="0" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Resource Name</label>
                                                            <input type="text" class="form-control @error('resources.0.nom') is-invalid @enderror" 
                                                                   name="resources[0][nom]" value="{{ old('resources.0.nom') }}">
                                                            @error('resources.0.nom')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Resource Type</label>
                                                            <select class="form-control @error('resources.0.type') is-invalid @enderror" 
                                                                    name="resources[0][type]">
                                                                <option value="">Select Type</option>
                                                                @foreach($resourceTypes as $type)
                                                                    <option value="{{ $type }}" {{ old('resources.0.type') == $type ? 'selected' : '' }}>
                                                                        {{ $type }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('resources.0.type')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Quantity</label>
                                                            <input type="number" min="1" class="form-control @error('resources.0.quantite') is-invalid @enderror" 
                                                                   name="resources[0][quantite]" value="{{ old('resources.0.quantite', 1) }}">
                                                            @error('resources.0.quantite')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Supplier</label>
                                                            <select class="form-control @error('resources.0.fournisseur_id') is-invalid @enderror" 
                                                                    name="resources[0][fournisseur_id]">
                                                                <option value="">Select Supplier</option>
                                                                @foreach($fournisseurs as $fournisseur)
                                                                    <option value="{{ $fournisseur->id }}" {{ old('resources.0.fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                                                        {{ $fournisseur->nom_societe }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('resources.0.fournisseur_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 d-flex align-items-center">
                                                        <button type="button" class="btn btn-danger btn-sm remove-resource mt-4">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-resource">Add Resource</button>
                                        <div id="suggestions-loading" class="mt-2 text-muted" style="display: none;">Calcul en cours...</div>
                                    </div>
                                </div>

                                <!-- Event Success Prediction -->
                                <div class="card mt-4">
                                    <div class="card-header bg-gradient-success text-white">
                                        <h6 class="font-weight-semibold text-lg mb-0">
                                            <i class="fas fa-chart-line me-2"></i>AI Success Prediction
                                        </h6>
                                        <p class="text-sm mb-0 opacity-8">Professional analysis of your event's success potential</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="form-control-label text-success">Professional Analysis</label>
                                                    <div class="form-control bg-light" id="success-prediction" 
                                                        style="min-height: 200px; overflow-y: auto; white-space: pre-wrap;"
                                                        placeholder="Fill in event details and click 'Analyze Success' to get AI-powered professional insights...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button type="button" class="btn btn-success w-100" id="predict-success-btn">
                                                    <i class="fas fa-brain me-1"></i> Analyze Success
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-lightbulb me-1"></i>
                                                Our AI analyzes your event details and provides professional recommendations for success.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-outline-secondary me-2" id="validate-form">
                                        <i class="fas fa-check-circle me-1"></i> Validate Form
                                    </button>
                                    <button type="submit" class="btn btn-dark" id="submit-btn">
                                        <i class="fas fa-save me-1"></i> Create Event
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
        // Variables JS depuis Blade
        const resourceTypes = JSON.parse('@json($resourceTypes)');
        const fournisseurs = JSON.parse('@json($fournisseurs->map(function ($f) { return ["id" => $f->id, "nom_societe" => $f->nom_societe]; }))');
    
        // Token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Enhanced Generate Complete Event Function
        document.getElementById('generate-complete-event-btn').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const categoryId = document.getElementById('categorie_id').value;
            const capacity = document.getElementById('capacity_max').value;
            const loading = document.getElementById('ai-loading');
            
            if (!title || !categoryId || !capacity) {
                alert('Please enter title, category, and capacity first');
                return;
            }
            
            loading.style.display = 'block';
            
            fetch('{{ route("events.generate-complete-event") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    category_id: categoryId,
                    capacity: capacity
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                
                if (data.success) {
                    const event = data.event;
                    
                    // Fill ALL form fields with generated data
                    if (event.title) {
                        document.getElementById('title').value = event.title;
                    }
                    if (event.description) {
                        document.getElementById('description').value = event.description;
                    }
                    if (event.location) {
                        document.getElementById('location').value = event.location;
                    }
                    if (event.capacity_max) {
                        document.getElementById('capacity_max').value = event.capacity_max;
                    }
                    if (event.price !== undefined) {
                        document.getElementById('price').value = event.price;
                    }
                    if (event.status) {
                        document.getElementById('status').value = event.status;
                    }
                    if (event.is_public !== undefined) {
                        document.getElementById('is_public').checked = event.is_public;
                    }
                    
                    // Fill date fields with proper formatting
                    if (event.start_date) {
                        document.getElementById('start_date').value = formatDateForInput(event.start_date);
                    }
                    if (event.end_date) {
                        document.getElementById('end_date').value = formatDateForInput(event.end_date);
                    }
                    if (event.registration_deadline) {
                        document.getElementById('registration_deadline').value = formatDateForInput(event.registration_deadline);
                    }
                    
                    alert('🎉 Complete event generated successfully! All fields have been populated with AI suggestions.');
                } else {
                    alert('Failed to generate complete event: ' + data.message);
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                console.error('Error:', error);
                alert('Error generating complete event');
            });
        });

        // Enhanced Generate Description Function - with helpful alerts
        document.getElementById('generate-description-btn').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const categoryId = document.getElementById('categorie_id').value;
            const location = document.getElementById('location').value;
            const capacity = document.getElementById('capacity_max').value;
            const price = document.getElementById('price').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const loading = document.getElementById('ai-loading');
            
            // Check if all required fields are filled
            const missingFields = [];
            if (!title) missingFields.push('Title');
            if (!categoryId) missingFields.push('Category');
            if (!location) missingFields.push('Location');
            if (!capacity) missingFields.push('Capacity');
            if (!price) missingFields.push('Price');
            if (!startDate) missingFields.push('Start Date');
            if (!endDate) missingFields.push('End Date');
            
            if (missingFields.length > 0) {
                alert('Please fill in these fields first: ' + missingFields.join(', '));
                return;
            }
            
            loading.style.display = 'block';
            
            fetch('{{ route("events.generate-description") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    category_id: categoryId,
                    location: location,
                    capacity_max: capacity,
                    price: price,
                    start_date: startDate,
                    end_date: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                if (data.success) {
                    document.getElementById('description').value = data.description;
                    alert('✅ Description generated successfully!');
                } else {
                    alert('Failed to generate description: ' + data.message);
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                console.error('Error:', error);
                alert('Error generating description');
            });
        });

        // Enhanced Event Success Prediction with HTML display
        document.getElementById('predict-success-btn').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const categoryId = document.getElementById('categorie_id').value;
            const description = document.getElementById('description').value;
            const location = document.getElementById('location').value;
            const capacity = document.getElementById('capacity_max').value;
            const price = document.getElementById('price').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const regDeadline = document.getElementById('registration_deadline').value;
            const loading = document.getElementById('ai-loading');
            
            // Check if all required fields are filled
            const missingFields = [];
            if (!title) missingFields.push('Title');
            if (!categoryId) missingFields.push('Category');
            if (!description) missingFields.push('Description');
            if (!location) missingFields.push('Location');
            if (!capacity) missingFields.push('Capacity');
            if (!price) missingFields.push('Price');
            if (!startDate) missingFields.push('Start Date');
            if (!endDate) missingFields.push('End Date');
            if (!regDeadline) missingFields.push('Registration Deadline');
            
            if (missingFields.length > 0) {
                alert('Please fill in these fields first: ' + missingFields.join(', '));
                return;
            }
            
            loading.style.display = 'block';
            
            fetch('{{ route("events.predict-success") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    category_id: categoryId,
                    description: description,
                    location: location,
                    capacity_max: capacity,
                    price: price,
                    start_date: startDate,
                    end_date: endDate,
                    registration_deadline: regDeadline
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                if (data.success) {
                    // Use formatted HTML if available, otherwise use plain text
                    const predictionContent = data.formatted_prediction || data.prediction;
                    document.getElementById('success-prediction').innerHTML = predictionContent;
                    alert('✅ Success analysis generated!');
                } else {
                    alert('Failed to generate prediction: ' + data.message);
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                console.error('Error:', error);
                alert('Error generating prediction');
            });
        });

        // Helper function to format dates for datetime-local input
        function formatDateForInput(dateString) {
            if (!dateString) return '';
            
            // Handle various date formats from AI
            let date;
            
            // If it's already in ISO format (from AI response)
            if (dateString.includes('T')) {
                date = new Date(dateString);
            } 
            // If it's in "YYYY-MM-DD HH:MM:SS" format (common from AI)
            else if (dateString.includes(' ')) {
                // Replace space with T to make it ISO-like
                const isoString = dateString.replace(' ', 'T');
                date = new Date(isoString);
            }
            // Fallback - try direct parsing
            else {
                date = new Date(dateString);
            }
            
            // Check if date is valid
            if (isNaN(date.getTime())) {
                console.error('Invalid date:', dateString);
                return '';
            }
            
            // Format to YYYY-MM-DDTHH:MM for datetime-local input
            return date.toISOString().slice(0, 16);
        }


        // =============================================
        // SYSTÈME DE CONTRÔLE DE SAISIE
        // =============================================

        // Configuration de validation
        const validationRules = {
            title: {
                required: true,
                minLength: 5,
                maxLength: 255,
                messages: {
                    required: 'Title is required',
                    minLength: 'Title must be at least 5 characters',
                    maxLength: 'Title cannot exceed 255 characters',
                }
            },
            description: {
                required: true,
                minLength: 50,
                maxLength: 2000,
                messages: {
                    required: 'Description is required',
                    minLength: 'Description must be at least 50 characters',
                    maxLength: 'Description cannot exceed 2000 characters'
                }
            },
            location: {
                required: true,
                messages: {
                    required: 'Location is required',
                }
            },
            capacity_max: {
                required: true,
                min: 1,
                messages: {
                    required: 'Capacity is required',
                    min: 'Capacity must be at least 1',
                }
            },
            price: {
                required: true,
                min: 0,
                max: 10000,
                messages: {
                    required: 'Price is required',
                    min: 'Price cannot be negative',
                    max: 'Price cannot exceed $10,000'
                }
            },
            categorie_id: {
                required: true,
                messages: {
                    required: 'Please select a category'
                }
            },
            status: {
                required: true,
                messages: {
                    required: 'Please select a status'
                }
            },
            start_date: {
                required: true,
                messages: {
                    required: 'Start date is required'
                }
            },
            end_date: {
                required: true,
                messages: {
                    required: 'End date is required'
                }
            },
            registration_deadline: {
                required: true,
                messages: {
                    required: 'Registration deadline is required'
                }
            }
        };

        // Initialisation du système de validation
        function initValidationSystem() {
            // Événements pour tous les champs
            const fields = ['title', 'description', 'location', 'capacity_max', 'price', 'categorie_id', 'status', 'start_date', 'end_date', 'registration_deadline'];
            
            fields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    // Validation en temps réel
                    element.addEventListener('input', function() {
                        validateField(field);
                        updateCharacterCount(field);
                    });
                    
                    element.addEventListener('blur', function() {
                        validateField(field);
                    });
                    
                    element.addEventListener('change', function() {
                        validateField(field);
                    });
                }
            });

            // Validation spéciale pour les dates
            setupDateValidation();
            
            // Validation des fichiers
            setupFileValidation();
            
            // Validation du formulaire complet
            setupFormValidation();
        }

        // Validation d'un champ individuel
        function validateField(fieldName) {
            const field = document.getElementById(fieldName);
            const feedback = document.getElementById(fieldName + '-feedback');
            const rules = validationRules[fieldName];
            
            if (!field || !rules) return true;
            
            const value = field.value.trim();
            let isValid = true;
            let message = '';
            
            // Validation required
            if (rules.required && !value) {
                isValid = false;
                message = rules.messages.required;
            }
            
            // Validation minLength
            if (isValid && rules.minLength && value.length < rules.minLength) {
                isValid = false;
                message = rules.messages.minLength;
            }
            
            // Validation maxLength
            if (isValid && rules.maxLength && value.length > rules.maxLength) {
                isValid = false;
                message = rules.messages.maxLength;
            }
            
            // Validation pattern
            if (isValid && rules.pattern && !rules.pattern.test(value)) {
                isValid = false;
                message = rules.messages.pattern;
            }
            
            // Validation min/max pour les nombres
            if (isValid && rules.min !== undefined && parseFloat(value) < rules.min) {
                isValid = false;
                message = rules.messages.min;
            }
            
            if (isValid && rules.max !== undefined && parseFloat(value) > rules.max) {
                isValid = false;
                message = rules.messages.max;
            }
            
            // Mise à jour de l'UI
            updateFieldUI(field, feedback, isValid, message);
            
            return isValid;
        }

        // Mise à jour de l'interface utilisateur pour un champ
        function updateFieldUI(field, feedback, isValid, message) {
            field.classList.remove('valid', 'invalid');
            feedback.classList.remove('valid', 'invalid');
            
            if (field.value.trim() === '') {
                // Champ vide - état neutre
                return;
            }
            
            if (isValid) {
                field.classList.add('valid');
                feedback.classList.add('valid');
                feedback.textContent = '✓ Valid';
            } else {
                field.classList.add('invalid');
                feedback.classList.add('invalid');
                feedback.textContent = message;
            }
        }

        // Mise à jour du compteur de caractères
        function updateCharacterCount(fieldName) {
            const field = document.getElementById(fieldName);
            if (!field) return;
            
            const length = field.value.length;
            
            if (fieldName === 'title') {
                const counter = document.getElementById('title-length');
                if (counter) counter.textContent = length;
            }
            
            if (fieldName === 'description') {
                const counter = document.getElementById('description-length');
                const container = document.getElementById('description-count');
                
                if (counter) counter.textContent = length;
                if (container) {
                    container.className = 'character-count';
                    if (length > 1800) {
                        container.classList.add('over-limit');
                    } else if (length > 1500) {
                        container.classList.add('near-limit');
                    }
                }
            }
        }

        // Validation des dates
        function setupDateValidation() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const regDeadline = document.getElementById('registration_deadline');
            
            if (startDate && endDate) {
                startDate.addEventListener('change', validateDates);
                endDate.addEventListener('change', validateDates);
            }
            
            if (startDate && regDeadline) {
                startDate.addEventListener('change', validateRegistrationDeadline);
                regDeadline.addEventListener('change', validateRegistrationDeadline);
            }
        }

        function validateDates() {
            const start = new Date(document.getElementById('start_date').value);
            const end = new Date(document.getElementById('end_date').value);
            const feedback = document.getElementById('end_date-feedback');
            
            if (start && end && start >= end) {
                feedback.classList.add('invalid');
                feedback.textContent = 'End date must be after start date';
                document.getElementById('end_date').classList.add('invalid');
            } else {
                feedback.classList.remove('invalid');
                document.getElementById('end_date').classList.remove('invalid');
            }
        }

        function validateRegistrationDeadline() {
            const start = new Date(document.getElementById('start_date').value);
            const deadline = new Date(document.getElementById('registration_deadline').value);
            const feedback = document.getElementById('registration_deadline-feedback');
            
            if (start && deadline && deadline >= start) {
                feedback.classList.add('invalid');
                feedback.textContent = 'Registration deadline must be before start date';
                document.getElementById('registration_deadline').classList.add('invalid');
            } else {
                feedback.classList.remove('invalid');
                document.getElementById('registration_deadline').classList.remove('invalid');
            }
        }

        // Validation des fichiers
        function setupFileValidation() {
            const fileInput = document.getElementById('images');
            if (!fileInput) return;
            
            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const errors = [];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const maxFiles = 10;
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                
                // Vérification du nombre de fichiers
                if (files.length > maxFiles) {
                    errors.push(`You can only upload up to ${maxFiles} files`);
                }
                
                // Vérification de chaque fichier
                for (let file of files) {
                    // Taille du fichier
                    if (file.size > maxSize) {
                        errors.push(`"${file.name}" is too large. Maximum size is 2MB`);
                    }
                    
                    // Type de fichier
                    if (!allowedTypes.includes(file.type)) {
                        errors.push(`"${file.name}" is not a supported image format`);
                    }
                }
                
                // Affichage des erreurs
                const errorContainer = document.getElementById('file-errors');
                if (errors.length > 0) {
                    errorContainer.innerHTML = errors.map(error => `<div>• ${error}</div>`).join('');
                    errorContainer.style.display = 'block';
                    fileInput.value = ''; // Réinitialiser l'input
                } else {
                    errorContainer.style.display = 'none';
                }
            });
        }

        // Validation du formulaire complet
        function setupFormValidation() {
            const form = document.getElementById('event-form');
            const validateBtn = document.getElementById('validate-form');
            const submitBtn = document.getElementById('submit-btn');
            
            if (validateBtn) {
                validateBtn.addEventListener('click', function() {
                    const isValid = validateAllFields();
                    if (isValid) {
                        alert('✅ All fields are valid! You can submit the form.');
                    } else {
                        alert('❌ Please fix the validation errors before submitting.');
                    }
                });
            }
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateAllFields()) {
                        e.preventDefault();
                        alert('❌ Please fix the validation errors before submitting.');
                        scrollToFirstError();
                    }
                });
            }
        }

        // Validation de tous les champs
        function validateAllFields() {
            let isValid = true;
            const fields = Object.keys(validationRules);
            
            fields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });
            
            return isValid;
        }

        // Défilement vers la première erreur
        function scrollToFirstError() {
            const firstError = document.querySelector('.invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            initValidationSystem();
            
            // Mise à jour initiale des compteurs
            updateCharacterCount('title');
            updateCharacterCount('description');
            
            // Validation initiale des champs avec des valeurs
            Object.keys(validationRules).forEach(field => {
                const element = document.getElementById(field);
                if (element && element.value) {
                    validateField(field);
                }
            });
        });

        // Remove the old generate-event-btn function since we're using generate-complete-event-btn now
        // Keep your existing resource suggestion code as it is
        // ... [Your existing resource suggestion code remains unchanged] ...

        // Debounce pour éviter les appels multiples
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Fonction pour suggérer les ressources
        const suggestResources = debounce(function() {
            const categorieId = document.getElementById('categorie_id').value;
            const capacityMax = document.getElementById('capacity_max').value;

            console.log('Suggest triggered - Category ID:', categorieId, 'Capacity:', capacityMax); // Debug: Trigger check

            if (!categorieId || !capacityMax) {
                console.log('Suggest aborted - Missing category or capacity'); // Debug
                return; // Pas encore rempli
            }

            const loading = document.getElementById('suggestions-loading');
            loading.style.display = 'block';

            const payload = {
                categorie_id: parseInt(categorieId),
                capacity_max: parseInt(capacityMax)
            };
            console.log('Sending payload:', payload); // Debug: Payload

            fetch('{{ route("events.suggest-resources") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                console.log('Fetch response status:', response.status); // Debug: Response status
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data); // Debug: Full response
                loading.style.display = 'none';
                if (data.error) {
                    console.error('Server error:', data.error); // Debug
                    alert('Erreur: ' + data.error);
                    return;
                }
                populateResources(data.resources || []);
            })
            .catch(error => {
                console.error('Fetch error:', error); // Debug: Full error
                loading.style.display = 'none';
                alert('Erreur lors du calcul des ressources');
            });
        }, 500);

        // Écouteurs pour déclencher le calcul auto
        document.getElementById('categorie_id').addEventListener('change', function() {
            console.log('Category changed event fired'); // Debug: Event listener
            suggestResources();
        });
        document.getElementById('capacity_max').addEventListener('input', function() {
            console.log('Capacity input event fired'); // Debug: Event listener
            suggestResources();
        });

        // Fonction pour peupler les ressources suggérées
        function populateResources(resources) {
            console.log('Populate called with resources:', resources); // Debug: Resources array
            const container = document.getElementById('resources-container');
            container.innerHTML = ''; // Efface tout

            resources.forEach((resource, index) => {
                console.log(`Adding resource ${index}:`, resource); // Debug: Each resource

                // Génère options pour type
                let typeOptions = '<option value="">Select Type</option>';
                resourceTypes.forEach(type => {
                    const selected = resource.type === type ? 'selected' : '';
                    typeOptions += `<option value="${type}" ${selected}>${type}</option>`;
                });

                // Génère options pour fournisseur (pré-sélectionné)
                let supplierOptions = '<option value="">Select Supplier</option>';
                fournisseurs.forEach(f => {
                    const selected = (resource.fournisseur && resource.fournisseur.id == f.id) ? 'selected' : '';
                    supplierOptions += `<option value="${f.id}" ${selected}>${f.nom_societe}</option>`;
                });

                const row = document.createElement('div');
                row.className = 'resource-row mb-3';
                row.dataset.index = index;
                row.innerHTML = `
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Resource Name</label>
                                <input type="text" class="form-control" name="resources[${index}][nom]" value="${resource.nom || ''}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Resource Type</label>
                                <select class="form-control" name="resources[${index}][type]">${typeOptions}</select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label">Quantity</label>
                                <input type="number" min="1" class="form-control" name="resources[${index}][quantite]" value="${resource.quantite || 1}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Supplier</label>
                                <select class="form-control" name="resources[${index}][fournisseur_id]">${supplierOptions}</select>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-resource mt-4">Remove</button>
                        </div>
                    </div>
                `;
                container.appendChild(row);
            });

            // Si pas de suggestions, ajoute une row vide
            if (resources.length === 0) {
                console.log('No resources, adding empty row'); // Debug
                addEmptyResourceRow();
            } else {
                console.log('Resources populated successfully'); // Debug
            }
        }

        // Fonction pour ajouter une row vide (comme avant)
        function addEmptyResourceRow(index = document.querySelectorAll('.resource-row').length) {
            const container = document.getElementById('resources-container');

            // Génère options pour type (vide)
            let typeOptions = '<option value="">Select Type</option>';
            resourceTypes.forEach(type => {
                typeOptions += `<option value="${type}">${type}</option>`;
            });

            // Génère options pour fournisseur (vide)
            let supplierOptions = '<option value="">Select Supplier</option>';
            fournisseurs.forEach(f => {
                supplierOptions += `<option value="${f.id}">${f.nom_societe}</option>`;
            });

            const template = `
                <div class="resource-row mb-3" data-index="${index}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Resource Name</label>
                                <input type="text" class="form-control" name="resources[${index}][nom]">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Resource Type</label>
                                <select class="form-control" name="resources[${index}][type]">${typeOptions}</select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label">Quantity</label>
                                <input type="number" min="1" class="form-control" name="resources[${index}][quantite]" value="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Supplier</label>
                                <select class="form-control" name="resources[${index}][fournisseur_id]">${supplierOptions}</select>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-resource mt-4">Remove</button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            console.log('Empty row added'); // Debug
        }

        // Add resource row dynamically (mise à jour pour utiliser la fonction)
        document.getElementById('add-resource').addEventListener('click', function() {
            const index = document.querySelectorAll('.resource-row').length;
            addEmptyResourceRow(index);
        });

        // Remove resource row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-resource')) {
                console.log('Remove button clicked'); // Debug
                e.target.closest('.resource-row').remove();
                // Renumérote les index après suppression (optionnel, mais propre)
                const container = document.getElementById('resources-container');
                const rows = container.querySelectorAll('.resource-row');
                rows.forEach((row, idx) => {
                    row.dataset.index = idx;
                    const inputs = row.querySelectorAll('input, select');
                    inputs.forEach(input => {
                        const name = input.name.replace(/\[(\d+)\]/, `[${idx}]`);
                        input.name = name;
                    });
                });
                console.log('Rows renumbered'); // Debug
            }
        });

        // Image preview (inchangé)
        document.getElementById('images').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('images-preview');
            previewContainer.innerHTML = '';
            Array.from(this.files).forEach((file) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'position-relative';
                    col.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        <small class="d-block text-center text-xs mt-1">${file.name}</small>
                    `;
                    previewContainer.appendChild(col);
                }
                reader.readAsDataURL(file);
            });
        });

        console.log('Script loaded successfully'); // Debug: Script init
        console.log('Resource Types:', resourceTypes); // Debug
        console.log('Fournisseurs:', fournisseurs); // Debug
    </script>
</x-app-layout>