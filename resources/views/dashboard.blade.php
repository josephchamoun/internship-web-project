<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

    </div>
</x-slot>



    <div class="container mx-auto px-4">
        <!-- Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4"><!--hon bel cols-4 mn 8ayir addech badna bi kel row-->
            @foreach ($items as $item)
                <div class="bg-white shadow rounded-lg p-4">
                    <figure>
                        <img src="https://cdn.flyonui.com/fy-assets/components/card/image-9.png" alt="Watch" class="w-full rounded-lg" />
                    </figure>
                    <div class="mt-4">
                        <h5 class="font-semibold text-lg">{{ $item->name }}</h5>
                        <p class="text-gray-600 mt-2">{{ $item->description }}</p>
                        <p class="text-gray-600 mt-2">{{ $item->price }} $</p>
                        <div class="mt-4 flex gap-2">
                        <form action="{{ route('cart.add', $item->id) }}" method="POST">
                             @csrf
                            <button type="submit"class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add to cart</button>
                            <button class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Edit item</button>
                        </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $items->links() }}
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script><!--heda daroure ta ye5od l design-->

</x-app-layout>



