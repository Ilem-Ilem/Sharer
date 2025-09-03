<?php

use Livewire\Volt\Component;

new class extends Component {
    
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
                <button
                    class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-white dark:bg-gray-800 flex items-center justify-center shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-camera text-gray-700 dark:text-gray-300 text-xs"></i>
                </button>
            </div>

            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">John Doe</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Product Designer at TechCorp</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2"><i class="fas fa-map-marker-alt mr-2"></i>San
                    Francisco, CA</p>

                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-4">
                    <span class="text-xs px-3 py-1.5 glass rounded-full text-gray-700 dark:text-gray-300">UI/UX
                        Design</span>
                    <span class="text-xs px-3 py-1.5 glass rounded-full text-gray-700 dark:text-gray-300">Product
                        Strategy</span>
                    <span
                        class="text-xs px-3 py-1.5 glass rounded-full text-gray-700 dark:text-gray-300">Wireframing</span>
                </div>
            </div>

            <div class="mt-4 md:mt-0">
                <button
                    class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-colors shadow-lg">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </button>
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">142</p>
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">24</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Collaborators</p>
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
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">87</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Shared Notes</p>
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

    <div class="flex flex-col lg:flex-row gap-6 relative" x-data="{ activeTab: 'overview' }">
        <!-- Main Content -->
        <div class="flex-1">
            <!-- Tabs -->
            <div class="glass-card rounded-2xl p-1 mb-6 flex overflow-x-auto">
                <button @click="activeTab = 'overview'"
                    :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'overview' }"
                    class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                    Overview
                </button>
                <button @click="activeTab = 'activity'"
                    :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'activity' }"
                    class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                    Activity
                </button>
                <button @click="activeTab = 'notes'"
                    :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'notes' }"
                    class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                    My Notes
                </button>
                <button @click="activeTab = 'collections'"
                    :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'collections' }"
                    class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                    Collections
                </button>
                <button @click="activeTab = 'settings'"
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
                        Product designer with 5+ years of experience creating user-centered digital products.
                        Passionate about design systems, interaction design, and accessibility. Currently working
                        on improving collaboration between designers and developers at TechCorp.
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
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-80 flex-none space-y-6 sticky top-6">
            <!-- Account Details -->
            <div class="glass-card rounded-2xl p-6 slide-in">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account Details</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</p>
                        <p class="text-gray-900 dark:text-white">john.doe@example.com</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Member since</p>
                        <p class="text-gray-900 dark:text-white">January 2022</p>
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