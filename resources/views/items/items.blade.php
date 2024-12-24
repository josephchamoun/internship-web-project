<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center space-x-2">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items Supplies') }}
        </h2>
        <x-add-button url="/additem">
            Add Supply
        </x-add-button>

    </div>
    </x-slot>

    <div class="container mx-auto px-4">
        <!-- Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($itemsuppliers as $itemsupplier)
                <div class="bg-white shadow rounded-lg p-4">
                    
                    <div class="mt-4">
                        <h5 class="font-semibold text-lg">Supplier: {{ $itemsupplier->supplier->name }}</h5>
                        <h5 class="font-semibold text-lg">Item: {{ $itemsupplier->item->name }}</h5>
                        <h5 class="font-semibold text-lg">Quantity: {{ $itemsupplier->quantity }}</h5>
                        <h5 class="font-semibold text-lg">Price: {{ $itemsupplier->buyprice }} $</h5>
                        <h5 class="font-semibold text-lg">{{ $itemsupplier->updated_at }}</h5>
                       
                        <div class="mt-4 flex gap-2">
                         
                        <x-add-button url="{{ route('itemsupply.edit', $itemsupplier->id) }}">
                            Edit Supply
                        </x-add-button>

                  
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $itemsuppliers->links() }}
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script><!--heda daroure-->

</x-app-layout>