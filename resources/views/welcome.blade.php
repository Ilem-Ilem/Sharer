<x-layouts.app>
    <x-slot:title>
        Sharer - AI-Powered Collaborative Note-Taking
        </x-slot>
        <!-- Hero Section -->
        <section class="hero-pattern py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <!-- Left Column -->
                    <div class="md:w-1/2 mb-12 md:mb-0">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6 ">
                            Collaborate on Notes with <span class="text-primary-600">AI Power</span>
                        </h1>
                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-8 font-edu">
                            NoteSync brings real-time collaboration and AI-powered summaries to your note-taking
                            workflow.
                            Work together, get insights, and stay productive.
                        </p>
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 font-agbalumo">
                            @if(auth()->check())
                                <a href="{{ route('dashboard') }}"
                                    class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 text-center transition-colors shadow-lg hover:shadow-xl" wire:navigate>
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="#"
                                    class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 text-center transition-colors shadow-lg hover:shadow-xl" wire:navigate>
                                    Get Started for Free
                                </a>
                            @endif

                            <a href="{{ route('note.index') }}"
                                class="px-6 py-3 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30 text-center transition-colors flex items-center justify-center" wire:navigate>
                                <i class="fas fa-play-circle mr-2"></i> View Books
                            </a>
                        </div>
                        <div class="mt-8 flex items-center space-x-6 font-satisfy">
                            <div class="flex -space-x-2">
                                <div
                                    class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900 bg-primary-500">
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900 bg-primary-400">
                                </div>
                                <div
                                    class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-900 bg-primary-300">
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400"><span
                                    class="font-semibold text-primary-600">5,000+</span> users collaborating daily</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="md:w-1/2">
                        <div class="relative">
                            <div
                                class="glass-intense rounded-2xl shadow-xl p-6 transform rotate-2 border border-white/20 font-fira">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                            <i class="fas fa-user text-primary-600 dark:text-primary-400"></i>
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white">Team Project</span>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Editing now: 3 people</div>
                                </div>
                                <div class="prose dark:prose-invert max-w-none">
                                    <h3 class="text-primary-600 dark:text-primary-400 font-playwrite">Project
                                        Brainstorming</h3>
                                    <p class="text-gray-700 dark:text-gray-300 font-edu">Let's collaborate on the new
                                        marketing campaign ideas. I've added some initial thoughts below.</p>
                                    <ul class="text-gray-700 dark:text-gray-300 font-edu">
                                        <li>Social media campaign targeting developers</li>
                                        <li>Webinar series with industry experts</li>
                                        <li>Content upgrade strategy for blog posts</li>
                                    </ul>
                                </div>
                                <div class="mt-4 p-3 glass rounded-lg font-satisfy">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">AI
                                            Summary</span>
                                        <i class="fas fa-robot text-primary-600 dark:text-primary-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">This note discusses
                                        potential marketing strategies including social media, webinars, and content
                                        upgrades.</p>
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-4 -left-4 bg-primary-500 text-white rounded-lg px-3 py-2 shadow-lg glass-intense border border-white/20 font-satisfy">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                                    <span class="text-sm font-medium">Real-time collaboration</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">Powerful Features
                    </h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto font-edu">NoteSync combines the
                        best of
                        note-taking with cutting-edge AI technology</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 font-fira">
                    <!-- Feature 1 -->
                    <div class="glass-intense rounded-2xl p-6 feature-card border border-white/20">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center mb-4">
                            <i class="fas fa-users text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Real-time
                            Collaboration</h3>
                        <p class="text-gray-700 dark:text-gray-300">Work on notes simultaneously with your team. See
                            changes
                            as they happen with live cursor tracking.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-intense rounded-2xl p-6 feature-card border border-white/20">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center mb-4">
                            <i class="fas fa-robot text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">AI-Powered
                            Summaries</h3>
                        <p class="text-gray-700 dark:text-gray-300">Get automatic summaries of your notes, highlighting
                            key
                            points and action items.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-intense rounded-2xl p-6 feature-card border border-white/20">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center mb-4">
                            <i class="fas fa-shield-alt text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Secure & Private
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">Your data is encrypted and secure. We prioritize
                            your
                            privacy with industry-leading security measures.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="glass-intense rounded-2xl p-6 feature-card border border-white/20">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center mb-4">
                            <i class="fas fa-bolt text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Lightning Fast
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">Experience blazing fast performance with our
                            optimized
                            architecture and global CDN.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="glass-intense rounded-2xl p-6 feature-card border border-white/20">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center mb-4">
                            <i class="fas fa-mobile-alt text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Cross-Platform
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">Access your notes from anywhere, on any device. Full
                            mobile and desktop support.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="glass-intense rounded-2xl p-6 feature-card border border-white/20">
                        <div
                            class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900 flex items-center justify-center mb-4">
                            <i class="fas fa-search text-primary-600 dark:text-primary-400 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Smart Search
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">Find anything in your notes instantly with our
                            AI-powered search that understands context.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 gradient-bg">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">How It Works</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto font-edu">Getting started with
                        NoteSync is
                        simple and straightforward</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 font-fira">
                    <div class="text-center">
                        <div
                            class="w-16 h-16 rounded-full glass-intense flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <span
                                class="text-2xl font-bold text-primary-600 dark:text-primary-400 font-playwrite">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Create a Note
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">Start a new note or import existing documents. Use
                            our
                            rich text editor to format your content.</p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 rounded-full glass-intense flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <span
                                class="text-2xl font-bold text-primary-600 dark:text-primary-400 font-playwrite">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Invite
                            Collaborators</h3>
                        <p class="text-gray-700 dark:text-gray-300">Share your note with team members. Control
                            permissions
                            with view, comment, or edit access.</p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 rounded-full glass-intense flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <span
                                class="text-2xl font-bold text-primary-600 dark:text-primary-400 font-playwrite">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Get AI Insights
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300">Let our AI generate summaries, action items, and
                            insights from your collaborative work.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">What Our Users Say
                    </h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto font-edu">
                        Hear from teams that are already using NoteSync to transform their workflow
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 font-fira">
                    <!-- Testimonial 1 -->
                    <div class="glass-intense rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold mr-4 font-fira">
                                JD</div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white font-playwrite">John Doe</h4>
                                <p class="text-primary-600 font-fira">Product Manager</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            "NoteSync has completely transformed how our team collaborates. The AI summaries save us
                            hours of meeting time each week."
                        </p>
                        <div class="mt-4 flex text-primary-600">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="glass-intense rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold mr-4 font-fira">
                                AS</div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white font-playwrite">Alice Smith</h4>
                                <p class="text-primary-600 font-fira">Software Engineer</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            "The real-time collaboration is seamless. I can see exactly what my teammates are working on
                            without constant meetings."
                        </p>
                        <div class="mt-4 flex text-primary-600">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="glass-intense rounded-2xl p-6 border border-white/20">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold mr-4 font-fira">
                                RJ</div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white font-playwrite">Robert Johnson</h4>
                                <p class="text-primary-600 font-fira">Marketing Director</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            "The AI-powered insights have helped us identify patterns and opportunities we would have
                            otherwise missed."
                        </p>
                        <div class="mt-4 flex text-primary-600">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-20 gradient-bg">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">Simple, Transparent
                        Pricing</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto font-edu">
                        Choose the plan that works best for your team
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 font-fira">
                    <!-- Free Plan -->
                    <div class="glass-intense rounded-2xl p-8 border border-white/20 text-center">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">Free</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white font-playwrite">$0</span>
                            <span class="text-gray-600 dark:text-gray-400 font-fira">/month</span>
                        </div>
                        <ul class="mb-8 space-y-3 font-edu">
                            <li class="text-white">Up to 5 users</li>
                            <li class="text-white">Basic note collaboration</li>
                            <li class="text-white">5 AI summaries per month</li>
                            <li class="text-white">2GB storage</li>
                        </ul>
                        <a href="#"
                            class="block w-full py-3 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30 transition-colors font-fira">Get
                            Started</a>
                    </div>

                    <!-- Pro Plan -->
                    <div class="glass-intense rounded-2xl p-8 border-2 border-primary-500 text-center relative">
                        <div
                            class="absolute top-0 right-0 bg-primary-500 text-white text-xs font-bold px-4 py-1 rounded-bl-lg rounded-tr-lg font-fira">
                            MOST POPULAR</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">Pro</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white font-playwrite">$12</span>
                            <span class="text-gray-600 dark:text-gray-400 font-fira">/user/month</span>
                        </div>
                        <ul class="mb-8 space-y-3 font-edu">
                            <li class="text-white">Unlimited users</li>
                            <li class="text-white">Advanced collaboration tools</li>
                            <li class="text-white">Unlimited AI summaries</li>
                            <li class="text-white">50GB storage</li>
                            <li class="text-white">Priority support</li>
                        </ul>
                        <a href="#"
                            class="block w-full py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors font-fira">Get
                            Started</a>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="glass-intense rounded-2xl p-8 border border-white/20 text-center">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">Enterprise</h3>
                        <div class="mb-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white font-playwrite">Custom</span>
                        </div>
                        <ul class="mb-8 space-y-3 font-edu text-white">
                            <li class="text-white">Unlimited everything</li>
                            <li class="text-white">Custom AI model training</li>
                            <li class="text-white">On-premise deployment</li>
                            <li class="text-white">Dedicated account manager</li>
                            <li class="text-white">24/7 support</li>
                        </ul>
                        <a href="#"
                            class="block w-full py-3 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30 transition-colors font-fira">Contact
                            Sales</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 font-playwrite">Frequently Asked
                        Questions</h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto font-edu">
                        Everything you need to know about NoteSync
                    </p>
                </div>

                <div class="max-w-3xl mx-auto font-fira">
                    <div class="glass-intense rounded-2xl p-6 mb-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">How does the AI
                            summary feature work?</h3>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            Our AI uses advanced natural language processing to analyze your notes and extract key
                            points, action items, and insights. It learns from your team's usage patterns to provide
                            increasingly relevant summaries over time.
                        </p>
                    </div>

                    <div class="glass-intense rounded-2xl p-6 mb-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Is my data
                            secure with NoteSync?</h3>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            Absolutely. We use end-to-end encryption for all your notes and follow industry best
                            practices for security. Your data is never used to train our AI models without your explicit
                            permission.
                        </p>
                    </div>

                    <div class="glass-intense rounded-2xl p-6 mb-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">Can I use
                            NoteSync offline?</h3>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            Yes! Our mobile and desktop apps allow you to access and edit your notes offline. Changes
                            will sync automatically once you're back online.
                        </p>
                    </div>

                    <div class="glass-intense rounded-2xl p-6 mb-6 border border-white/20">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 font-playwrite">How many people
                            can collaborate on a note?</h3>
                        <p class="text-gray-700 dark:text-gray-300 font-edu">
                            With our Pro and Enterprise plans, there's no limit to the number of collaborators on a
                            note. The Free plan allows up to 5 users per workspace.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-primary-600">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-6">Ready to Transform Your Note-Taking?</h2>
                <p class="text-lg text-primary-100 max-w-2xl mx-auto mb-8">Join thousands of teams already using
                    NoteSync to
                    collaborate smarter and achieve more.</p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="#"
                        class="px-6 py-3 bg-white text-primary-600 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                        Get Started for Free
                    </a>
                    <a href="#"
                        class="px-6 py-3 glass text-white rounded-lg font-medium hover:bg-white/20 transition-colors">
                        Schedule a Demo
                    </a>
                </div>
            </div>
        </section>
</x-layouts.app>