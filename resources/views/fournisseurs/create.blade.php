<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-3">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <!-- FULL WIDTH BLUE TITLE BAR -->
                        <div class="row g-0">
                            <div class="col-12">
                                <div style="background: #1e293b; color: #fff; width: 100%; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                    <h4 class="mb-0 py-2 px-4 fw-semibold" style="letter-spacing:1px;color: #fff;">Ajouter un Fournisseur</h4>
                                </div>
                            </div>
                        </div>
                        <!-- END TITLE BAR -->
                        <div class="card-body px-4 py-4">
                            <form id="fournisseurForm" action="{{ route('fournisseurs.store') }}" method="POST" autocomplete="off" novalidate>
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label fw-medium" for="nom_societe">Nom Société</label>
                                        <input type="text" name="nom_societe" id="nom_societe"
                                               class="form-control"
                                               maxlength="255" required>
                                        <div class="form-text text-danger" id="nom_societe_msg">Obligatoire, texte, max 255 caractères.</div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label fw-medium" for="domaine_service">Domaine Service</label>
                                        <select name="domaine_service" id="domaine_service" class="form-select" required>
                                            <option value="" selected disabled>Choisir...</option>
                                            @foreach([
                                                'Décoration', 'Nourriture', 'Matériel', 'Transport',
                                                'Électronique', 'Hygiène', 'Communication', 'Papeterie',
                                                'Énergie', 'Nettoyage', 'Sécurité', 'Autre'
                                            ] as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text text-danger" id="domaine_service_msg">Obligatoire, sélectionnez une option.</div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label fw-medium" for="adresse">Adresse</label>
                                        <input type="text" name="adresse" id="adresse"
                                               class="form-control"
                                               maxlength="255" required>
                                        <div class="form-text text-danger" id="adresse_msg">Obligatoire, texte, max 255 caractères.</div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label fw-medium" for="email">Email</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control"
                                               required>
                                        <div class="form-text text-danger" id="email_msg">Obligatoire, format email, unique.</div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label class="form-label fw-medium" for="telephone">Téléphone</label>
                                        <input type="text" name="telephone" id="telephone"
                                               class="form-control"
                                               minlength="8" maxlength="9" required pattern="^[0-9]{8,9}$">
                                        <div class="form-text text-danger" id="telephone_msg">Obligatoire, chiffres uniquement, 8 à 9 caractères.</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2 pt-4">
                                    <button type="submit" class="btn btn-dark px-4" id="submitBtn" disabled>Enregistrer</button>
                                    <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- JS Validation dynamique -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('fournisseurForm');
                const submitBtn = document.getElementById('submitBtn');
                const msg = {
                    nom_societe: document.getElementById('nom_societe_msg'),
                    domaine_service: document.getElementById('domaine_service_msg'),
                    adresse: document.getElementById('adresse_msg'),
                    email: document.getElementById('email_msg'),
                    telephone: document.getElementById('telephone_msg'),
                };
                const inputs = {
                    nom_societe: document.getElementById('nom_societe'),
                    domaine_service: document.getElementById('domaine_service'),
                    adresse: document.getElementById('adresse'),
                    email: document.getElementById('email'),
                    telephone: document.getElementById('telephone'),
                };
                function validateNomSociete() {
                    const value = inputs.nom_societe.value.trim();
                    return value !== '' && value.length <= 255;
                }
                function validateDomaineService() {
                    return inputs.domaine_service.value !== '';
                }
                function validateAdresse() {
                    const value = inputs.adresse.value.trim();
                    return value !== '' && value.length <= 255;
                }
                function validateEmail() {
                    const value = inputs.email.value.trim();
                    return value !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                }
                function validateTelephone() {
                    const value = inputs.telephone.value.trim();
                    return /^[0-9]{8,9}$/.test(value);
                }
                function showOrHide(field, valid) {
                    if (valid) {
                        msg[field].style.display = 'none';
                        inputs[field].classList.remove('is-invalid');
                    } else {
                        msg[field].style.display = 'block';
                        inputs[field].classList.add('is-invalid');
                    }
                }
                function validateForm() {
                    const vNom = validateNomSociete();
                    const vDom = validateDomaineService();
                    const vAdr = validateAdresse();
                    const vEmail = validateEmail();
                    const vTel = validateTelephone();

                    showOrHide('nom_societe', vNom);
                    showOrHide('domaine_service', vDom);
                    showOrHide('adresse', vAdr);
                    showOrHide('email', vEmail);
                    showOrHide('telephone', vTel);

                    submitBtn.disabled = !(vNom && vDom && vAdr && vEmail && vTel);
                }
                Object.values(inputs).forEach(input => {
                    input.addEventListener('input', validateForm);
                    input.addEventListener('change', validateForm);
                });
                validateForm();
            });
        </script>
    </main>
</x-app-layout>