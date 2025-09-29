<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">

            <h2>Ajouter un Fournisseur</h2>

            <form action="{{ route('fournisseurs.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nom Société</label>
                    <input type="text" name="nom_societe" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Domaine Service</label>
                    <input type="text" name="domaine_service" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Enregistrer</button>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">Annuler</a>
            </form>

        </div>
    </main>
</x-app-layout>
