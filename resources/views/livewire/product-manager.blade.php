<div>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" wire:model="name" class="form-control" placeholder="Nom du produit">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-3">
            <input type="number" wire:model="price" class="form-control" placeholder="Prix">
            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-2">
            <input type="number" wire:model="stock" class="form-control" placeholder="Stock">
            @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-3">
            <select wire:model="category_id" class="form-select">
                <option value="">-- Choisir une catégorie --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-1">
            @if($isEditMode)
                <button class="btn btn-primary" wire:click="update">✔</button>
            @else
                <button class="btn btn-success" wire:click="store">+</button>
            @endif
        </div>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td>{{ number_format($p->price, 2, ',', ' ') }}</td>
                    <td>{{ $p->stock }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $p->id }})">✎</button>
                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $p->id }})">🗑</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Aucun produit trouvé.</td>
                </tr>

            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">{{ $products->links() }}</td>
            </tr>
        </tfoot>
    </table>
</div>
