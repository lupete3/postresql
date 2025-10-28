<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Sale;

class SalesManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $saleId;
    public $product_id;
    public $quantity;
    public $price;
    public $search = '';

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ];

    public function render()
    {
        $sales = Sale::with('product')
            ->whereHas('product', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $products = Product::orderBy('name')->get();

        // --- Statistiques globales ---
        $totalSalesAmount = Sale::sum(\DB::raw('quantity * price'));
        $totalQuantitySold = Sale::sum('quantity');
        $todaySales = Sale::whereDate('created_at', now())->sum(\DB::raw('quantity * price'));

        $topProduct = Sale::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->first();

        return view('livewire.sales-manager', compact(
            'sales',
            'products',
            'totalSalesAmount',
            'totalQuantitySold',
            'todaySales',
            'topProduct'
        ));
    }

    public function resetForm()
    {
        $this->reset(['saleId', 'product_id', 'quantity', 'price']);
    }

    public function save()
    {
        $this->validate();

        $product = Product::find($this->product_id);

        // Vérifie le stock disponible
        if ($product->stock < $this->quantity) {
            session()->flash('error', "Stock insuffisant pour le produit {$product->name} !");
            return;
        }

        // Création ou mise à jour de la vente
        $sale = Sale::updateOrCreate(
            ['id' => $this->saleId],
            [
                'product_id' => $this->product_id,
                'quantity' => $this->quantity,
                'price' => $this->price,
            ]
        );

        // Mise à jour du stock
        if ($this->saleId) {
            $oldQuantity = $sale->getOriginal('quantity');
            $product->stock += ($oldQuantity - $this->quantity); // ajustement
        } else {
            $product->stock -= $this->quantity; // nouvelle vente
        }

        $product->save();

        $this->resetForm();
        session()->flash('success', 'Vente enregistrée avec succès.');
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $this->saleId = $sale->id;
        $this->product_id = $sale->product_id;
        $this->quantity = $sale->quantity;
        $this->price = $sale->price;
    }

    public function delete($id)
    {
        $sale = Sale::findOrFail($id);
        $product = $sale->product;
        $product->stock += $sale->quantity; // réajuste le stock supprimé
        $product->save();

        $sale->delete();
        session()->flash('success', 'Vente supprimée et stock réajusté.');
    }

}
