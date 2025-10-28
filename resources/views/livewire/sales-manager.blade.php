<div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total des ventes</h6>
                    <h4 class="fw-bold">{{ number_format($totalSalesAmount, 2, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Articles vendus</h6>
                    <h4 class="fw-bold">{{ number_format($totalQuantitySold) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <h6 class="text-dark-50">Ventes du jour</h6>
                    <h4 class="fw-bold">{{ number_format($todaySales, 2, ',', ' ') }} $</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Produit le plus vendu</h6>
                    <h5 class="fw-bold">
                        @if($topProduct)
                            {{ $topProduct->product->name }} ({{ $topProduct->total_sold }})
                        @else
                            -
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gestion des ventes</h5>

            <div class="d-flex gap-2">
                <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Rechercher un produit...">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#saleModal">
                    <i class="bx bx-plus"></i> Nouvelle Vente
                </button>
            </div>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $index => $sale)
                            <tr>
                                <td>{{ $sales->firstItem() + $index }}</td>
                                <td>{{ $sale->product->name }}</td>
                                <td>{{ $sale->quantity }}</td>
                                <td>{{ number_format($sale->price, 2, ',', ' ') }} $</td>
                                <td>{{ number_format($sale->quantity * $sale->price, 2, ',', ' ') }} $</td>
                                <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <button wire:click="edit({{ $sale->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#saleModal">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $sale->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Aucune vente enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $sales->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Vente -->
    <div wire:ignore.self class="modal fade" id="saleModal" tabindex="-1" aria-labelledby="saleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleModalLabel">{{ $saleId ? 'Modifier la Vente' : 'Nouvelle Vente' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product" class="form-label">Produit</label>
                            <select wire:model.lazy="product_id" class="form-select">
                                <option value="">-- Choisir un produit --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                            @error('product_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantité</label>
                            <input type="number" wire:model="quantity" class="form-control" min="1">
                            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Prix Unitaire ($)</label>
                            <input type="number" step="0.01" wire:model="price" class="form-control">
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="resetForm">Annuler</button>
                        <button type="submit" class="btn btn-primary">{{ $saleId ? 'Mettre à jour' : 'Enregistrer' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
