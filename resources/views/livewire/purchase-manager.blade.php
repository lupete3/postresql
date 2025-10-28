<div>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total des achats</h6>
                    <h4 class="fw-bold">{{ number_format($totalPurchaseAmount, 2, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Quantité achetée</h6>
                    <h4 class="fw-bold">{{ number_format($totalQuantityPurchased) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <h6 class="text-dark-50">Achats du jour</h6>
                    <h4 class="fw-bold">{{ number_format($todayPurchase, 2, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Produit le plus acheté</h6>
                    <h5 class="fw-bold">
                        @if($topProductPurchase)
                            {{ $topProductPurchase->product->name }} ({{ $topProductPurchase->total_bought }})
                        @else
                            -
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <select wire:model="product_id" class="form-select">
                <option value="">-- Choisir un produit --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
            @error('product_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-3">
            <input type="number" wire:model="quantity" class="form-control" placeholder="Quantité">
            @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-3">
            <input type="number" step="0.01" wire:model="price" class="form-control" placeholder="Prix total">
            @error('price') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-2">
            @if($isEditMode)
                <button class="btn btn-primary w-100" wire:click="update">Mettre à jour</button>
            @else
                <button class="btn btn-success w-100" wire:click="store">Ajouter</button>
            @endif
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Total</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->product->name ?? '-' }}</td>
                    <td>{{ $p->quantity }}</td>
                    <td>{{ number_format($p->price, 2, ',', ' ') }}</td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $p->id }})">✎</button>
                        <button class="btn btn-danger btn-sm" wire:click="delete({{ $p->id }})">🗑</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">{{ $purchases->links() }}</td>
            </tr>
        </tfoot>
    </table>
</div>
