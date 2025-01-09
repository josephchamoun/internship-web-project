<x-app-layout>

  <!-- Dashboard Container -->
  <div class="p-6 space-y-6">
    <!-- Top Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Card -->
      <div class="bg-gray-800 p-4 rounded-lg flex items-center justify-between">
        <div>
          <p class="text-gray-400 uppercase text-sm">Value</p>
          <h2 class="text-2xl font-bold">$30,000</h2>
          <p class="text-green-400 text-sm">+4.4%</p>
        </div>
        <div>
          <div class="bg-blue-500 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.656 0 3-1.343 3-3s-1.344-3-3-3c-1.656 0-3 1.343-3 3s1.344 3 3 3zM12 12c-2.672 0-8 1.343-8 4v2h16v-2c0-2.657-5.328-4-8-4z"/>
            </svg>
          </div>
        </div>
      </div>
      <!-- Repeat for Users, Orders, Tickets -->
      <div class="bg-gray-800 p-4 rounded-lg flex items-center justify-between">
        <div>
          <p class="text-gray-400 uppercase text-sm">Users</p>
          <h2 class="text-2xl font-bold">50,021</h2>
          <p class="text-green-400 text-sm">+2.6%</p>
        </div>
        <div>
          <div class="bg-blue-500 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17a4 4 0 11-4-4m0 0a4 4 0 11-4-4m4 8h.01M7 13h.01m-6 0a4 4 0 0112 0m-6 4h.01" />
            </svg>
          </div>
        </div>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg flex items-center justify-between">
        <div>
          <p class="text-gray-400 uppercase text-sm">Orders</p>
          <h2 class="text-2xl font-bold">45,021</h2>
          <p class="text-green-400 text-sm">+3.1%</p>
        </div>
        <div>
          <div class="bg-blue-500 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h6M9 10V6m0 4V4m9 8h6M18 18v-6m0 6v2m0-2a4 4 0 004-4m0 4a4 4 0 11-4-4m0 4a4 4 0 11-4-4m0 4a4 4 0 104-4m0 4a4 4 0 100-8m0 8a4 4 0 004-4"/>
            </svg>
          </div>
        </div>
      </div>
      <div class="bg-gray-800 p-4 rounded-lg flex items-center justify-between">
        <div>
          <p class="text-gray-400 uppercase text-sm">Tickets</p>
          <h2 class="text-2xl font-bold">20,516</h2>
          <p class="text-green-400 text-sm">+3.1%</p>
        </div>
        <div>
          <div class="bg-blue-500 p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-6-6 6-6" />
            </svg>
          </div>
        </div>
      </div>
    </div>
    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Bar Chart -->
      <div class="bg-gray-800 p-6 rounded-lg">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold">Bar Chart</h3>
          <div class="flex items-center">
            <span class="text-gray-400 mr-2">Last year</span>
            <button class="w-10 h-5 bg-gray-600 rounded-full flex items-center px-1">
              <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
            </button>
          </div>
        </div>
        <!-- Placeholder for Bar Chart -->
        <div class="h-48 mt-6 bg-gray-700 rounded-lg"></div>
      </div>
      <!-- Doughnut Chart -->
      <div class="bg-gray-800 p-6 rounded-lg">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-bold">Doughnut Chart</h3>
          <div class="flex items-center">
            <button class="w-10 h-5 bg-gray-600 rounded-full flex items-center px-1">
              <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
            </button>
          </div>
        </div>
        <!-- Placeholder for Doughnut Chart -->
        <div class="h-48 mt-6 bg-gray-700 rounded-lg flex items-center justify-center">
          <div class="w-24 h-24 bg-blue-500 rounded-full"></div>
        </div>
      </div>
    </div>
  </div>

</x-app-layout>