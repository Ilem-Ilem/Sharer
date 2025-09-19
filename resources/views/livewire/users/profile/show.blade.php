<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Url;

new class extends Component {
    public $user;

    #[Url(keep:true)]
    public $tab ='overview';

    public function mount()
    {
        $this->user = User::with(['profile', 'notes', 'collaborations'])
            ->where('id', '=', auth()->user()->id)
            ->firstOrFail();
    }

    
}; ?>

<div>
    <!-- Profile Header -->
    <div class="glass-card rounded-2xl p-6 mb-6 profile-header fade-in">
        <div class="flex flex-col md:flex-row items-center md:items-start">
            <div class="relative mb-4 md:mb-0 md:mr-6">
                <div
                    class="w-24 h-24 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    JD
                </div>
               
            </div>

            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-gray-400 font-satisfy">{{ '@' . $user->profile->username }}</p>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $user->profile->occupation ?? 'I am a Sharer' }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2"><i
                        class="fas fa-map-marker-alt mr-2"></i>{{ html_entity_decode($user->profile->location) ?? 'N/A' }}
                </p>
            </div>

            <div class="mt-4 md:mt-0">
                <a href="{{ route('profile.edit') }}"
                    class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-colors shadow-lg">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-2xl p-4 stat-card fade-in">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-sticky-note text-primary-600 dark:text-primary-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($user->notes) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Notes</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-4 stat-card fade-in">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($user->collaborations) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Collaborations</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-4 stat-card fade-in">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-share-alt text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->profile->followers_count }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Followers</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-4 stat-card fade-in">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mr-4">
                    <i class="fas fa-star text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">36</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Favorites</p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 relative" x-data="{ activeTab: '{{ $tab ?? 'overview' }}' }">
        <!-- Main Content -->
      @include('partials.profile.profile-tabs')

        <!-- Sidebar -->
        <div class="w-full lg:w-80 flex-none space-y-6 sticky top-6">
            <!-- Account Details -->
            <div class="glass-card rounded-2xl p-6 slide-in">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account Details</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                        <p class="text-gray-900 dark:text-white">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Member since</p>
                        <p class="text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('M, Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Plan</p>
                        <p class="text-gray-900 dark:text-white flex items-center">
                            <span
                                class="bg-gradient-to-r from-primary-500 to-primary-600 text-white text-xs px-2 py-1 rounded-lg mr-2">Pro</span>
                            <span
                                class="text-xs text-primary-600 dark:text-primary-400 cursor-pointer hover:underline">Upgrade</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Connected Accounts -->
            <div class="glass-card rounded-2xl p-6 slide-in">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Connected Accounts</h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                <i class="fab fa-google text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">Google</span>
                        </div>
                        <span
                            class="text-xs text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-2 py-1 rounded-lg">Connected</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center mr-3">
                                <i class="fab fa-apple text-gray-700 dark:text-gray-300"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">Apple</span>
                        </div>
                        <button
                            class="text-xs text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Connect</button>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                <i class="fab fa-linkedin text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">LinkedIn</span>
                        </div>
                        <button
                            class="text-xs text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Connect</button>
                    </div>
                </div>
            </div>

            <!-- Recent Collaborators -->
            <div class="glass-card rounded-2xl p-6 slide-in">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Recent Collaborators</h3>

                <div class="space-y-3">
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                            AS</div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Alice Smith</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Product Manager</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                            MJ</div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Mike Johnson</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Developer</p>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-lg">
                            SD</div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Sarah Davis</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">UX Researcher</p>
                        </div>
                    </div>
                </div>

                <button
                    class="w-full mt-4 py-2 glass text-gray-900 dark:text-white rounded-xl font-medium hover:bg-white/20 dark:hover:bg-black/20 transition-colors text-sm">
                    View all collaborators
                </button>
            </div>
        </div>
    </div>
</div>
