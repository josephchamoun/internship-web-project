<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-pink-400 dark:bg-gray-900">
    <div class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-white bg-gradient-to-r from-blue-600 to-pink-600 border-emerald-500 rounded-md">
        <a href="{{ $backUrl }}" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Go Back</a>
        
        <div class="my-3">
            <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
            <form id="{{ $formId }}" action="{{ $formAction }}" method="POST">
                @csrf
                @method('PUT')
                @foreach ($fields as $field)
                <div class="my-2">
                    <label for="{{ $field['id'] }}" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">{{ $field['label'] }}</label>
                    <input 
                        type="{{ $field['type'] }}" 
                        name="{{ $field['name'] }}" 
                        id="{{ $field['id'] }}" 
                        value="{{ $field['value'] ?? '' }}" 
                        class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900" 
                    >
                </div>
                @endforeach

                <button class="px-4 py-1 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Save</button>
            </form>
        </div>
    </div>
</div>