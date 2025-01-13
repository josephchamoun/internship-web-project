<x-app-layout>


    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Dashboard - Stats</h1>

        <!-- Overall Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <h2 class="text-xl font-semibold mb-2">Total Orders</h2>
                <p class="text-3xl font-bold">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                <h2 class="text-xl font-semibold mb-2">Total Revenue</h2>
                <p class="text-3xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-4">Best-Selling Items</h2>
<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <table class="table-auto w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                <th class="px-6 py-3 font-medium text-gray-600">#</th>
                <th class="px-6 py-3 font-medium text-gray-600">Item</th>
                <th class="px-6 py-3 font-medium text-gray-600">Sold</th>
                <th class="px-6 py-3 font-medium text-gray-600">Progress</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bestSellingItems as $index => $item)
            <tr class="hover:bg-gray-50 border-b">
                <!-- Rank -->
                <td class="px-6 py-3 font-medium">{{ $index + 1 }}</td>

                <!-- Item Name -->
                <td class="px-6 py-3 font-medium truncate">{{ $item->name }}</td>

                <!-- Total Sold -->
                <td class="px-6 py-3">{{ $item->total_sold }}</td>

                <!-- Progress Bar -->
                <td class="px-6 py-3">
                    <div class="bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ ($item->total_sold / $bestSellingItems->max('total_sold')) * 100 }}%;"></div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



        <!-- Monthly Revenue Chart -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-10">
            <h2 class="text-xl font-bold mb-4">Monthly Revenue</h2>
            <canvas id="revenueChart" class="h-96"></canvas>
        </div>
    </div>

    <script>
        // Render Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!}, // Month names
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($monthlyRevenue) !!}, // Revenue per month
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
