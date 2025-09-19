<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\{Tag, User, Note};
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination;

    public $notes;

    public ?array $sharers;

    #[Url]
    public ?string $search = '';

    #[Url(history: true)]
    public ?string $author = '';

    public ?array $selectedTagsId = [];
    // public $

    public $total_notes;

    public function mount()
    {
        $this->total_notes = Note::count();
        $this->sharers     = User::whereHas('notes')->get()->toArray();
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
            })->when($this->search, function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhereHas('user', function ($q) {
                        $q->where('users.name', 'like', "%{$this->search}%");
                    });
            })->when($this->author, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('users.name', '=', e($this->author));
                });
            })
            ->paginate(10);

    }



    public function resetFilters()
    {
        $this->reset('selectedTagsId', 'author', 'search');
    }

    #[Computed]
    public function Tags()
    {
        return Tag::all();
    }

    #[Computed]
    public function Sharers()
    {
        return User::whereHas('notes');
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
                        <p class="text-gray-600 dark:text-gray-400 font-edu">We have <b class="font-playwrite">{{
    $total_notes }}</b> Notes and <b class="font-playwrite">{{ count($this->sharers) }}</b>
                            Sharer</p>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto mt-4 md:mt-0">
                        <button
                            class="flex-1 px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30 transition-colors"
                            wire:click='resetFilters'>
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

                <!-- Sort By -->
                <div class="md:flex md:justify-evenly">
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase mb-3">Sort By</h3>
                        <select
                            class="w-full glass rounded-lg border dark;bg-zinc-950 border-gray-200 dark:border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 dark:text-white">
                            <option class="dark:bg-zinc-950 text-white font-playwrite border-1 border-white ">Date
                                Created (Newest)</option>
                            <option class="dark:bg-zinc-950 text-white font-playwrite border-1 border-white ">Date
                                Created (Oldest)</option>
                            <option class="dark:bg-zinc-950 text-white font-playwrite border-1 border-white ">Last
                                Modified</option>
                            <option class="dark:bg-zinc-950 text-white font-playwrite border-1 border-white ">
                                Alphabetical</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase mb-3">Shared By</h3>
                        <select wire:model.live='author'
                            class="w-full glass rounded-lg border dark;bg-zinc-950 border-gray-200 dark:border-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-gray-900 dark:text-white">
                            @foreach ($this->sharers as $sharer)
                                <option class="dark:bg-zinc-950 text-white font-playwrite border-1 border-white "
                                    value="{{ $sharer['name'] }}">
                                    {{ $sharer['name'] }}
                                </option>
                            @endforeach


                        </select>
                    </div>
                </div>


                <!-- Notes Grid -->
                <div class="">
                    @if($this->Notes)
                        @foreach ($this->Notes as $key => $note)
                            <!-- Note {{ $key + 1 }} -->
                            <div
                                class="glass-intense rounded-xl mb-4 shadow-2xl relativep-4 note-card border border-white/20 p-3">
                                <div class="">
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="font-bold text-gray-900 dark:text-white font-edu">
                                            {!! html_entity_decode($note->title) !!}
                                        </h3>
                                        <button class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                            <i class="far fa-star"></i>
                                        </button>
                                    </div>
                                    <p class="text-gray-500">created by <span
                                            class="font-edu text-green-500">{{ $note->user->name }}</span></p>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-4 font-serif">
                                        {{ $note?->description }}
                                    </p>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div class="flex justify-evenly float-right mt-0 mb-6">
                                                @foreach ($note->tags as $tag)
                                                    <a href=""
                                                        class="bg-primary-500 font-edu rounded-md py-0.5 px-4  flex items-center justify-center text-white text-xs mr-1">
                                                        {{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                       
                    @endif

                </div>

                <!-- Pagination -->

                {{ $this->Notes->onEachSide(1)->links('vendor.pagination.tailwind') }}
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