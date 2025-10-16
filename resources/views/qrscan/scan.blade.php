<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-xs border">
                    <div class="card-header bg-gradient-dark">
                        <h5 class="text-white mb-0">Scanner un QR Code</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="mb-3">Scanner avec la caméra</h6>
                                        <div id="reader" class="border mb-3" style="width: 100%; min-height: 300px;"></div>
                                        <p class="text-muted small">Positionnez le QR code face à la caméra</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">Saisie manuelle</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Code du ticket</label>
                                            <div class="input-group">
                                                <input type="text" id="ticket-code" class="form-control" placeholder="Ex: ABCD1234">
                                                <button class="btn btn-dark" id="check-ticket" type="button">Vérifier</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Résultat du scan -->
                                <div id="scan-result" class="card d-none mt-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">Résultat du scan</h6>
                                        <div id="registration-info">
                                            <!-- Les informations d'inscription seront affichées ici -->
                                        </div>
                                        <div class="mt-3 text-center">
                                            <button id="mark-attended-btn" class="btn btn-success d-none">
                                                <i class="fas fa-check-circle me-2"></i>Marquer comme présent
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML5 QR Code Scanner Script -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuration du scanner QR Code
            const html5QrCode = new Html5Qrcode("reader");
            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Arrêter le scan après avoir détecté un QR code
                html5QrCode.stop();
                processQrCode(decodedText);
            };
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            // Démarrer le scanner
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
            
            // Gestion du bouton de vérification manuelle
            document.getElementById('check-ticket').addEventListener('click', function() {
                const ticketCode = document.getElementById('ticket-code').value.trim();
                if (ticketCode) {
                    try {
                        // Créer un objet JSON similaire à celui encodé dans le QR code
                        const qrData = JSON.stringify({
                            ticket_code: ticketCode
                        });
                        processQrCode(qrData);
                    } catch (error) {
                        showError("Format de code invalide");
                    }
                } else {
                    showError("Veuillez entrer un code de ticket");
                }
            });
            
            // Fonction pour traiter les données du QR code
            function processQrCode(qrData) {
                fetch('{{ route("qrscan.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ qr_data: qrData })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRegistrationInfo(data);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showError("Erreur lors du traitement du QR code");
                    console.error(error);
                });
            }
            
            // Afficher les informations d'inscription
            function displayRegistrationInfo(data) {
                const registration = data.registration;
                const infoDiv = document.getElementById('registration-info');
                const resultCard = document.getElementById('scan-result');
                const markAttendedBtn = document.getElementById('mark-attended-btn');
                
                let statusClass = 'bg-info';
                if (registration.status === 'confirmed') statusClass = 'bg-success';
                if (registration.status === 'canceled') statusClass = 'bg-danger';
                if (registration.status === 'pending') statusClass = 'bg-warning';
                if (registration.status === 'attended') statusClass = 'bg-primary';
                
                let html = `
                    <div class="alert alert-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>${data.message}
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Événement:</strong>
                        <span>${registration.event.title}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Participant:</strong>
                        <span>${registration.user.name}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Code:</strong>
                        <span class="badge bg-dark">${registration.ticket_code}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Statut:</strong>
                        <span class="badge ${statusClass}">${registration.status}</span>
                    </div>
                `;
                
                infoDiv.innerHTML = html;
                resultCard.classList.remove('d-none');
                
                // Afficher le bouton "Marquer comme présent" seulement si le statut n'est pas déjà "attended"
                if (registration.status !== 'attended') {
                    markAttendedBtn.classList.remove('d-none');
                    markAttendedBtn.setAttribute('data-registration-id', registration.id);
                    
                    // Ajouter l'événement au bouton
                    markAttendedBtn.addEventListener('click', function() {
                        markAsAttended(registration.id);
                    });
                } else {
                    markAttendedBtn.classList.add('d-none');
                }
            }
            
            // Marquer un participant comme présent
            function markAsAttended(registrationId) {
                fetch('{{ route("qrscan.mark-attended") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ registration_id: registrationId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour l'affichage
                        displayRegistrationInfo(data);
                        // Afficher un message de succès
                        alert("Participant marqué comme présent avec succès!");
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showError("Erreur lors de la mise à jour du statut");
                    console.error(error);
                });
            }
            
            // Afficher une erreur
            function showError(message) {
                const infoDiv = document.getElementById('registration-info');
                const resultCard = document.getElementById('scan-result');
                const markAttendedBtn = document.getElementById('mark-attended-btn');
                
                infoDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>${message}
                    </div>
                `;
                
                resultCard.classList.remove('d-none');
                markAttendedBtn.classList.add('d-none');
            }
        });
    </script>
</x-app-layout>
