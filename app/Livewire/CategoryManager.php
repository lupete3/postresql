<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $name, $category_id;
    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
    ];

    public function render()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('livewire.category-manager', ['categories' => $categories]);
    }

    public function resetInput()
    {
        $this->name = null;
        $this->category_id = null;
        $this->isEditMode = false;
    }

    public function store()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Catégorie ajoutée avec succès!');
        $this->resetInput();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $this->category_id,
        ]);

        $category = Category::findOrFail($this->category_id);
        $category->update([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Catégorie mise à jour avec succès!');
        $this->resetInput();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Catégorie supprimée avec succès!');
    }
}

