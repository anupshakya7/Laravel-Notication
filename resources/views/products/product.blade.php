<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('product.create') }}" class="float-end bg-red-500 color-white-300">Add Product</a>
                    @endif
                    <h2>You will see Product 1 and other great products</h2>
                    <table class="table-auto table-dark table-striped">
                        <thead>
                          <tr>
                            <th scope="col">S.No.</th>
                            <th scope="col">Name</th>
                            <th scope="col">Usd Price</th>
                            <th scope="col">Euro Price</th>
                          </tr>
                        </thead>
                        <tbody>
                        @forelse($products as $key=>$product)
                          <tr>
                            <th scope="row">{{$key+1}}</th>
                            <td>{{$product->name}}</td>
                            <td>{{$product->price}}</td>
                            <td>{{$product->price_eur}}</td>
                          </tr>
                        @empty
                            <h3>No Record Found</h3>
                        @endforelse
                        </tbody>
                      </table>
                      {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
