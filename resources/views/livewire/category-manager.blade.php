<div>
    @if(session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="mb-3">
        <input type="text" class="form-control" placeholder="Nom de la catégorie" wire:model="name">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3">
        @if($isEditMode)
            <button class="btn btn-primary" wire:click="update">Mettre à jour</button>
            <button class="btn btn-secondary" wire:click="resetInput">Annuler</button>
        @else
            <button class="btn btn-success" wire:click="store">Ajouter</button>
        @endif
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="edit({{ $category->id }})">Modifier</button>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $category->id }})">Supprimer</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">{{ $categories->links() }}</td>
            </tr>
        </tfoot>
    </table>
</div>

