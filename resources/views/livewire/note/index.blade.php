<?php

use App\Models\Note;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Tag;

new class extends Component {
    use WithPagination;

    public $notes;

    public ?array $selectedTagsId = [];
    // public $

    public $total_notes;

    public function mount()
    {
        $this->total_notes = Note::count();
        // dd($this->Notes());
    }

    #[Computed]
    public function Notes()
    {
        return Note::latest()
            ->with([
                'user',
                'tags',
                'collaborations.collaborator',
            ])->when($this->selectedTagsId, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->whereIn('tags.id', $this->selectedTagsId);
                });
            })
            ->paginate(9);
    }

    public function resetFilters()
    {
        $this->reset('selectedTagsId');
    }

    #[Computed]
    public function Tags()
    {
        return Tag::all();
    }


}; ?>
<div x-data="{
        sidebarOpen: false,
        sidebarOpen: window.innerWidth >= 768 
        }">
    <div class="flex flex-col md:flex-row h-[calc(100vh-73px)]">
        <!-- Sidebar Filters -->
        @include('partials.notes.filters')
        <!-- Main Content -->
        <div class="flex-1 custom-scrollbar overflow-y-auto">
            <div class="p-4 md:p-6">
                <!-- Content Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">All Notes</h1>
                        <p class="text-gray-600 dark:text-gray-400">We have {{ $total_notes }} Notes</p>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto mt-4 md:mt-0">
                        <button
                            class="flex-1 px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30 transition-colors" wire:click='resetFilters'>
                            <i class="fa fa-arrow-rotate-left"></i> 
                            <span wire:loading.remove>Reset</span>
                            <span wire:loading wire:target='resetFilters'>Reseting....</span>
                        </button>
                        <button
                            class="mt-4 md:mt-0 px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors flex items-center">
                            <i class="fas fa-plus mr-2"></i> New Note
                        </button>
                    </div>

                </div>

                <!-- Filter Tabs -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg font-medium">
                        All Notes
                    </button>
                    <button
                        class="px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                        Recent
                    </button>
                    <button
                        class="px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                        Favorites
                    </button>
                    <button
                        class="px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                        Shared
                    </button>
                    <button
                        class="px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                        Archived
                    </button>
                </div>

                <!-- Notes Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2  gap-4 md:gap-6">

                    @foreach ($this->Notes as $key => $note)
                        <!-- Note {{ $key + 1 }} -->
                        <div class="glass-intense rounded-xl p-4 note-card border border-white/20">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-bold text-gray-900 dark:text-white">
                                    {!!  html_entity_decode($note->title) !!}
                                </h3>
                                <button class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                    <i class="far fa-star"></i>
                                </button>
                            </div>
                            {{-- <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">Initial planning for the Q4
                                product
                                launch with timeline and resource allocation...</p> --}}
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div
                                        class="w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center text-white text-xs mr-1">
                                        JD</div>
                                    <div
                                        class="w-6 h-6 rounded-full bg-primary-400 flex items-center justify-center text-white text-xs mr-1">
                                        AS</div>
                                    <div
                                        class="w-6 h-6 rounded-full bg-primary-300 flex items-center justify-center text-white text-xs">
                                        +2</div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</span>
                            </div>
                        </div>
                    @endforeach


                </div>

                <!-- Pagination -->
                <div class="flex justify-center items-center mt-8">
                    <nav class="flex items-center space-x-2">
                        <button
                            class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 bg-primary-600 text-white rounded-lg font-medium">1</button>
                        <button
                            class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">2</button>
                        <button
                            class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">3</button>
                        <span class="px-2 text-gray-500">...</span>
                        <button
                            class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">8</button>
                        <button
                            class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Button -->
    <div x-show="!sidebarOpen" class="fixed bottom-14 right-4 md:hidden z-30">
        <button @click="sidebarOpen = !sidebarOpen"
            class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center shadow-lg">
            <i class="fas fa-filter text-xl"></i>
        </button>
    </div>
</div>