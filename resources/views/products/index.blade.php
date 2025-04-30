@extends('products.layout.web')
@section('content')
    <div class="row">
      <h2>You will see Product 1 and other great products</h2>
        @forelse($products as $product)
        <div class="col-sm-6 mb-2">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Product: {{$product->name}}</h5>
              <p class="card-text">USD: {{$product->price}}</p>
              <p class="card-text">EURO: {{$product->price_eur}}</p>
              <a href="#" class="btn btn-primary">Buy</a>
            </div>
          </div>
        </div>
        @empty
        <h3>No Record Found</h3>
        @endforelse
    </div>
@endsection