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
                        <a href="{{ route('product.index') }}" class="float-end bg-red-500 color-white-300">Back</a>
                    @endif
                    <form action="{{ route('product.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                          <label for="name" class="form-label">Product</label>
                          <input type="text" class="form-control" name="name" id="name" placeholder="Product...">
                        </div>
                        <div class="mb-3">
                          <label for="price" class="form-label">Price</label>
                          <input type="text" class="form-control" name="price" id="price" placeholder="Price...">
                        </div>
                        <button type="submit" class="bg-red-500 color-white-300">Create</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
