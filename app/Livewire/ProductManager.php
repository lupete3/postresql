<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $categories;
    public $name, $price, $stock, $category_id, $product_id;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
    ];

    public function render()
    {
        $this->categories = Category::orderBy('name')->get();
        $products = Product::with('category')->orderBy('id', 'desc')->paginate(10);

        return view('livewire.product-manager', ['products' => $products]);
    }

    public function resetInput()
    {
        $this->name = '';
        $this->price = '';
        $this->stock = '';
        $this->category_id = '';
        $this->product_id = null;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        Product::create([
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ]);

        session()->flash('message', 'Produit ajouté avec succès !');
        $this->resetInput();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->category_id = $product->category_id;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);
        $product->update([
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
        ]);

        session()->flash('message', 'Produit mis à jour avec succès !');
        $this->resetInput();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Produit supprimé avec succès !');
    }
}

