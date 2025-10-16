<x-app-layout>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <!-- Add AI Loading Indicator -->
                                <div id="ai-loading" class="alert alert-info" style="display: none;">
                                    <i class="fas fa-spinner fa-spin me-2"></i> AI is generating content...
                                </div>
                                    </div>
                                </div>

                                <!-- Title & Category -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="form-control-label">Title</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                       id="title" name="title" value="{{ old('title') }}" required>
                                                
                                            </div>
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
                                                    <option value="{{ $category->id }}" {{ old('categorie_id') == $category->id ? 'selected' : '' }}>
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

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <div class="input-group">
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                        <button type="button" class="btn btn-outline-secondary" id="generate-description-btn">
                                            <i class="fas fa-magic me-1"></i> AI Generate
                                        </button>
                                    </div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dates -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date" class="form-control-label">Start Date</label>
                                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="end_date" class="form-control-label">End Date</label>
                                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="registration_deadline" class="form-control-label">Registration Deadline</label>
                                            <input type="datetime-local" class="form-control @error('registration_deadline') is-invalid @enderror" 
                                                   id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}" required>
                                            @error('registration_deadline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Location & Capacity & Price -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="location" class="form-control-label">Location</label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                                   id="location" name="location" value="{{ old('location') }}" required>
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="capacity_max" class="form-control-label">Max Capacity</label>
                                            <input type="number" class="form-control @error('capacity_max') is-invalid @enderror" 
                                                   id="capacity_max" name="capacity_max" value="{{ old('capacity_max') }}" min="1" required>
                                            @error('capacity_max')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="price" class="form-control-label">Price ($)</label>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', 0) }}" min="0" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Visibility -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-control-label">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                                <option value="">Select Status</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
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
                                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_public">
                                                    Public Event
                                                </label>
                                            </div>
                                        </div>
                                    </div>
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
                                                   id="images" name="images[]" multiple accept="image/*">
                                            @error('images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">You can select multiple images (JPEG, PNG, JPG, GIF, WebP, max: 2MB each)</small>
                                        </div>
                                        
                                        <div id="images-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
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
                                                    <textarea class="form-control bg-light" id="success-prediction" rows="4" readonly 
                                                              placeholder="Fill in event details and click 'Analyze Success' to get AI-powered professional insights..."></textarea>
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
                                    <button type="submit" class="btn btn-dark">Create Event</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // AI Generation Functions
        document.getElementById('generate-description-btn').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const categoryId = document.getElementById('categorie_id').value;
            const loading = document.getElementById('ai-loading');
            
            if (!title || !categoryId) {
                alert('Please enter a title and select a category first');
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
                    category_id: categoryId
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                if (data.success) {
                    document.getElementById('description').value = data.description;
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
                    if (event.price) {
                        document.getElementById('price').value = event.price;
                    }
                    if (event.status) {
                        document.getElementById('status').value = event.status;
                    }
                    if (event.is_public !== undefined) {
                        document.getElementById('is_public').checked = event.is_public;
                    }
                    
                    // Fill date fields
                    if (event.start_date) {
                        document.getElementById('start_date').value = formatDateForInput(event.start_date);
                    }
                    if (event.end_date) {
                        document.getElementById('end_date').value = formatDateForInput(event.end_date);
                    }
                    if (event.registration_deadline) {
                        document.getElementById('registration_deadline').value = formatDateForInput(event.registration_deadline);
                    }
                    
                    // Fill success prediction
                    if (event.success_prediction) {
                        document.getElementById('success-prediction').value = event.success_prediction;
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

        // Event Success Prediction
        document.getElementById('predict-success-btn').addEventListener('click', function() {
            const title = document.getElementById('title').value;
            const categoryId = document.getElementById('categorie_id').value;
            const capacity = document.getElementById('capacity_max').value;
            const loading = document.getElementById('ai-loading');
            
            if (!title || !categoryId || !capacity) {
                alert('Please enter title, category, and capacity first');
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
                    capacity: capacity
                })
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                if (data.success) {
                    document.getElementById('success-prediction').value = data.prediction;
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
            const date = new Date(dateString);
            return date.toISOString().slice(0, 16);
        }

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
                const row = document.createElement('div');
                row.className = 'resource-row mb-3';
                row.dataset.index = index;
                row.innerHTML = `
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Resource Name</label>
                                <input type="text" class="form-control" name="resources[${index}][nom]" value="${resource.nom || resource.type}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label">Resource Type</label>
                                <select class="form-control" name="resources[${index}][type]">
                                    <option value="">Select Type</option>
                                    @foreach($resourceTypes as $type)
                                        <option value="{{ $type }}" ${resource.type === '{{ $type }}' ? 'selected' : ''}>{{ $type }}</option>
                                    @endforeach
                                </select>
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
                                <select class="form-control" name="resources[${index}][fournisseur_id]">
                                    <option value="">Select Supplier</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom_societe }}</option>
                                    @endforeach
                                </select>
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
                                <select class="form-control" name="resources[${index}][type]">
                                    <option value="">Select Type</option>
                                    @foreach($resourceTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
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
                                <select class="form-control" name="resources[${index}][fournisseur_id]">
                                    <option value="">Select Supplier</option>
                                    @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom_societe }}</option>
                                    @endforeach
                                </select>
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
    </script>
</x-app-layout>