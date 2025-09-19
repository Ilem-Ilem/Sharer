<div x-show="sidebarOpen" @click.away="if (window.innerWidth < 768) sidebarOpen = false"
class="w-full md:w-64 glass-intense border-r border-gray-200 dark:border-gray-800 md:block custom-scrollbar overflow-y-auto mt-22 md:mt-0 glass-card md:sticky top-20"
:class="{ 'fixed inset-0 z-40 bg-white dark:bg-gray-900 md:relative': window.innerWidth < 768, 'hidden': !sidebarOpen }">
<div class="p-4 glass-card">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Filters</h2>
        <button @click="sidebarOpen = false" class="md:hidden p-2 rounded-lg bg-gray-100 dark:bg-zinc-800">
            <i class="fas fa-times text-gray-700 dark:text-gray-300"></i>
        </button>
    </div>

    <!-- Search Box -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" placeholder="Search notes..."
                class="w-full pl-10 pr-4 py-2 glass rounded-lg border border-gray-200 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 dark:text-white" wire:model.live='search'>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-500"></i>
            </div>
        </div>
    </div>


    <!-- Filter by Tags -->
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase mb-3">Tags</h3>
        <div class="space-y-2">
            @foreach ($this->Tags as $tag)
                <label class="flex items-center">
                <input type="checkbox" value="{{ $tag->id }}" class="rounded text-primary-600 focus:ring-primary-500" wire:model.live='selectedTagsId'>
                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $tag->name }}</span>
            </label>
            @endforeach
            
           
        </div>
    </div>


   
</div>
</div>
