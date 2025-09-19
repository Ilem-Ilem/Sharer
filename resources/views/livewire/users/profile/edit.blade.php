<?php

use Livewire\Volt\Component;
use App\Models\Profile;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ?string $username = null;
    public ?string $bio = null;
    public ?string $location = null;
    public ?string $occupation = null;
    public ?string $field_of_study = null;
    public ?string $education = null;
    public ?string $website = null;
    public ?string $birthday = null;
    public ?string $gender = null;
    public ?string $visibility = null;
    public ?string $theme = null;
    public ?string $language = null;
    public $user;
    
    public $profile_photo;   // temporary file
    public $avatar;
    public $cover_photo;

    public function mount()
    {
        $profile = auth()->user()->profile;
        $this->user = auth()->user();
        if ($profile) {
            $this->fill([
                'username'       => $profile->username,
                'bio'            => $profile->bio,
                'location'       => $profile->location,
                'occupation'     => $profile->occupation,
                'field_of_study' => $profile->field_of_study,
                'education'      => $profile->education,
                'website'        => $profile->website,
                'birthday'       => $profile->birthday,
                'gender'         => $profile->gender,
                'visibility'     => $profile->visibility,
                'theme'          => $profile->theme,
                'language'       => $profile->language,
            ]);
            $this->avatar = auth()->user()->profile->avatar ?? null;
        }
    }

    public function save()
    {
        $this->validate([
            'username'       => 'required|string|max:150|unique:profiles,username,' . auth()->user()->profile->id,
            'bio'            => 'nullable|string|max:500',
            'location'       => 'nullable|string|max:255',
            'occupation'     => 'nullable|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'education'      => 'nullable|string|max:255',
            'website'        => 'nullable|url|max:255',
            'birthday'       => 'nullable|date',
            'gender'         => 'nullable|in:male,female',
            'visibility'     => 'required|in:public,private',
            'theme'          => 'required|in:light,dark',
            'language'       => 'required|string|max:10',
            'avatar'         => 'nullable|image|max:2048',
            'cover_photo'    => 'nullable|image|max:4096',
        ]);

        $profile = auth()->user()->profile;

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = auth()->id();
        }

        if ($this->avatar) {
            $profile->avatar = $this->avatar->store('avatars', 'public');
        }

        if ($this->cover_photo) {
            $profile->cover_photo = $this->cover_photo->store('covers', 'public');
        }

        $profile->fill([
            'username'       => $this->username,
            'bio'            => $this->bio,
            'location'       => $this->location,
            'occupation'     => $this->occupation,
            'field_of_study' => $this->field_of_study,
            'education'      => $this->education,
            'website'        => $this->website,
            'birthday'       => $this->birthday,
            'gender'         => $this->gender,
            'visibility'     => $this->visibility,
            'theme'          => $this->theme,
            'language'       => $this->language,
        ]);

        $profile->save();

        session()->flash('success', 'Profile updated successfully!');
    }
    
    public function updatedProfilePhoto()
    {
        // validate instantly on upload
        $this->validate([
            'profile_photo' => 'nullable|image|max:5120', // 5MB
        ]);
    }
    public function savePhoto()
    {
        $this->validate([
            'profile_photo' => 'nullable|image|max:5120', // 5MB
        ]);

        if ($this->profile_photo) {
            $path = $this->profile_photo->store('avatars', 'public');

            $profile = auth()->user()->profile;
            $profile->avatar = $path;
            $profile->save();

            $this->avatar = $path;
            $this->profile_photo = null; // reset temp upload

            session()->flash('success', 'Profile photo updated!');
        }
    }

    public function removePhoto()
    {
        $profile = auth()->user()->profile;
        if ($profile->avatar) {
            \Storage::disk('public')->delete($profile->avatar);
            $profile->avatar = null;
            $profile->save();
        }

        $this->avatar = null;
        $this->profile_photo = null;

        session()->flash('success', 'Profile photo removed.');
    }
}; ?>

<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Page Header -->
        <form wire:submit.prevent='save'>
              <div class="glass-card rounded-2xl p-6 mb-6 profile-header fade-in">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your account settings and preferences</p>
                        </div>

                        <div class="flex space-x-2 mt-4 md:mt-0">
                            <button class="px-4 py-2 glass text-gray-900 dark:text-white rounded-xl font-medium hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                Cancel
                            </button>
                            <button class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-colors shadow-lg">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Main Content -->
                    <div class="flex-1">
                        <!-- Tabs -->
                        <div class="glass-card rounded-2xl p-1 mb-6 flex overflow-x-auto">
                            <button @click="activeTab = 'profile'" :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'profile' }" class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                Profile
                            </button>
                            <button @click="activeTab = 'additional'" :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'additional' }" class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                Additional Info
                            </button>
                            <button @click="activeTab = 'notifications'" :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'notifications' }" class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                Notifications
                            </button>
                            <button @click="activeTab = 'preferences'" :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'preferences' }" class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                Preferences
                            </button>
                            <button @click="activeTab = 'billing'" :class="{ 'active text-primary-600 dark:text-primary-400': activeTab === 'billing' }" class="tab-button px-4 py-2 text-gray-700 dark:text-gray-300 font-medium whitespace-nowrap">
                                Billing
                            </button>
                        </div>

                        <!-- Profile Tab Content -->
                        <div x-show="activeTab === 'profile'" class="slide-in">
                            <div class="glass-card rounded-2xl p-6 mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Profile Information</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Update your photo and personal details here.</p>

                                <div class="flex flex-col md:flex-row items-center md:items-start mb-6">
                                    <div class="relative mb-4 md:mb-0 md:mr-6">
                                        <div class="w-24 h-24 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 
                                                    flex items-center justify-center text-white text-3xl font-bold shadow-lg overflow-hidden">

                                            {{-- Temp preview if uploading --}}
                                            @if($profile_photo)
                                            <img src="{{ $profile_photo->temporaryUrl() }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">

                                            {{-- Saved avatar --}}
                                            @elseif($avatar)
                                            <img src="{{ asset('storage/'.$avatar) }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">

                                            {{-- Initials fallback --}}
                                            @else
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                            {{ substr(strrchr(auth()->user()->name, ' '), 1, 1) }}
                                            @endif
                                        </div>

                                        <label class="avatar-upload absolute bottom-0 right-0 w-8 h-8 rounded-full 
                                                    bg-white dark:bg-gray-800 flex items-center justify-center shadow-md 
                                                    hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                            <input type="file" wire:model="profile_photo" class="hidden">
                                            <i class="fas fa-camera text-gray-700 dark:text-gray-300 text-xs"></i>
                                        </label>
                                    </div>

                                    <div class="flex-1 text-center md:text-left">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            Recommended: JPG, PNG or GIF, Max 5MB
                                        </p>
                                        @if($avatar || $profile_photo)
                                        <button wire:click="removePhoto" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                            Remove photo
                                        </button>
                                        @endif
                                        {{-- Save button (only when new photo is uploaded) --}}
                                        @if($profile_photo)
                                        <span class="mt-4">
                                            <button wire:click="savePhoto" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-colors shadow-lg">
                                                Save New Photo
                                            </button>
                                        </span>
                                        @endif

                                        @error('profile_photo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>




                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="col-span-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                                        <input type="text" disabled class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white" value="{{ auth()->user()->name }}">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                                    <input type="text" wire:model="username" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                                    <textarea wire:model="bio" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white" rows="4">Product designer with 5+ years of experience creating user-centered digital products. Passionate about design systems, interaction design, and accessibility.</textarea>
                                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info Tab Content -->
                        <div x-show="activeTab === 'additional'" class="slide-in">
                            <div class="glass-card rounded-2xl p-6 mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Additional Information</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Provide more details about yourself.</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                        <input type="text" wire:model="location" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                        @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Occupation</label>
                                        <input type="text" wire:model="occupation" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                        @error('occupation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Field of Study</label>
                                        <input type="text" wire:model="field_of_study" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                        @error('field_of_study') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Education</label>
                                        <input type="text" wire:model="education" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                        @error('education') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website</label>
                                        <input type="text" wire:model="website" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                        @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Birthday</label>
                                        <input type="date" wire:model="birthday" class="w-full form-input glass rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                        @error('birthday') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                                        <select wire:model="gender" class="w-full form-input glass glass-card rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                            <option value="" class="glass-card">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Visibility</label>
                                        <select wire:model="visibility" class="w-full form-input glass glass-card rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                            <option value="public">Public</option>
                                            <option value="private">Private</option>
                                            <option value="friends">Friends Only</option>
                                        </select>
                                        @error('visibility') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Tab Content -->
                        <div x-show="activeTab === 'notifications'" class="slide-in">
                            <div class="glass-card rounded-2xl p-6 mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notification Preferences</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Manage how you receive notifications from NoteSync.</p>

                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Email Notifications</h4>

                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">New note mentions</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Get notified when someone mentions you in a note</p>
                                                </div>
                                                <label for="mention-notifications" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="mention-notifications" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Note comments</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Get notified when someone comments on your notes</p>
                                                </div>
                                                <label for="comment-notifications" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="comment-notifications" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Collaboration invites</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Get notified when someone invites you to collaborate</p>
                                                </div>
                                                <label for="collab-notifications" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="collab-notifications" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Product updates</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Get notified about new features and improvements</p>
                                                </div>
                                                <label for="update-notifications" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="update-notifications" class="toggle-checkbox sr-only">
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Push Notifications</h4>

                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Real-time collaboration</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Get notified when collaborators edit shared notes</p>
                                                </div>
                                                <label for="realtime-notifications" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="realtime-notifications" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Reminders</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Get reminders for upcoming deadlines</p>
                                                </div>
                                                <label for="reminder-notifications" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="reminder-notifications" class="toggle-checkbox sr-only">
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Notification Frequency</h4>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="frequency" class="mr-2 text-primary-600 focus:ring-primary-500" checked>
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Immediate</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="frequency" class="mr-2 text-primary-600 focus:ring-primary-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Daily Digest</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="frequency" class="mr-2 text-primary-600 focus:ring-primary-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">Weekly Digest</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences Tab Content -->
                        <div x-show="activeTab === 'preferences'" class="slide-in">
                            <div class="glass-card rounded-2xl p-6 mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Appearance & Preferences</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Customize how NoteSync looks and behaves.</p>

                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Theme</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <label class="flex flex-col items-center p-4 glass rounded-xl cursor-pointer border-2 border-transparent hover:border-primary-500 transition-colors">
                                                <div class="w-12 h-12 rounded-lg bg-white shadow-md mb-2 flex items-center justify-center">
                                                    <i class="fas fa-sun text-yellow-500"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Light</span>
                                                <input type="radio" wire:model="theme" value="light" class="sr-only">
                                            </label>

                                            <label class="flex flex-col items-center p-4 glass rounded-xl cursor-pointer border-2 border-transparent hover:border-primary-500 transition-colors">
                                                <div class="w-12 h-12 rounded-lg bg-gray-900 shadow-md mb-2 flex items-center justify-center">
                                                    <i class="fas fa-moon text-purple-400"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dark</span>
                                                <input type="radio" wire:model="theme" value="dark" class="sr-only" checked>
                                            </label>

                                            <label class="flex flex-col items-center p-4 glass rounded-xl cursor-pointer border-2 border-transparent hover:border-primary-500 transition-colors">
                                                <div class="w-12 h-12 rounded-lg bg-gradient-to-r from-gray-800 to-gray-900 shadow-md mb-2 flex items-center justify-center">
                                                    <i class="fas fa-adjust text-gray-300"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">System</span>
                                                <input type="radio" wire:model="theme" value="system" class="sr-only">
                                            </label>
                                        </div>
                                        @error('theme') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Language</h4>
                                        <select wire:model="language" class="w-full form-input glass glass-card rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                            <option value="en">English</option>
                                            <option value="es">Spanish</option>
                                            <option value="fr">French</option>
                                            <option value="de">German</option>
                                        </select>
                                        @error('language') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Editor Preferences</h4>

                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Font Size</label>
                                                <select class="w-full form-input glass glass-card rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                                    <option>Small</option>
                                                    <option selected>Medium</option>
                                                    <option>Large</option>
                                                    <option>X-Large</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Default Font Family</label>
                                                <select class="w-full form-input glass glass-card rounded-xl px-4 py-3 border border-gray-200 dark:border-gray-700 focus:outline-none focus:border-primary-500 text-gray-900 dark:text-white">
                                                    <option selected>Inter (Default)</option>
                                                    <option>Roboto</option>
                                                    <option>Open Sans</option>
                                                    <option>Source Sans Pro</option>
                                                    <option>System UI</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Auto-save</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Automatically save changes as you type</p>
                                                </div>
                                                <label for="autosave" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="autosave" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Spell Check</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Check spelling as you type</p>
                                                </div>
                                                <label for="spellcheck" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="spellcheck" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Privacy</h4>

                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Search engine indexing</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Allow search engines to index your public notes</p>
                                                </div>
                                                <label for="indexing" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="indexing" class="toggle-checkbox sr-only">
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Show online status</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Allow others to see when you're online</p>
                                                </div>
                                                <label for="online-status" class="toggle-label flex items-center cursor-pointer">
                                                    <div class="relative">
                                                        <input type="checkbox" id="online-status" class="toggle-checkbox sr-only" checked>
                                                        <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full shadow-inner"></div>
                                                        <div class="dot absolute w-4 h-4 bg-white rounded-full shadow left-1 top-1 transition"></div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Tab Content -->
                        <div x-show="activeTab === 'billing'" class="slide-in">
                            <div class="glass-card rounded-2xl p-6 mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Billing & Plans</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Manage your subscription and payment methods.</p>

                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Current Plan</h4>

                                        <div class="glass-card rounded-xl p-4 border-2 border-primary-500">
                                            <div class="flex flex-col md:flex-row md:items-center justify-between">
                                                <div>
                                                    <span class="bg-gradient-to-r from-primary-500 to-primary-600 text-white text-xs px-3 py-1 rounded-full">Pro Plan</span>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">$12/month per user</p>
                                                </div>
                                                <button class="mt-3 md:mt-0 px-4 py-2 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                                    Change Plan
                                                </button>
                                            </div>

                                            <div class="mt-4 grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Billing Cycle</p>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Monthly</p>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">Next Billing Date</p>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Oct 15, 2023</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Payment Methods</h4>

                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between glass rounded-xl p-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-6 bg-gradient-to-r from-purple-500 to-purple-600 rounded-sm flex items-center justify-center mr-3">
                                                        <span class="text-white font-bold text-xs">VISA</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Visa ending in 4567</p>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">Expires 12/2024</p>
                                                    </div>
                                                </div>
                                                <button class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>

                                            <button class="w-full py-3 glass text-gray-900 dark:text-white rounded-xl font-medium hover:bg-white/20 dark:hover:bg-black/20 transition-colors flex items-center justify-center">
                                                <i class="fas fa-plus mr-2"></i> Add Payment Method
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Billing History</h4>

                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                                        <th class="text-left py-3 text-gray-600 dark:text-gray-400">Date</th>
                                                        <th class="text-left py-3 text-gray-600 dark:text-gray-400">Description</th>
                                                        <th class="text-left py-3 text-gray-600 dark:text-gray-400">Amount</th>
                                                        <th class="text-left py-3 text-gray-600 dark:text-gray-400">Status</th>
                                                        <th class="text-right py-3 text-gray-600 dark:text-gray-400">Invoice</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                                        <td class="py-3 text-gray-900 dark:text-white">Sep 15, 2023</td>
                                                        <td class="py-3 text-gray-900 dark:text-white">Pro Plan Subscription</td>
                                                        <td class="py-3 text-gray-900 dark:text-white">$12.00</td>
                                                        <td class="py-3"><span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded-full">Paid</span></td>
                                                        <td class="py-3 text-right"><a href="#" class="text-primary-600 dark:text-primary-400 hover:underline">Download</a></td>
                                                    </tr>
                                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                                        <td class="py-3 text-gray-900 dark:text-white">Aug 15, 2023</td>
                                                        <td class="py-3 text-gray-900 dark:text-white">Pro Plan Subscription</td>
                                                        <td class="py-3 text-gray-900 dark:text-white">$12.00</td>
                                                        <td class="py-3"><span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded-full">Paid</span></td>
                                                        <td class="py-3 text-right"><a href="#" class="text-primary-600 dark:text-primary-400 hover:underline">Download</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-3 text-gray-900 dark:text-white">Jul 15, 2023</td>
                                                        <td class="py-3 text-gray-900 dark:text-white">Pro Plan Subscription</td>
                                                        <td class="py-3 text-gray-900 dark:text-white">$12.00</td>
                                                        <td class="py-3"><span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded-full">Paid</span></td>
                                                        <td class="py-3 text-right"><a href="#" class="text-primary-600 dark:text-primary-400 hover:underline">Download</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar -->
                    <div class="w-full lg:w-80 flex-none space-y-6">
                        <!-- Save Card -->
                        <div class="glass-card rounded-2xl p-6 slide-in">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Linked Accounts</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Connect your social media accounts to easily share content and grow your network.</p>

                            <div class="space-y-3" id="socialAccounts">
                                <!-- Facebook -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-facebook text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Facebook</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-colors" data-platform="facebook">
                                        Connect
                                    </button>
                                </div>

                                <!-- Twitter -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-twitter text-sky-600 dark:text-sky-400"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Twitter</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300 hover:bg-sky-200 dark:hover:bg-sky-800/50 transition-colors" data-platform="twitter">
                                        Connect
                                    </button>
                                </div>

                                <!-- Instagram -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-instagram text-pink-600 dark:text-pink-400"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Instagram</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-300 hover:bg-pink-200 dark:hover:bg-pink-800/50 transition-colors" data-platform="instagram">
                                        Connect
                                    </button>
                                </div>

                                <!-- LinkedIn -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-linkedin text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">LinkedIn</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-colors" data-platform="linkedin">
                                        Connect
                                    </button>
                                </div>

                                <!-- YouTube -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-youtube text-red-600 dark:text-red-400"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">YouTube</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition-colors" data-platform="youtube">
                                        Connect
                                    </button>
                                </div>

                                <!-- TikTok -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-tiktok text-gray-800 dark:text-gray-200"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">TikTok</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800/50 transition-colors" data-platform="tiktok">
                                        Connect
                                    </button>
                                </div>

                                <!-- Pinterest -->
                                <div class="social-item flex items-center justify-between p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-3">
                                            <i class="fab fa-pinterest text-red-600 dark:text-red-400"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pinterest</span>
                                    </div>
                                    <button class="connect-btn text-xs px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/50 transition-colors" data-platform="pinterest">
                                        Connect
                                    </button>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-800">
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">7 social platforms available</p>
                                    <button id="saveBtn" class="text-xs px-4 py-2 rounded-lg bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 hover:bg-primary-200 dark:hover:bg-primary-800/50 transition-colors opacity-50 cursor-not-allowed" disabled>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Help Card -->
                        <div class="glass-card rounded-2xl p-6 slide-in">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Need Help?</h3>

                            <div class="space-y-3">
                                <a href="#" class="flex items-center p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mr-3">
                                        <i class="fas fa-question-circle text-primary-600 dark:text-primary-400"></i>
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Help Center</span>
                                </a>

                                <a href="#" class="flex items-center p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                        <i class="fas fa-envelope text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Contact Support</span>
                                </a>

                                <a href="#" class="flex items-center p-3 glass rounded-xl hover:bg-white/20 dark:hover:bg-black/20 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                                        <i class="fas fa-book text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Documentation</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
              
    </div>

    <script>
        // // Toggle switch functionality
        // document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
        //     checkbox.addEventListener('change', function() {
        //         const dot = this.nextElementSibling.querySelector('.dot');
        //         if (this.checked) {
        //             dot.style.transform = 'translateX(1rem)';
        //             dot.style.backgroundColor = '#ef4444';
        //         } else {
        //             dot.style.transform = 'translateX(0)';
        //             dot.style.backgroundColor = '#fff';
        //         }
        //     });

        //     // Initialize toggle positions
        //     const dot = checkbox.nextElementSibling.querySelector('.dot');
        //     if (checkbox.checked) {
        //         dot.style.transform = 'translateX(1rem)';
        //         dot.style.backgroundColor = '#ef4444';
        //     }
        // });

        // Theme selection
        document.querySelectorAll('input[name="theme"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="theme"]').forEach(r => {
                    r.parentElement.classList.remove('border-primary-500');
                });
                this.parentElement.classList.add('border-primary-500');
            });
        });

    </script>
</div>
