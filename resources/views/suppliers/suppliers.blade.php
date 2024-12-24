<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center space-x-2">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suppliers') }}
        </h2>
        <x-add-button>
            Add Suppliers
        </x-add-button>
    </div>
    </x-slot>

    <div class="container mx-auto px-4">
        <!-- Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($suppliers as $supplier)
                <div class="bg-white shadow rounded-lg p-4">
                    <figure>
                        <img src="https://cdn.flyonui.com/fy-assets/components/card/image-9.png" alt="Watch" class="w-full rounded-lg" />
                    </figure>
                    <div class="mt-4">
                        <h5 class="font-semibold text-lg">{{ $supplier->name }}</h5>
                        <p class="text-gray-600 mt-2">Stay connected, motivated, and healthy with the latest Apple Watch.</p>
                        <div class="mt-4 flex gap-2">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View supplier</button>
                            <button class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Add to cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $suppliers->links() }}
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script><!--heda daroure-->

</x-app-layout>