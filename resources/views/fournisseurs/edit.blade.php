<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">

            <h2>Modifier le Fournisseur</h2>

            <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nom Société</label>
                    <input type="text" name="nom_societe" class="form-control" value="{{ $fournisseur->nom_societe }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Domaine Service</label>
                    <input type="text" name="domaine_service" class="form-control" value="{{ $fournisseur->domaine_service }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="{{ $fournisseur->adresse }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $fournisseur->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ $fournisseur->telephone }}" required>
                </div>

                <button type="submit" class="btn btn-success">Mettre à jour</button>
                <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">Annuler</a>
            </form>

        </div>
    </main>
</x-app-layout>
