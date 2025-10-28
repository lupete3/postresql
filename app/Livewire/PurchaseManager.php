<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class PurchaseManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $products;
    public $product_id, $quantity, $price, $purchase_id;
    public $isEditMode = false;

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|integer|min:1',
        'price'      => 'required|numeric|min:0',
    ];

    public function render()
    {
        $this->products = Product::orderBy('name')->get();
        $purchases = Purchase::with('product')->orderBy('id', 'desc')->paginate(10);

        // --- Statistiques globales ---
        $totalPurchaseAmount = Purchase::sum('price');
        $totalQuantityPurchased = Purchase::sum('quantity');
        $todayPurchase = Purchase::whereDate('created_at', now())->sum('price');

        $topProductPurchase = Purchase::selectRaw('product_id, SUM(quantity) as total_bought')
            ->groupBy('product_id')
            ->orderByDesc('total_bought')
            ->with('product')
            ->first();

        return view('livewire.purchase-manager', compact(
            'purchases',
            'totalPurchaseAmount',
            'totalQuantityPurchased',
            'todayPurchase',
            'topProductPurchase'
        ));

    }

    public function resetInput()
    {
        $this->product_id = '';
        $this->quantity = '';
        $this->price = '';
        $this->purchase_id = null;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            // Créer l'achat
            $purchase = Purchase::create([
                'product_id' => $this->product_id,
                'quantity' => $this->quantity,
                'price' => $this->price,
            ]);

            // Augmenter le stock du produit acheté
            $product = Product::find($this->product_id);
            $product->increment('stock', $this->quantity);
        });

        session()->flash('message', 'Achat enregistré et stock mis à jour !');
        $this->resetInput();
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $this->purchase_id = $id;
        $this->product_id = $purchase->product_id;
        $this->quantity = $purchase->quantity;
        $this->price = $purchase->price;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $purchase = Purchase::findOrFail($this->purchase_id);
            $oldQuantity = $purchase->quantity;

            // Mettre à jour la fiche achat
            $purchase->update([
                'product_id' => $this->product_id,
                'quantity' => $this->quantity,
                'price' => $this->price,
            ]);

            // Ajuster le stock en fonction de la différence
            $difference = $this->quantity - $oldQuantity;
            $purchase->product->increment('stock', $difference);
        });

        session()->flash('message', 'Achat modifié et stock ajusté !');
        $this->resetInput();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $purchase = Purchase::findOrFail($id);

            // Diminuer le stock du produit si l'achat est supprimé
            $purchase->product->decrement('stock', $purchase->quantity);

            $purchase->delete();
        });

        session()->flash('message', 'Achat supprimé et stock ajusté !');
    }
}

