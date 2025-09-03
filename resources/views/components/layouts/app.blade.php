<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', activeTab: 'profile' }"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sharer'}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="bg-white dark:bg-gray-900 transition-colors duration-300">
    <!-- Header/Navigation -->

    <header class="sticky top-0 z-50 glass-card border-b border-gray-200 dark:border-gray-800"
        x-data="{ mobileOpen: false}" :class="{ 'dark': darkMode }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Left section -->
                <div class="flex items-center space-x-2">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-sticky-note text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white font-playwrite">NoteSync</span>
                </div>

                <!-- Desktop navigation -->
                <nav class="hidden md:flex items-center space-x-4 font-edu">
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Dashboard</a>
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Notes</a>
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Shared</a>
                </nav>

                <!-- Right section -->
                <div class="flex items-center space-x-3">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-moon" x-show="!darkMode" x-cloak></i>
                        <i class="fas fa-sun" x-show="darkMode" x-cloak></i>
                    </button>

                    <!-- Mobile menu button -->
                    <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-bars" x-show="!mobileOpen"></i>
                        <i class="fas fa-times" x-show="mobileOpen" x-cloak></i>
                    </button>

                    <!-- Profile avatar -->
                    @if(!auth()->check())
                        <div class="relative hidden md:flex">
                            <div
                                class="w-fit px-3 py-2 h-8 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold cursor-pointer shadow-lg mr-3">
                                <a href="{{ route('login') }}"> Sign In</a>

                            </div>
                        </div>
                    @else

                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item icon="user" href="{{ route('profile.show') }}">
                                    Profile
                                </flux:menu.item>
                                <flux:menu.separator />
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <flux:menu.item as="button" type="submit" variant="danger">
                                        Logout
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    @endif

                </div>
            </div>
        </div>

        <!-- Mobile slide-down menu -->
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden bg-white border-t border-gray-200 dark:border-gray-800 shadow-lg glass-card">
            <nav class="container mx-auto px-4 py-4">
                <ul class="flex flex-col space-y-3 font-edu">
                    <li><a href="#"
                            class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Dashboard</a>
                    </li>
                    <li><a href="#"
                            class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Notes</a>
                    </li>
                    <li><a href="#"
                            class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Shared</a>
                    </li>
                    <li class="pt-2 border-t border-gray-200 dark:border-gray-800">
                        <div class="flex items-center py-2 px-4">
                            <div
                                class="w-fit px-3 py-2 h-8 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold cursor-pointer shadow-lg mr-3">
                                Sign In
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>


    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{ $slot }}
    </div>
    <!-- Footer -->
    <footer class="py-12 glass-intense border-t border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center">
                            <i class="fas fa-sticky-note text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">NoteSync</span>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">The next generation of collaborative note-taking
                        with AI-powered insights.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Product</h3>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Features</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Pricing</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Use
                                Cases</a></li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Integrations</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resources</h3>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Documentation</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Blog</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Tutorials</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Support</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">About
                                Us</a></li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Careers</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Contact</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Privacy
                                Policy</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-gray-200 dark:border-gray-700 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-700 dark:text-gray-300">© 2023 NoteSync. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Terms</a>
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Privacy</a>
                    <a href="#"
                        class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    @fluxScripts
</body>

</html>