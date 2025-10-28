<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;

class DashboardManager extends Component
{
    public function render()
    {
        // --- Totaux ---
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(\DB::raw('stock * price'));
        $totalPurchaseAmount = Purchase::sum('price');
        $totalSalesAmount = Sale::sum('price');

        // --- Top produits ---
        $topSoldProduct = Sale::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->first();

        $topPurchasedProduct = Purchase::selectRaw('product_id, SUM(quantity) as total_bought')
            ->groupBy('product_id')
            ->orderByDesc('total_bought')
            ->with('product')
            ->first();

        return view('livewire.dashboard-manager', compact(
            'totalProducts',
            'totalStockValue',
            'totalPurchaseAmount',
            'totalSalesAmount',
            'topSoldProduct',
            'topPurchasedProduct'
        ));
    }
}

