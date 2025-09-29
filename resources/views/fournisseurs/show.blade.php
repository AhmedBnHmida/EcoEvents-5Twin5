<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-app.navbar />
        <div class="container-fluid py-4 px-5">

            <h2>Détails du Fournisseur</h2>

            <div class="card p-4 mb-3">
                <p><strong>Nom Société:</strong> {{ $fournisseur->nom_societe }}</p>
                <p><strong>Domaine Service:</strong> {{ $fournisseur->domaine_service }}</p>
                <p><strong>Adresse:</strong> {{ $fournisseur->adresse }}</p>
                <p><strong>Email:</strong> {{ $fournisseur->email }}</p>
                <p><strong>Téléphone:</strong> {{ $fournisseur->telephone }}</p>
            </div>

            <a href="{{ route('fournisseurs.index') }}" class="btn btn-primary">Retour à la liste</a>
            <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-warning">Modifier</a>
            <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" onclick="return confirm('Supprimer ce fournisseur ?')">Supprimer</button>
            </form>

        </div>
    </main>
</x-app-layout>
