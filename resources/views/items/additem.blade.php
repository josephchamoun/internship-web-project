<!--
<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    
     
    <div class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-white dark:bg-gray-800 border-emerald-500 rounded-md">
    
       
        <a href="\dashboard" class="px-4 py-2 bg-red-500 rounded-md text-white text-sm sm:text-lg shadow-md">Go Back</a>
        
        <div class="my-3">
          
            <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Add New Item</h1>
            <form action="" method="POST">
            
                
                <div class="my-2">
                    <label for="name" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" id="name">
                </div>

                
                <div class="my-2">
                    <label for="first_name" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Description</label>
                    <input type="text" name="first_name" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" id="first_name">
                </div>

              
                <div class="my-2">
                    <label for="class" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Price</label>
                    <input type="text" name="class" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" id="class">
                </div>

               
                <button class="px-4 py-1 bg-emerald-500 rounded-md text-black text-sm sm:text-lg shadow-md">Save</button>
            </form>
        </div>
    </div>
</div>
-->

<!--ma7al l items store badde zid supplieritem store-->
<x-add 
    title="Add New Item" 
    back-url="/items" 
    form-action="/items/store" 
    :fields="[
        ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'id' => 'name'],
        ['label' => 'Description', 'name' => 'description', 'type' => 'text', 'id' => 'description'],
        ['label' => 'Quantity', 'name' => 'quantity', 'type' => 'text', 'id' => 'quantity'],
        ['label' => 'Price', 'name' => 'price', 'type' => 'text', 'id' => 'price'],
        ['label' => 'Supplier', 'name' => 'itemsupplier', 'type' => 'text', 'id' => 'itemsupplier'],
    ]"
/>
