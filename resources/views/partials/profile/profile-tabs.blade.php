<div class="flex-1">
    <!-- Tabs -->
    <div class="glass-card rounded-2xl p-1 mb-6 flex overflow-x-auto">
        <button @click="activeTab = 'overview'"
            :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'overview' }"
            class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
            Overview
        </button>
        <button @click="activeTab = 'activity'; $wire.set('tab', 'activity')"
            :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'activity' }"
            class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
            Activity
        </button>
        <button @click="activeTab = 'notes';  $wire.set('tab', 'notes')"
            :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'notes' }"
            class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
            My Notes
        </button>
        
        <button @click="activeTab = 'settings';  $wire.set('tab', 'settings')"
            :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'settings' }"
            class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
            Settings
        </button>
    </div>

    <!-- Tab Content -->
    <div x-show="activeTab === 'overview'" class="slide-in">
        <div class="glass-card rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">About Me</h3>
            <p class="text-gray-700 dark:text-gray-300">
                {{ $user->profile->bio }}
            </p>
        </div>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
            <div class="space-y-4">
                <div class="flex">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Created a new note <span
                                class="text-primary-600 dark:text-primary-400">"Q4 Planning"</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                    </div>
                </div>
                <div class="flex">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Commented on <span
                                class="text-primary-600 dark:text-primary-400">"Team Meeting Notes"</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</p>
                    </div>
                </div>
                <div class="flex">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Shared <span
                                class="text-primary-600 dark:text-primary-400">"Project Requirements"</span>
                            with 3 people</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Yesterday</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Storage Usage</h3>
            <div class="mb-2 flex justify-between">
                <span class="text-sm text-gray-700 dark:text-gray-300">4.2 GB of 15 GB used</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">28%</span>
            </div>
            <div class="progress-bar mb-6">
                <div class="progress-fill" style="width: 28%"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-sticky-note text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Notes</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">1.8 GB</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-image text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Images</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2.1 GB</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-file-pdf text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Documents</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">0.3 GB</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mr-3">
                        <i class="fas fa-archive text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">Other</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">0.1 GB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'activity'" class="slide-in">
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Created a new note <span
                                class="text-primary-600 dark:text-primary-400">"Q4 Planning"</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                    </div>
                    <button class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="flex items-start">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Commented on <span
                                class="text-primary-600 dark:text-primary-400">"Team Meeting Notes"</span></p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">"I think we should consider
                            adding a section for action items."</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</p>
                    </div>
                    <button class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="flex items-start">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Shared <span
                                class="text-primary-600 dark:text-primary-400">"Project Requirements"</span>
                            with 3 people</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Yesterday</p>
                    </div>
                    <button class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="flex items-start">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Edited <span
                                class="text-primary-600 dark:text-primary-400">"User Research Findings"</span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 days ago</p>
                    </div>
                    <button class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
                <div class="flex items-start">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                        JD</div>
                    <div class="flex-1">
                        <p class="text-gray-900 dark:text-white">Created a new collection <span
                                class="text-primary-600 dark:text-primary-400">"Design Resources"</span></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">3 days ago</p>
                    </div>
                    <button class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div x-show="activeTab === 'notes'" class="slide-in">
        @foreach ($user->notes as $note)
            <div class="glass-card rounded-2xl p-6 mb-3 z-10">
                <div class="space-y-4">

                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-gray-900 dark:text-white">
                                <a href="" wire:navigate class="hover:text-primary-500 transition-all duration-100 font-sans">{!! html_entity_decode($note->title) !!}</a></p>
                                   
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $note->created_at->diffForHumans() }}
                            </p>
                        </div>
                      
                   
                    </div>
                </div>
            </div>
        @endforeach


    </div>
</div>
