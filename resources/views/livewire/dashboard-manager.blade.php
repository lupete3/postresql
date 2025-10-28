<div>
  <div class="row g-3 mb-4">

      <div class="col-md-3">
          <div class="card bg-primary text-white">
              <div class="card-body">
                  <h6>Total produits</h6>
                  <h4>{{ $totalProducts }}</h4>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card bg-success text-white">
              <div class="card-body">
                  <h6>Valeur totale stock</h6>
                  <h4>{{ number_format($totalStockValue, 2, ',', ' ') }} $</h4>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card bg-warning text-dark">
              <div class="card-body">
                  <h6>Total achats</h6>
                  <h4>{{ number_format($totalPurchaseAmount, 2, ',', ' ') }} $</h4>
              </div>
          </div>
      </div>

      <div class="col-md-3">
          <div class="card bg-info text-white">
              <div class="card-body">
                  <h6>Total ventes</h6>
                  <h4>{{ number_format($totalSalesAmount, 2, ',', ' ') }} $</h4>
              </div>
          </div>
      </div>
  </div>

  <div class="row g-3">
      <div class="col-md-6">
          <div class="card shadow-sm">
              <div class="card-body">
                  <h6>Produit le plus vendu</h6>
                  <p>{{ $topSoldProduct ? $topSoldProduct->product->name . ' (' . $topSoldProduct->total_sold . ')' : '-' }}</p>
              </div>
          </div>
      </div>

      <div class="col-md-6">
          <div class="card shadow-sm">
              <div class="card-body">
                  <h6>Produit le plus acheté</h6>
                  <p>{{ $topPurchasedProduct ? $topPurchasedProduct->product->name . ' (' . $topPurchasedProduct->total_bought . ')' : '-' }}</p>
              </div>
          </div>
      </div>
  </div>

</div>
