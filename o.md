
# AI-Powered Note Sharing Platform – Laravel + Livewire Volt Project

Below is a comprehensive response to your request for the **project design**, **file structure**, and **DB design** based on the provided system design. I'll structure it in Markdown for clarity. This assumes Laravel 11.x with Livewire 3.x and Volt for classless Livewire components. The project uses Laravel's MVC architecture enhanced with Livewire for real-time, reactive UIs without heavy JavaScript.

---

## 🚀 Project Design

### Architectural Overview
- **Framework Stack**: 
  - **Backend**: Laravel (PHP framework for routing, authentication, ORM, queues, etc.).
  - **Frontend**: Livewire Volt (for reactive, single-file components that handle state and logic without SPA frameworks like Vue/React). Blade for templating.
  - **Database**: MySQL/PostgreSQL (scalable with Eloquent ORM for relationships).
  - **AI Integration**: Use external APIs (e.g., OpenAI or Hugging Face) for summaries, keywords, embeddings, and Q&A. Queue jobs for async processing to avoid blocking uploads. Store embeddings as JSON for vector search (e.g., via PostgreSQL pgvector extension or Laravel Scout with Algolia/Meilisearch for full-text/vector search).
  - **Authentication**: Laravel Breeze/Jetstream for email/password, social logins, and email verification.
  - **Authorization**: Policies/Gates for note visibility (public/private/friends), collaborations, and exams.
  - **Scalability**: Use Laravel Horizon/Queues for AI tasks. Cache frequently accessed data (e.g., profiles, AI metadata) with Redis. File storage via Laravel Filesystem (local/S3 for notes/files).
  - **Security**: Sanctum for API tokens if needed (e.g., mobile apps). Rate limiting on AI features. Validate inputs to prevent SQL injection/XSS.
  - **Deployment**: Docker for dev/prod. CI/CD with GitHub Actions. Monitoring with Laravel Telescope/Pulse.

### Key Components & Flow
- **MVC + Livewire**:
  - **Models**: Eloquent models for each table (e.g., User, Profile, Note) with relationships (e.g., User hasOne Profile, Note belongsTo User).
  - **Views**: Blade layouts (e.g., app.blade.php) with Livewire components embedded (e.g., @livewire('note-upload')).
  - **Controllers**: HTTP controllers for non-Livewire routes (e.g., API endpoints). Livewire handles most interactive logic.
- **User Flow**:
  - Register/Login → Profile setup → Upload note (triggers AI job) → Share/Collaborate → Bookmark/Search → Create/Take Exam.
- **AI Workflow**:
  - On note upload: Dispatch queue job to generate summary/keywords/embeddings → Store in NoteAi model.
  - Search: Use embeddings for semantic search (e.g., via Laravel Scout).
  - Q&A/Exams: Cache responses; AI generates questions optionally.
- **Temporary Features**: Use expires_at timestamps for collaborations/exams; cron jobs (Laravel Scheduler) to clean up expired entries.
- **Social Features**: Follower system with counters; real-time updates via Laravel Echo/Broadcasting (if needed for notifications).

### Tech Decisions
- **Why Laravel + Livewire Volt?**: Laravel for robust backend; Livewire for SPA-like interactivity without JS fatigue; Volt for simpler, functional component syntax.
- **Extensions**: 
  - Queue: For AI processing.
  - Scout: For advanced search.
  - Socialite: For social logins/links.
- **Testing**: PHPUnit for backend; Pest for simpler syntax. Dusk for browser tests.

---

## 🗄️ DB Design

The database uses MySQL/PostgreSQL with Eloquent ORM. Below are the refined schemas from your design, including relationships and indexes for performance. I've added foreign key constraints, indexes, and soft deletes where useful (e.g., for notes). Use migrations to create these.

### Entity-Relationship Notes
- **One-to-One**: User → Profile.
- **One-to-Many**: User → Notes, User → Bookmarks, User → Collaborations (as owner), User → Exams (as owner).
- **Many-to-Many**: Notes ↔ Tags, Users ↔ Users (followers via pivot).
- **Polymorphic?**: Not needed here.
- **Indexes**: On foreign keys, counts, and search fields (e.g., title, keywords).
- **Vector Search**: For embeddings, use PostgreSQL with pgvector if advanced search is needed.

### Schemas (SQL via Laravel Migrations)

```sql
-- users (auth basics)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- profiles (social details)
CREATE TABLE profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    username VARCHAR(150) UNIQUE NOT NULL,
    avatar VARCHAR(255) NULL,
    cover_photo VARCHAR(255) NULL,
    bio TEXT NULL,
    location VARCHAR(255) NULL,
    website VARCHAR(255) NULL,
    birthday DATE NULL,
    gender ENUM('male', 'female', 'other', 'prefer_not_say') NULL,
    social_links JSON NULL,
    followers_count INT DEFAULT 0,
    following_count INT DEFAULT 0,
    notes_count INT DEFAULT 0,
    visibility ENUM('public', 'private') DEFAULT 'public',
    theme ENUM('light', 'dark') DEFAULT 'light',
    language VARCHAR(10) DEFAULT 'en',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- followers (pivot for follows)
CREATE TABLE followers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    follower_id BIGINT UNSIGNED NOT NULL,
    followed_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    UNIQUE KEY unique_follow (follower_id, followed_id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (followed_id) REFERENCES users(id) ON DELETE CASCADE
);

-- notes (core content)
CREATE TABLE notes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NULL,
    file_path VARCHAR(255) NULL,
    visibility ENUM('public', 'private', 'friends') DEFAULT 'public',
    downloads_count INT DEFAULT 0,
    ratings_sum INT DEFAULT 0,
    ratings_count INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,  -- Soft deletes
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_visibility (visibility)
);

-- note_ai (AI metadata)
CREATE TABLE note_ai (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id BIGINT UNSIGNED NOT NULL,
    summary LONGTEXT NULL,
    keywords JSON NULL,
    embedding JSON NULL,  -- For vector search
    topics JSON NULL,
    qa_cache JSON NULL,
    generated_by VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    INDEX idx_note_id (note_id)
);

-- tags
CREATE TABLE tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL
);

-- note_tag (pivot)
CREATE TABLE note_tag (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE,
    UNIQUE KEY unique_tag (note_id, tag_id)
);

-- bookmarks
CREATE TABLE bookmarks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    note_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bookmark (user_id, note_id)
);

-- collaborations (temporary)
CREATE TABLE collaborations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id BIGINT UNSIGNED NOT NULL,
    owner_id BIGINT UNSIGNED NOT NULL,
    collaborator_id BIGINT UNSIGNED NOT NULL,
    role ENUM('editor', 'viewer') DEFAULT 'editor',
    status ENUM('active', 'ended') DEFAULT 'active',
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (collaborator_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_expires_at (expires_at)
);

-- exams (temporary)
CREATE TABLE exams (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    note_id BIGINT UNSIGNED NOT NULL,
    owner_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    instructions TEXT NULL,
    duration INT NULL,
    status ENUM('draft', 'active', 'ended') DEFAULT 'draft',
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_expires_at (expires_at)
);

-- exam_questions
CREATE TABLE exam_questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('mcq', 'true_false', 'short_answer') DEFAULT 'mcq',
    options JSON NULL,
    answer JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

-- exam_submissions
CREATE TABLE exam_submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    started_at TIMESTAMP NULL,
    submitted_at TIMESTAMP NULL,
    score DECIMAL(5,2) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- exam_answers
CREATE TABLE exam_answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    submission_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    user_answer JSON NULL,
    is_correct BOOLEAN NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (submission_id) REFERENCES exam_submissions(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES exam_questions(id) ON DELETE CASCADE
);
```

### Migrations
In Laravel, create migration files (e.g., `php artisan make:migration create_users_table`) and define the schema in `up()` methods. Use `Schema::create()` with columns and constraints.

---

## 📂 File Structure

Here's a tree-like representation of the project file structure. It follows standard Laravel conventions, with additions for Livewire Volt components (in `app/Livewire/` as single-file PHP with Volt syntax). Routes are in `routes/web.php`. Models in `app/Models/`. Assume Breeze/Jetstream installed for auth scaffolding.

```
project-root/
├── app/
│   ├── Console/                # Kernel.php for commands/scheduling
│   │   └── Commands/           # Custom commands (e.g., CleanupExpiredCollaborations.php)
│   ├── Exceptions/             # Handler.php
│   ├── Http/
│   │   ├── Controllers/        # HTTP controllers (e.g., Api/NoteController.php for any APIs)
│   │   ├── Middleware/         # Auth middleware, etc.
│   │   └── Requests/           # Form requests (e.g., NoteUploadRequest.php)
│   ├── Livewire/               # Volt components (single-file, classless)
│   │   ├── Admin/              # Admin components
│   │   │   ├── ManageUsers.php
│   │   │   ├── ManageNotes.php
│   │   │   └── Moderate.php
│   │   ├── Auth/               # Auth-related (if not using Breeze)
│   │   ├── Dashboard/          # User dashboard
│   │   │   ├── MyNotes.php
│   │   │   ├── UploadNote.php  # Handles upload + AI trigger
│   │   │   ├── MyBookmarks.php
│   │   │   ├── MyCollaborations.php
│   │   │   ├── MyExams.php
│   │   │   └── Settings.php    # Profile/theme/language
│   │   ├── Exams/              # Exam features
│   │   │   ├── Builder.php     # Create/edit questions
│   │   │   ├── Taking.php      # Take exam
│   │   │   └── Results.php
│   │   ├── Notes/              # Note-related
│   │   │   ├── Detail.php      # View note + AI summary/rating/download/bookmark
│   │   │   └── Explore.php     # Search/filter notes
│   │   ├── Profiles/           # Social profiles
│   │   │   └── Show.php        # User profile page
│   │   └── Public/             # Public pages
│   │       └── Landing.php     # Marketing/trending
│   ├── Models/                 # Eloquent models
│   │   ├── Bookmark.php
│   │   ├── Collaboration.php
│   │   ├── Exam.php
│   │   ├── ExamAnswer.php
│   │   ├── ExamQuestion.php
│   │   ├── ExamSubmission.php
│   │   ├── Follower.php
│   │   ├── Note.php
│   │   ├── NoteAi.php
│   │   ├── Profile.php
│   │   ├── Tag.php
│   │   └── User.php
│   ├── Providers/              # Service providers (e.g., AppServiceProvider.php)
│   └── Jobs/                   # Queue jobs (e.g., GenerateNoteAi.php for AI processing)
├── bootstrap/                  # App.php, cache/
├── config/                     # app.php, database.php, queue.php, scout.php (for search)
├── database/
│   ├── factories/              # Model factories for testing/seeding
│   ├── migrations/             # Schema files (e.g., 2025_08_29_000000_create_users_table.php)
│   ├── seeders/                # DatabaseSeeder.php
│   └── sqlite/                 # If using SQLite for dev
├── public/                     # index.php, css/js (compiled), storage symlink
├── resources/
│   ├── css/                    # app.css (Tailwind if using)
│   ├── js/                     # app.js (Alpine if needed)
│   ├── views/                  # Blade templates
│   │   ├── components/         # Reusable (e.g., note-card.blade.php)
│   │   ├── layouts/            # app.blade.php, guest.blade.php
│   │   ├── livewire/           # If any full-page Livewire views
│   │   ├── pages/              # Static pages if needed
│   │   └── emails/             # Mailable templates
├── routes/                     # web.php (Livewire routes via Livewire::component()), api.php
├── storage/                    # app/ (files), framework/, logs/
├── tests/                      # Feature/, Unit/ (e.g., NoteTest.php)
├── vendor/                     # Composer dependencies (Laravel, Livewire, etc.)
├── .env                        # Environment vars (DB creds, AI API keys)
├── artisan                     # CLI tool
├── composer.json               # Dependencies (laravel/framework, livewire/livewire, livewire/volt)
├── package.json                # If using Vite/Tailwind
├── tailwind.config.js          # If using Tailwind
├── vite.config.js              # Asset bundler
└── README.md                   # Project docs
```

### Notes on File Structure
- **Livewire Volt Setup**: Install via `composer require livewire/volt` and `php artisan livewire:install`. Components use `volt()` syntax (e.g., in `app/Livewire/UploadNote.php`: `<?php use function Livewire\Volt\{component, title}; component(fn() => ...);`).
- **Customizations**: Add folders like `app/Services/` for AI service classes (e.g., AiService.php to call external APIs).
- **Assets**: Use Vite for compiling CSS/JS. Include Tailwind for styling.
- **Seeding/Testing**: Factories for dummy data (e.g., User::factory()->has(Profile::factory())).

This structure is modular and scalable. Start with `composer create-project laravel/laravel project-name`, then install Livewire/Volt, and scaffold auth with Breeze. If you need code snippets for specific files (e.g., a model or component), let me know!
Determining the appropriate storage space for users in a **freemium** model for your AI-powered note-sharing platform involves balancing user needs, system scalability, and business incentives to encourage upgrades to paid plans. Since your platform supports both **text-based notes** (stored in the `content` column as `LONGTEXT`) and **PDF files** (stored via `file_path` in a filesystem like local storage or AWS S3), the storage allocation must account for both types of data. Below, I’ll analyze the factors to consider and provide a recommendation for storage limits in the freemium tier, along with rationale and implementation details.

---

## 📊 Factors to Consider for Freemium Storage Limits

1. **User Needs**:
   - **Text Notes**: Text-based notes (e.g., Markdown or plain text) are typically small. A 10,000-word note in UTF-8 encoding is roughly 50-100 KB. Users might create dozens of notes, but the total size per user is likely under a few MB unless they store very large documents.
   - **PDF Notes**: PDFs vary widely in size (e.g., 100 KB for a simple text-based PDF to 10 MB+ for documents with images or complex formatting). Users uploading lecture slides or study guides may need more space.
   - **Use Case**: Students and educators (likely your target audience) may upload frequent small notes (text) or occasional larger PDFs (e.g., textbooks, slides). A freemium tier should support basic usage without feeling restrictive.

2. **System Constraints**:
   - **Database**: Text notes in the `content` column (MySQL `LONGTEXT`, up to 4 GB per row) are stored in the database. However, large text content can degrade DB performance, so you’ll want to limit per-user text storage to avoid bloat.
   - **Filesystem**: PDFs stored via `file_path` (e.g., on S3) are more scalable but incur storage costs. Freemium limits should keep infrastructure costs manageable.
   - **AI Processing**: Both text and PDFs require AI processing (summaries, keywords, embeddings). Limiting storage indirectly controls API usage costs.

3. **Business Model**:
   - **Freemium Goal**: Offer enough storage to attract users and demonstrate value, but incentivize upgrading to a paid plan (e.g., SuperGrok or a custom premium tier) for power users needing more space or features.
   - **Competitive Analysis**: Platforms like Notion (freemium: ~5 MB/file limit, no total storage cap), Evernote (freemium: 60 MB/month upload), and Google Drive (free: 15 GB shared) provide benchmarks. Your platform, focused on notes and PDFs, should align with similar tools.

4. **Technical Implementation**:
   - **Text Storage**: Track total `content` size per user (e.g., sum of `LENGTH(content)` in bytes).
   - **File Storage**: Track total file size per user in the filesystem (e.g., via Laravel’s `Storage` facade).
   - **Enforcement**: Use middleware or form request validation to check storage limits before uploads.

---

## 🏆 Recommended Storage Limits for Freemium Tier

Based on the above factors, here’s a recommended storage allocation for freemium users:

- **Total Storage**: **1 GB** per user (combined for text notes and PDF files).
- **Per-Note Limits**:
  - **Text Notes**: 10 MB per note (arbitrary cap to prevent abuse of `LONGTEXT`).
  - **PDF Files**: 10 MB per file (to support typical documents like slides or study guides).
- **Monthly Upload Quota**: **100 MB/month** (resets monthly to encourage frequent but controlled usage).

### Rationale
- **1 GB Total Storage**:
  - **Why?**: Generous enough for casual users (e.g., students storing ~100 small text notes at 10 KB each or ~100 small PDFs at 10 MB each). Competitive with tools like Evernote (60 MB/month) or Dropbox (2 GB free). Encourages upgrading for heavy users (e.g., those uploading large textbooks).
  - **Cost**: On S3, 1 GB/user costs ~$0.023/month (Standard tier). For 10,000 free users, that’s ~$230/month, manageable with a freemium model. DB storage for text is cheaper but requires indexing optimization.
- **10 MB Per-Note/File**:
  - **Why?**: Prevents abuse (e.g., uploading massive files) while supporting most use cases (e.g., a 10 MB PDF can include text, images, and slides). Text notes rarely exceed 1 MB, so 10 MB is ample.
- **100 MB/Month Upload Quota**:
  - **Why?**: Limits rapid storage consumption, encouraging users to manage space or upgrade. Similar to Evernote’s 60 MB/month but more generous to attract users.
- **Scalability**: 1 GB/user is feasible with S3 for files and a well-indexed MySQL/PostgreSQL DB for text. Use caching (Redis) and queues (Laravel Horizon) to handle AI processing efficiently.

---

## 🛠️ Implementation in Laravel

To enforce these storage limits, you’ll need to:
1. Track storage usage per user.
2. Validate uploads against limits.
3. Provide feedback in the UI (e.g., via Livewire).

### 1. Track Storage Usage
Add a method to the `User` model to calculate storage usage.

```php
// app/Models/User.php
<?php
namespace App\Models;

use App\Traits\HasFollowers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable, HasFollowers;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
    ];

    public function getStorageUsageAttribute()
    {
        // Text storage (in bytes)
        $textSize = $this->notes()
            ->whereNotNull('content')
            ->sum(DB::raw('LENGTH(content)'));

        // File storage (in bytes)
        $fileSize = $this->notes()
            ->whereNotNull('file_path')
            ->get()
            ->sum(function ($note) {
                return Storage::size($note->file_path) ?: 0;
            });

        return $textSize + $fileSize; // Total in bytes
    }

    public function getMonthlyUploadUsageAttribute()
    {
        return $this->notes()
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum(DB::raw('COALESCE(LENGTH(content), 0) + COALESCE((
                SELECT OCTET_LENGTH(data) FROM information_schema.files
                WHERE file_name = notes.file_path
            ), 0)'));
    }

    public function canUpload($sizeInBytes)
    {
        $totalLimit = 1 * 1024 * 1024 * 1024; // 1 GB
        $monthlyLimit = 100 * 1024 * 1024; // 100 MB
        return $this->storage_usage + $sizeInBytes <= $totalLimit
            && $this->monthly_upload_usage + $sizeInBytes <= $monthlyLimit;
    }

    // Relationships (as previously defined)
    public function profile() { return $this->hasOne(Profile::class); }
    public function notes() { return $this->hasMany(Note::class); }
    public function bookmarks() { return $this->hasMany(Bookmark::class); }
    public function collaborations() { return $this->hasMany(Collaboration::class, 'owner_id'); }
    public function collaboratorIn() { return $this->hasMany(Collaboration::class, 'collaborator_id'); }
    public function exams() { return $this->hasMany(Exam::class, 'owner_id'); }
    public function examSubmissions() { return $this->hasMany(ExamSubmission::class); }
}
```

**Note**: For file size calculation, `Storage::size()` works for local/S3 storage. If using MySQL, the `information_schema.files` query is MySQL-specific and assumes files are stored in a DB-managed filesystem (less common). For S3, rely on `Storage::size()`.

### 2. Validate Uploads
Update the `UploadNote` Livewire component to check storage limits before saving.

```php
// app/Livewire/Dashboard/UploadNote.php
<?php
use function Livewire\Volt\{component};
use App\Models\Note;
use App\Jobs\GenerateNoteAi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

component(function () {
    public $title = '';
    public $content = '';
    public $file = null;
    public $visibility = 'public';
    public $type = 'text'; // 'text' or 'pdf'

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required_if:type,text|string|nullable',
        'file' => 'required_if:type,pdf|file|mimes:pdf|max:10240', // 10 MB
        'visibility' => 'required|in:public,private,friends',
    ];

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        $sizeInBytes = $this->type === 'text' ? strlen($this->content) : $this->file->getSize();

        if (!$user->canUpload($sizeInBytes)) {
            throw ValidationException::withMessages([
                'file' => 'Storage limit exceeded (1 GB total or 100 MB/month). Upgrade to premium.',
            ]);
        }

        $noteData = [
            'user_id' => $user->id,
            'title' => $this->title,
            'visibility' => $this->visibility,
        ];

        if ($this->type === 'pdf' && $this->file) {
            $noteData['file_path'] = $this->file->store('notes', 'public');
        } else {
            $noteData['content'] = $this->content;
        }

        $note = Note::create($noteData);
        GenerateNoteAi::dispatch($note);

        session()->flash('message', 'Note saved successfully!');
        $this->reset();
    }
});
?>
<!-- Blade template -->
<div>
    <form wire:submit="save">
        <input type="text" wire:model="title" placeholder="Note Title" />
        @error('title') <span>{{ $message }}</span> @enderror
        <select wire:model="type">
            <option value="text">Text</option>
            <option value="pdf">PDF</option>
        </select>
        @if ($type === 'text')
            <textarea wire:model="content" placeholder="Enter note content"></textarea>
            @error('content') <span>{{ $message }}</span> @enderror
        @else
            <input type="file" wire:model="file" accept=".pdf" />
            @error('file') <span>{{ $message }}</span> @enderror
        @endif
        <select wire:model="visibility">
            <option value="public">Public</option>
            <option value="private">Private</option>
            <option value="friends">Friends</option>
        </select>
        <button type="submit">Save Note</button>
    </form>
    <div>Storage used: {{ number_format(auth()->user()->storage_usage / (1024 * 1024), 2) }} MB / 1000 MB</div>
</div>
```

### 3. Display Storage Usage
Show users their storage usage in the dashboard (`app/Livewire/Dashboard/Settings.php`).

```php
// app/Livewire/Dashboard/Settings.php
<?php
use function Livewire\Volt\{component};

component(function () {
    public function getStorageUsage()
    {
        return number_format(auth()->user()->storage_usage / (1024 * 1024), 2); // MB
    }

    public function getMonthlyUploadUsage()
    {
        return number_format(auth()->user()->monthly_upload_usage / (1024 * 1024), 2); // MB
    }
});
?>
<!-- Blade template -->
<div>
    <h2>Storage Usage</h2>
    <p>Total: {{ $this->getStorageUsage() }} MB / 1000 MB</p>
    <p>This Month: {{ $this->getMonthlyUploadUsage() }} MB / 100 MB</p>
    <a href="{{ url('/upgrade') }}">Upgrade to Premium</a>
</div>
```

---

## 🌟 Additional Considerations

- **Premium Tier**: Offer higher limits (e.g., 10 GB total, 1 GB/month) for paid users (e.g., SuperGrok). Check `role` or subscription status in `canUpload()`.
- **Optimization**:
  - Cache storage usage calculations (e.g., in Redis) to avoid frequent DB queries.
  - Use `php artisan queue:work` for async AI processing to handle large PDFs.
  - Index `notes.created_at` for faster monthly upload queries.
- **User Feedback**: Show a progress bar for storage usage in the UI (e.g., with Tailwind CSS).
- **Edge Cases**:
  - Handle deleted notes (soft deletes) by excluding `deleted_at` timestamps in storage calculations.
  - Validate file uploads for security (e.g., `mimes:pdf` to prevent malicious files).
- **Scalability**:
  - Use S3 for PDF storage to reduce server load.
  - Consider a separate table for storage tracking if usage queries become slow.

---

## ✅ Final Answer

For the freemium tier, allocate **1 GB total storage** per user (combined text and PDFs) with a **100 MB/month upload quota** and a **10 MB per-note/file limit**. This balances usability, cost, and incentives to upgrade to a paid plan. Implement with:
- A `User` model method (`canUpload`) to check storage limits.
- Validation in the `UploadNote` Livewire component to enforce limits.
- UI feedback in the dashboard to show usage (e.g., "500 MB / 1000 MB used").

This setup supports your platform’s hybrid approach (text in `content`, PDFs in `file_path`) and aligns with competitive freemium models. If you need code for additional features (e.g., premium tier logic, storage cleanup jobs), let me know!
In the provided database schema for the AI-powered note-sharing platform, the `notes` table includes a `content` column defined as `LONGTEXT NULL` and a `file_path` column defined as `VARCHAR(255) NULL`. These columns suggest two options for storing notes: as **text** directly in the `content` column or as **files** (e.g., PDFs) referenced by the `file_path` column. Below, I'll analyze whether notes should be saved as text in the `content` column or as PDFs referenced by `file_path`, and which approach is better for your use case.

---

## 📝 Options for Storing Notes

### 1. Storing Notes as Text in the `content` Column
- **Description**: The note's content is stored directly in the `content` column as plain text or a structured format (e.g., Markdown, HTML).
- **How It Works**:
  - Users input text (e.g., via a rich text editor like Trix or a Markdown editor).
  - The text is saved in the `content` column (`LONGTEXT` supports up to 4GB of text in MySQL).
  - AI processes the text directly for summaries, keywords, embeddings, etc.
  - The `file_path` column remains `NULL` unless a file is uploaded separately.

### 2. Storing Notes as PDFs in the `file_path` Column
- **Description**: Notes are uploaded as PDF files, stored on the server (or cloud storage like S3), with the file location saved in the `file_path` column.
- **How It Works**:
  - Users upload a PDF file (e.g., via a file input in a Livewire component).
  - The file is stored in the Laravel filesystem (e.g., `storage/app/public/notes/note_123.pdf`).
  - The `file_path` column stores the path (e.g., `notes/note_123.pdf`).
  - For AI processing, the PDF content must be extracted (e.g., using a library like `Smalot\PdfParser` or an external API) before generating summaries, keywords, or embeddings.
  - The `content` column may be `NULL` or used to store extracted text for search/AI purposes.

---

## ⚖️ Comparison: Text vs. PDF

| **Aspect**                | **Text in `content` Column**                              | **PDF in `file_path` Column**                            |
|---------------------------|---------------------------------------------------------|-------------------------------------------------------|
| **Storage**               | Stored directly in DB (`LONGTEXT`).                     | Stored as files on disk/S3, path in DB (`VARCHAR`).    |
| **Size Limit**            | MySQL `LONGTEXT`: ~4GB (but DB performance may degrade). | File size limited by filesystem (S3: 5TB max).         |
| **Ease of AI Processing** | Direct access to text for AI (no extraction needed).    | Requires PDF parsing (e.g., `Smalot\PdfParser`) for AI. |
| **Searchability**         | Easily searchable with full-text indexes or Scout.      | Needs extracted text for search (extra step).          |
| **Display**               | Rendered via Blade (e.g., Markdown parsed to HTML).      | Requires PDF viewer (e.g., `<embed>` or JavaScript).   |
| **Editing**               | Easily editable in a text editor (Livewire component).  | Hard to edit (requires re-upload or conversion).       |
| **Formatting**            | Limited to text/Markdown; no complex layouts.            | Supports rich formatting (images, tables, fonts).      |
| **Download**              | Convert to PDF on demand (e.g., `DomPDF`).               | Already in PDF; direct download.                      |
| **Collaboration**         | Real-time editing possible (e.g., via Livewire).         | Limited (requires external tools or conversion).       |
| **Performance**           | Faster for small notes; DB-heavy for large ones.         | File storage is scalable; parsing adds overhead.       |
| **Security**              | Sanitize input to prevent XSS (e.g., with `Purifier`).  | Validate PDFs to prevent malicious files.              |
| **Use Case Fit**          | Best for simple notes, text-based collaboration.        | Best for formatted documents, sharing pre-made PDFs.   |

---

## 🏆 Which Is Better?

The choice depends on your platform's **use case**, **user needs**, and **technical constraints**. Here’s a breakdown:

### When to Use Text (`content` Column)
- **Best For**:
  - Users creating notes directly on the platform (e.g., via a text editor).
  - Real-time collaboration (e.g., multiple users editing a note via Livewire).
  - Fast AI processing (no need to extract text from files).
  - Search-heavy features (e.g., semantic search with embeddings or full-text search with Scout).
  - Lightweight storage for small-to-medium notes.
- **Pros**:
  - Simplifies AI workflows (direct text access for summaries, keywords, embeddings).
  - Easy to edit and collaborate on (Livewire can update `content` in real-time).
  - Supports Markdown for rich formatting without files.
  - Efficient for database-driven search (e.g., MySQL full-text index or Scout).
- **Cons**:
  - Limited formatting (no images, tables, or complex layouts unless using advanced Markdown).
  - Large text content can bloat the database, impacting performance.
  - Generating PDFs for download requires extra processing (e.g., `DomPDF`).
- **Implementation**:
  - Use a rich text editor (e.g., Trix) or Markdown editor in a Livewire component (`app/Livewire/Dashboard/UploadNote.php`).
  - Store text in `content`; dispatch `GenerateNoteAi` job for AI metadata.
  - Example:
    ```php
    // app/Livewire/Dashboard/UploadNote.php
    <?php
    use function Livewire\Volt\{component};
    use App\Models\Note;
    use App\Jobs\GenerateNoteAi;

    component(function () {
        public $title = '';
        public $content = '';
        public $visibility = 'public';

        public function save()
        {
            $note = Note::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'content' => $this->content,
                'visibility' => $this->visibility,
            ]);
            GenerateNoteAi::dispatch($note);
            session()->flash('message', 'Note saved!');
        }
    });
    ?>
    ```

### When to Use PDF (`file_path` Column)
- **Best For**:
  - Users uploading pre-formatted documents (e.g., study guides, reports).
  - Scenarios requiring rich formatting (images, tables, custom fonts).
  - Sharing notes as downloadable files without modification.
  - Archival purposes where the original document format matters.
- **Pros**:
  - Supports complex formatting (PDFs can include images, charts, etc.).
  - Scalable storage with cloud services (e.g., AWS S3).
  - Users can upload existing PDFs, preserving their work.
  - Direct download without conversion.
- **Cons**:
  - Requires PDF parsing for AI features (adds complexity and latency).
  - Collaboration is harder (needs text extraction or external tools).
  - Displaying PDFs in the browser requires a viewer (e.g., PDF.js).
  - Security risks (malicious PDFs); requires validation/scanning.
- **Implementation**:
  - Use Laravel’s file upload in a Livewire component.
  - Store files in `storage/app/public/notes/` or S3.
  - Extract text for AI/search using `Smalot\PdfParser` or an API.
  - Example:
    ```php
    // app/Livewire/Dashboard/UploadNote.php
    <?php
    use function Livewire\Volt\{component};
    use App\Models\Note;
    use App\Jobs\GenerateNoteAi;
    use Illuminate\Support\Facades\Storage;

    component(function () {
        public $title = '';
        public $file = null;
        public $visibility = 'public';

        public function save()
        {
            $this->validate([
                'title' => 'required|string|max:255',
                'file' => 'required|file|mimes:pdf|max:10240', // 10MB max
            ]);

            $path = $this->file->store('notes', 'public');
            $note = Note::create([
                'user_id' => auth()->id(),
                'title' => $this->title,
                'file_path' => $path,
                'visibility' => $this->visibility,
            ]);
            GenerateNoteAi::dispatch($note);
            session()->flash('message', 'Note uploaded!');
        }
    });
    ?>
    ```

### Hybrid Approach
- **Description**: Allow both text and PDF uploads, using `content` for text notes and `file_path` for PDFs. Extract text from PDFs to populate `content` for AI/search.
- **Pros**:
  - Flexibility: Users can choose their preferred format.
  - AI/search works consistently (text in `content` for both).
  - Supports both simple notes and complex documents.
- **Cons**:
  - More complex backend (handle both text and file uploads).
  - PDF text extraction may not always be perfect (e.g., scanned PDFs).
- **Implementation**:
  - Modify `UploadNote` component to accept either text or file.
  - Extract PDF text in `GenerateNoteAi` job:
    ```php
    // app/Jobs/GenerateNoteAi.php
    public function handle(AiService $aiService)
    {
        $content = $this->note->content;
        if ($this->note->file_path && !$content) {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile(storage_path('app/' . $this->note->file_path));
            $content = $pdf->getText();
            $this->note->update(['content' => $content]);
        }
        $metadata = $aiService->generateMetadata($content);
        $this->note->noteAi()->create([
            'summary' => $metadata['summary'],
            'keywords' => $metadata['keywords'],
            'embedding' => $metadata['embedding'],
            'topics' => $metadata['topics'],
            'generated_by' => 'ai_service',
        ]);
    }
    ```

---

## 🏆 Recommendation: Hybrid Approach

For your AI-powered note-sharing platform, the **hybrid approach** is likely the best choice, as it balances flexibility, usability, and technical feasibility. Here’s why:

1. **User Flexibility**:
   - Some users may prefer typing notes directly (e.g., for quick notes or collaborative editing), which suits the `content` column.
   - Others may want to upload pre-formatted PDFs (e.g., lecture slides, study guides), leveraging `file_path`.

2. **AI and Search**:
   - Storing extracted text in `content` ensures AI features (summaries, keywords, embeddings, Q&A) work seamlessly for both text and PDF notes.
   - Use Laravel Scout or PostgreSQL pgvector for search, relying on `content` for full-text or semantic queries.

3. **Collaboration**:
   - Text in `content` enables real-time editing via Livewire (e.g., for collaborations).
   - PDFs can be shared but are harder to edit collaboratively unless converted to text.

4. **Scalability**:
   - Store PDFs in S3 for scalability, keeping `file_path` lean.
   - Use `content` for smaller notes or extracted PDF text to avoid DB bloat.

5. **Implementation**:
   - Add a form in `app/Livewire/Dashboard/UploadNote.php` with options for text or file upload.
   - Use `Smalot\PdfParser` or an API (e.g., AWS Textract) to extract text from PDFs.
   - Store extracted text in `content` for AI/search, and keep `file_path` for the original PDF.
   - Example schema usage:
     - Text note: `content` = "My lecture notes...", `file_path` = `NULL`.
     - PDF note: `content` = (extracted text), `file_path` = "notes/note_123.pdf".
   - Use `DomPDF` to generate downloadable PDFs from text notes if needed.

---

## 🛠️ Additional Considerations

- **Libraries**:
  - For PDF parsing: `composer require smalot/pdf-parser`.
  - For PDF generation: `composer require barryvdh/laravel-dompdf`.
  - For file storage: Configure `config/filesystems.php` for S3 (`spatie/flysystem-aws-s3-v3`).
- **Security**:
  - Validate file uploads: `mimes:pdf|max:10240` (10MB limit).
  - Sanitize text input: Use `laravel-dompurify` for `content`.
  - Scan PDFs for malware (e.g., via ClamAV or an external service).
- **Performance**:
  - Cache AI metadata (`note_ai` table) to avoid repeated API calls.
  - Use queue jobs (`GenerateNoteAi`) for PDF parsing and AI processing.
  - Index `content` with Scout or MySQL full-text for fast search.
- **Display**:
  - Text notes: Render with Markdown (`michelf/php-markdown`) or a rich text editor.
  - PDFs: Use `<embed src="{{ Storage::url($note->file_path) }}" />` or PDF.js for in-browser viewing.
- **DB Optimization**:
  - If `content` grows large, consider moving it to a separate table or using a blob storage solution.
  - Use `deleted_at` (soft deletes) for notes to allow recovery.

---

## 📦 Example Code: Hybrid Upload Component

Here’s a sample Livewire Volt component for uploading both text and PDF notes:

```php
// app/Livewire/Dashboard/UploadNote.php
<?php
use function Livewire\Volt\{component};
use App\Models\Note;
use App\Jobs\GenerateNoteAi;
use Illuminate\Support\Facades\Storage;

component(function () {
    public $title = '';
    public $content = '';
    public $file = null;
    public $visibility = 'public';
    public $type = 'text'; // 'text' or 'pdf'

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required_if:type,text|string|nullable',
        'file' => 'required_if:type,pdf|file|mimes:pdf|max:10240',
        'visibility' => 'required|in:public,private,friends',
    ];

    public function save()
    {
        $this->validate();

        $noteData = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'visibility' => $this->visibility,
        ];

        if ($this->type === 'pdf' && $this->file) {
            $noteData['file_path'] = $this->file->store('notes', 'public');
        } else {
            $noteData['content'] = $this->content;
        }

        $note = Note::create($noteData);
        GenerateNoteAi::dispatch($note);

        session()->flash('message', 'Note saved successfully!');
        $this->reset();
    }
});
?>
<!-- Blade template (simplified) -->
<div>
    <form wire:submit="save">
        <input type="text" wire:model="title" placeholder="Note Title" />
        <select wire:model="type">
            <option value="text">Text</option>
            <option value="pdf">PDF</option>
        </select>
        @if ($type === 'text')
            <textarea wire:model="content" placeholder="Enter note content"></textarea>
        @else
            <input type="file" wire:model="file" accept=".pdf" />
        @endif
        <select wire:model="visibility">
            <option value="public">Public</option>
            <option value="private">Private</option>
            <option value="friends">Friends</option>
        </select>
        <button type="submit">Save Note</button>
    </form>
</div>
```

---

## ✅ Final Answer

The **hybrid approach**—allowing notes to be stored as **text** in the `content` column or as **PDFs** in the `file_path` column, with extracted text in `content` for PDFs—is the best solution for your platform. It supports diverse user needs (text for quick notes/collaboration, PDFs for formatted documents), ensures AI/search functionality (via `content`), and is scalable with proper file storage (S3). Implement this with:
- A Livewire Volt component for dual uploads.
- PDF text extraction in the `GenerateNoteAi` job.
- Security and performance optimizations (validation, caching, indexing).

If you need further details (e.g., specific code for PDF parsing, search implementation, or a Livewire component), let me know!
Below, I'll provide the **Laravel migrations** and **Eloquent models** for the database schema you shared, along with additional classes to support the AI-powered note-sharing platform. I'll also include some essential supporting classes (e.g., services, jobs, and policies) to make the system functional and scalable. The structure assumes Laravel 11.x with Livewire Volt and follows best practices for modularity and maintainability.

---

## 📂 File Structure (Updated)

To accommodate migrations, models, and additional classes, here's the relevant part of the updated file structure:

```
project-root/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │       ├── NoteUploadRequest.php      # Form validation for note uploads
│   │       ├── ExamCreateRequest.php      # Validation for exam creation
│   │       └── ProfileUpdateRequest.php   # Validation for profile updates
│   ├── Jobs/
│   │   ├── GenerateNoteAi.php            # Queue job for AI processing
│   │   └── CleanupExpired.php            # Job to clean expired collaborations/exams
│   ├── Livewire/                         # Volt components (as before)
│   ├── Models/
│   │   ├── Bookmark.php
│   │   ├── Collaboration.php
│   │   ├── Exam.php
│   │   ├── ExamAnswer.php
│   │   ├── ExamQuestion.php
│   │   ├── ExamSubmission.php
│   │   ├── Follower.php
│   │   ├── Note.php
│   │   ├── NoteAi.php
│   │   ├── Profile.php
│   │   ├── Tag.php
│   │   └── User.php
│   ├── Policies/
│   │   ├── NotePolicy.php                # Authorization for notes
│   │   ├── ExamPolicy.php                # Authorization for exams
│   │   └── CollaborationPolicy.php       # Authorization for collaborations
│   ├── Services/
│   │   ├── AiService.php                 # Handles AI API calls
│   │   └── SearchService.php             # Manages search (Scout/vector)
│   └── Traits/
│   │   └── HasFollowers.php              # Reusable follower logic
├── database/
│   ├── migrations/
│   │   ├── 2025_08_29_000001_create_users_table.php
│   │   ├── 2025_08_29_000002_create_profiles_table.php
│   │   ├── 2025_08_29_000003_create_followers_table.php
│   │   ├── 2025_08_29_000004_create_notes_table.php
│   │   ├── 2025_08_29_000005_create_note_ai_table.php
│   │   ├── 2025_08_29_000006_create_tags_table.php
│   │   ├── 2025_08_29_000007_create_note_tag_table.php
│   │   ├── 2025_08_29_000008_create_bookmarks_table.php
│   │   ├── 2025_08_29_000009_create_collaborations_table.php
│   │   ├── 2025_08_29_000010_create_exams_table.php
│   │   ├── 2025_08_29_000011_create_exam_questions_table.php
│   │   ├── 2025_08_29_000012_create_exam_submissions_table.php
│   │   ├── 2025_08_29_000013_create_exam_answers_table.php
│   └── seeders/
├── config/
│   └── app.php                          # Add AI API keys, queue settings
├── routes/
│   └── web.php                          # Routes for Livewire components
└── composer.json                         # Dependencies
```

---

## 🗄️ Migrations

Below are the Laravel migration files for each table, using `Schema::create` and following the schema you provided. Run `php artisan make:migration create_<table>_table` for each, then add the code below to the generated files.

### 1. `database/migrations/2025_08_29_000001_create_users_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### 2. `database/migrations/2025_08_29_000002_create_profiles_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('username', 150)->unique();
            $table->string('avatar', 255)->nullable();
            $table->string('cover_photo', 255)->nullable();
            $table->text('bio')->nullable();
            $table->string('location', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_say'])->nullable();
            $table->json('social_links')->nullable();
            $table->integer('followers_count')->default(0);
            $table->integer('following_count')->default(0);
            $table->integer('notes_count')->default(0);
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->string('language', 10)->default('en');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
```

### 3. `database/migrations/2025_08_29_000003_create_followers_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('followed_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['follower_id', 'followed_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
```

### 4. `database/migrations/2025_08_29_000004_create_notes_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->longText('content')->nullable();
            $table->string('file_path', 255)->nullable();
            $table->enum('visibility', ['public', 'private', 'friends'])->default('public');
            $table->integer('downloads_count')->default(0);
            $table->integer('ratings_sum')->default(0);
            $table->integer('ratings_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('visibility');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
```

### 5. `database/migrations/2025_08_29_000005_create_note_ai_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('note_ai', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->longText('summary')->nullable();
            $table->json('keywords')->nullable();
            $table->json('embedding')->nullable();
            $table->json('topics')->nullable();
            $table->json('qa_cache')->nullable();
            $table->string('generated_by', 100)->nullable();
            $table->timestamps();
            $table->index('note_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_ai');
    }
};
```

### 6. `database/migrations/2025_08_29_000006_create_tags_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
```

### 7. `database/migrations/2025_08_29_000007_create_note_tag_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('note_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->unique(['note_id', 'tag_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_tag');
    }
};
```

### 8. `database/migrations/2025_08_29_000008_create_bookmarks_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
```

### 9. `database/migrations/2025_08_29_000009_create_collaborations_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collaborations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('collaborator_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['editor', 'viewer'])->default('editor');
            $table->enum('status', ['active', 'ended'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
};
```

### 10. `database/migrations/2025_08_29_000010_create_exams_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('instructions')->nullable();
            $table->integer('duration')->nullable();
            $table->enum('status', ['draft', 'active', 'ended'])->default('draft');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
```

### 11. `database/migrations/2025_08_29_000011_create_exam_questions_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['mcq', 'true_false', 'short_answer'])->default('mcq');
            $table->json('options')->nullable();
            $table->json('answer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
```

### 12. `database/migrations/2025_08_29_000012_create_exam_submissions_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_submissions');
    }
};
```

### 13. `database/migrations/2025_08_29_000013_create_exam_answers_table.php`

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('submission_id')->constrained('exam_submissions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->json('user_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
    }
};
```

Run `php artisan migrate` to apply these migrations. Use `php artisan migrate:rollback` to undo.

---

## 🛠️ Models

Below are the Eloquent models with relationships, casts, and scopes. Run `php artisan make:model <ModelName> -m` to generate each model with its migration (though migrations are already provided). Models use traits like `SoftDeletes` where applicable and define relationships (e.g., `hasMany`, `belongsTo`).

### 1. `app/Models/User.php`

```php
<?php
namespace App\Models;

use App\Traits\HasFollowers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasFollowers;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function collaborations()
    {
        return $this->hasMany(Collaboration::class, 'owner_id');
    }

    public function collaboratorIn()
    {
        return $this->hasMany(Collaboration::class, 'collaborator_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'owner_id');
    }

    public function examSubmissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }
}
```

### 2. `app/Models/Profile.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'username', 'avatar', 'cover_photo', 'bio', 'location',
        'website', 'birthday', 'gender', 'social_links', 'visibility', 'theme', 'language',
    ];

    protected $casts = [
        'social_links' => 'array',
        'birthday' => 'date',
        'visibility' => 'string',
        'theme' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### 3. `app/Models/Follower.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $fillable = ['follower_id', 'followed_id'];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
```

### 4. `app/Models/Note.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'content', 'file_path', 'visibility'];

    protected $casts = [
        'visibility' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function noteAi()
    {
        return $this->hasOne(NoteAi::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'note_tag');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function collaborations()
    {
        return $this->hasMany(Collaboration::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
              ->orWhere(function ($q) use ($user) {
                  $q->where('visibility', 'friends')
                    ->whereIn('user_id', $user->following()->pluck('followed_id'));
              })
              ->orWhere('user_id', $user->id);
        });
    }
}
```

### 5. `app/Models/NoteAi.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteAi extends Model
{
    protected $fillable = ['note_id', 'summary', 'keywords', 'embedding', 'topics', 'qa_cache', 'generated_by'];

    protected $casts = [
        'keywords' => 'array',
        'embedding' => 'array',
        'topics' => 'array',
        'qa_cache' => 'array',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
```

### 6. `app/Models/Tag.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function notes()
    {
        return $this->belongsToMany(Note::class, 'note_tag');
    }
}
```

### 7. `app/Models/Bookmark.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'note_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
```

### 8. `app/Models/Collaboration.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $fillable = ['note_id', 'owner_id', 'collaborator_id', 'role', 'status', 'expires_at'];

    protected $casts = [
        'role' => 'string',
        'status' => 'string',
        'expires_at' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function collaborator()
    {
        return $this->belongsTo(User::class, 'collaborator_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }
}
```

### 9. `app/Models/Exam.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['note_id', 'owner_id', 'title', 'instructions', 'duration', 'status', 'expires_at'];

    protected $casts = [
        'status' => 'string',
        'expires_at' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }
}
```

### 10. `app/Models/ExamQuestion.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = ['exam_id', 'question_text', 'question_type', 'options', 'answer'];

    protected $casts = [
        'question_type' => 'string',
        'options' => 'array',
        'answer' => 'array',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
```

### 11. `app/Models/ExamSubmission.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    protected $fillable = ['exam_id', 'user_id', 'started_at', 'submitted_at', 'score'];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'float',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
```

### 12. `app/Models/ExamAnswer.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    protected $fillable = ['submission_id', 'question_id', 'user_answer', 'is_correct'];

    protected $casts = [
        'user_answer' => 'array',
        'is_correct' => 'boolean',
    ];

    public function submission()
    {
        return $this->belongsTo(ExamSubmission::class);
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class);
    }
}
```

---

## 🧩 Additional Classes

Below are additional classes to enhance the system, including a trait for follower logic, a service for AI processing, a job for async AI tasks, and policies for authorization.

### 1. `app/Traits/HasFollowers.php`

Reusable trait for follower functionality.

```php
<?php
namespace App\Traits;

use App\Models\Follower;
use App\Models\User;

trait HasFollowers
{
    public function followers()
    {
        return $this->hasMany(Follower::class, 'followed_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function follow(User $user)
    {
        if ($this->id !== $user->id && !$this->isFollowing($user)) {
            Follower::create([
                'follower_id' => $this->id,
                'followed_id' => $user->id,
            ]);
            $user->profile->increment('followers_count');
            $this->profile->increment('following_count');
        }
    }

    public function unfollow(User $user)
    {
        $follow = Follower::where('follower_id', $this->id)
                          ->where('followed_id', $user->id)
                          ->first();
        if ($follow) {
            $follow->delete();
            $user->profile->decrement('followers_count');
            $this->profile->decrement('following_count');
        }
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }
}
```

### 2. `app/Services/AiService.php`

Handles AI API calls (e.g., OpenAI/Hugging Face for summaries, embeddings, etc.).

```php
<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\NoteAi;

class AiService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.ai.api_key');
        $this->endpoint = config('services.ai.endpoint');
    }

    public function generateMetadata($content)
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->endpoint}/generate", [
                'content' => $content,
                'tasks' => ['summary', 'keywords', 'embedding', 'topics'],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('AI API request failed');
    }

    public function generateExamQuestions($noteId, $content, $count = 5)
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->endpoint}/questions", [
                'content' => $content,
                'count' => $count,
                'type' => ['mcq', 'true_false', 'short_answer'],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('AI question generation failed');
    }

    public function answerQuestion($noteId, $question)
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->endpoint}/qa", [
                'note_id' => $noteId,
                'question' => $question,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('AI Q&A failed');
    }
}
```

Add to `config/services.php`:

```php
'ai' => [
    'api_key' => env('AI_API_KEY'),
    'endpoint' => env('AI_API_ENDPOINT', 'https://api.example.com'),
],
```

### 3. `app/Jobs/GenerateNoteAi.php`

Queue job for async AI metadata generation.

```php
<?php
namespace App\Jobs;

use App\Models\Note;
use App\Services\AiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateNoteAi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function handle(AiService $aiService)
    {
        $content = $this->note->content ?? file_get_contents(storage_path('app/' . $this->note->file_path));
        $metadata = $aiService->generateMetadata($content);

        $this->note->noteAi()->create([
            'summary' => $metadata['summary'],
            'keywords' => $metadata['keywords'],
            'embedding' => $metadata['embedding'],
            'topics' => $metadata['topics'],
            'generated_by' => 'ai_service',
        ]);
    }
}
```

### 4. `app/Jobs/CleanupExpired.php`

Job to clean up expired collaborations and exams.

```php
<?php
namespace App\Jobs;

use App\Models\Collaboration;
use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class CleanupExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function handle()
    {
        Collaboration::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'ended']);

        Exam::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update(['status' => 'ended']);
    }
}
```

Schedule in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->job(new CleanupExpired)->daily();
}
```

### 5. `app/Policies/NotePolicy.php`

Authorization for notes (e.g., view, edit, delete).

```php
<?php
namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Note $note)
    {
        if ($note->visibility === 'public') {
            return true;
        }

        if ($note->visibility === 'private' && $note->user_id === $user->id) {
            return true;
        }

        if ($note->visibility === 'friends' && $user->isFollowing($note->user)) {
            return true;
        }

        return false;
    }

    public function update(User $user, Note $note)
    {
        return $note->user_id === $user->id || $note->collaborations()
            ->where('collaborator_id', $user->id)
            ->where('role', 'editor')
            ->where('status', 'active')
            ->exists();
    }

    public function delete(User $user, Note $note)
    {
        return $note->user_id === $user->id;
    }
}
```

### 6. `app/Policies/ExamPolicy.php`

Authorization for exams.

```php
<?php
namespace App\Policies;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Exam $exam)
    {
        return $exam->status === 'active' && $exam->note->visibility === 'public' || $exam->owner_id === $user->id;
    }

    public function update(User $user, Exam $exam)
    {
        return $exam->owner_id === $user->id;
    }

    public function delete(User $user, Exam $exam)
    {
        return $exam->owner_id === $user->id;
    }

    public function take(User $user, Exam $exam)
    {
        return $exam->status === 'active' && !$exam->submissions()->where('user_id', $user->id)->exists();
    }
}
```

### 7. `app/Policies/CollaborationPolicy.php`

Authorization for collaborations.

```php
<?php
namespace App\Policies;

use App\Models\Collaboration;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollaborationPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Collaboration $collaboration)
    {
        return $collaboration->owner_id === $user->id || $collaboration->collaborator_id === $user->id;
    }

    public function update(User $user, Collaboration $collaboration)
    {
        return $collaboration->owner_id === $user->id;
    }

    public function delete(User $user, Collaboration $collaboration)
    {
        return $collaboration->owner_id === $user->id;
    }
}
```

Register policies in `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    Note::class => NotePolicy::class,
    Exam::class => ExamPolicy::class,
    Collaboration::class => CollaborationPolicy::class,
];
```

### 8. `app/Services/SearchService.php`

Handles note search with Scout or vector search.

```php
<?php
namespace App\Services;

use App\Models\Note;
use Laravel\Scout\Searchable;

class SearchService
{
    public function search($query, $user = null)
    {
        $search = Note::search($query)
            ->where('visibility', 'public');

        if ($user) {
            $search->orWhere(function ($q) use ($user) {
                $q->where('visibility', 'friends')
                  ->whereIn('user_id', $user->following()->pluck('followed_id'))
                  ->orWhere('user_id', $user->id);
            });
        }

        return $search->get();
    }

    public function semanticSearch($query, $user = null)
    {
        // Example: Use embeddings for vector search (requires pgvector or similar)
        // This is a placeholder; implement with actual vector DB
        $embedding = app(AiService::class)->generateMetadata($query)['embedding'];
        return Note::whereRaw('embedding <-> ?', [$embedding])
            ->where('visibility', 'public')
            ->when($user, fn($q) => $q->orWhere('user_id', $user->id))
            ->get();
    }
}
```

---

## 🛠️ Setup Instructions

1. **Install Laravel**: `composer create-project laravel/laravel project-name`.
2. **Install Dependencies**:
   - `composer require livewire/livewire livewire/volt laravel/scout`.
   - Optionally: `laravel/breeze`, `laravel/socialite`, `spatie/laravel-permission`.
3. **Run Migrations**: `php artisan migrate`.
4. **Setup Scout**: Configure `config/scout.php` for Meilisearch/Algolia or pgvector for vector search.
5. **Queue Setup**: Configure `config/queue.php` (e.g., Redis) and run `php artisan queue:work`.
6. **AI API**: Add API keys to `.env` (e.g., `AI_API_KEY=your-key`).
7. **Livewire Volt**: `php artisan livewire:install` and `php artisan volt:install`.

---

## 📝 Notes

- **AI Integration**: The `AiService` assumes an external API. Replace with actual endpoints (e.g., OpenAI). For vector search, use PostgreSQL with pgvector or a service like Pinecone.
- **Livewire Components**: Create Volt components in `app/Livewire/` (e.g., `UploadNote.php`) using `volt()` syntax. Example:
  ```php
  <?php
  use function Livewire\Volt\{component};
  component(function () {
      public $title = '';
      public function save() {
          $note = Note::create(['title' => $this->title, 'user_id' => auth()->id()]);
          GenerateNoteAi::dispatch($note);
      }
  });
  ?>
  ```
- **Testing**: Use `php artisan make:test` for unit/feature tests. Example: Test `NotePolicy` or `AiService`.
- **Scalability**: Use Redis for caching profiles/notes. Horizon for queue monitoring. CDN for file storage (S3).

If you need specific code for a Livewire component, controller, or additional features (e.g., seeding, testing), let me know!

## 🚀 Alternatives to Pusher for Real-Time Features in Laravel

Based on the context of your AI-powered note-sharing platform built with Laravel + Livewire Volt, you're likely referring to Pusher's limitations in the freemium model—such as its free tier caps (e.g., 100 concurrent connections and 200k daily messages as of 2025). Pusher is excellent for broadcasting events (e.g., real-time collaborations, notifications, or live updates in exams/notes), but its pricing can add up quickly for scaling users. Fortunately, Laravel's broadcasting system is flexible and supports several free or self-hosted alternatives that integrate seamlessly with Laravel Echo (the client-side listener) and your existing setup.

I'll outline the best options "for now" (i.e., immediate, low-cost implementations without major refactoring), prioritized by ease of use, cost (focusing on free/self-hosted), and compatibility with Laravel 11.x (current as of August 29, 2025). These can handle features like temporary collaborations (real-time editing) or notifications for bookmarks/shares. All are WebSocket-based for efficient real-time communication, avoiding polling.

### Key Considerations for Your Project
- **Integration**: Use Laravel's `BROADCAST_DRIVER` in `.env` (e.g., `BROADCAST_DRIVER=reverb`). Most alternatives work with Pusher-compatible APIs, so you can swap with minimal code changes (e.g., update `config/broadcasting.php`).
- **Freemium Fit**: Self-hosted options have no external limits but require server resources (e.g., on your VPS or shared hosting). For freemium users, enforce limits via your app logic (e.g., queue events or cap connections per user).
- **Setup**: All require running a queue worker (`php artisan queue:work`) for broadcasting. Use Laravel Horizon for monitoring.
- **Client-Side**: Stick with Laravel Echo; configure it to point to your alternative's host/port.
- **Scalability**: For production, deploy on a server with Redis (for pub/sub) if needed. Start local for dev.

### Recommended Alternatives (Free/Self-Hosted Focus)

Here's a comparison table of top free alternatives, based on current (2025) community recommendations and Laravel docs. I prioritized options that are actively maintained, Pusher-compatible, and suitable for your note-sharing use case (e.g., low-to-medium traffic for freemium users).

| **Alternative** | **Description** | **Pros** | **Cons** | **Cost** | **Ease of Setup in Laravel** | **Best For Your Project** |
|-----------------|-----------------|----------|----------|----------|------------------------------|---------------------------|
| **Laravel Reverb** (First-Party) | Laravel's official WebSocket server (introduced in Laravel 11, 2024). Handles broadcasting natively without external services. | - Free & self-hosted.<br>- Seamless integration (no extra packages).<br>- Supports presence channels (e.g., for collaborations).<br>- Scalable with Redis for horizontal scaling.<br>- No message/connection limits (server-dependent). | - Requires running a separate server process (`php artisan reverb:start`).<br>- Newer, so community resources are growing. | Free (self-hosted; server costs ~$5-10/month on basic VPS). | Very easy: `composer require laravel/reverb`, update `.env` and `config/broadcasting.php`, run the server. | **Top recommendation**: Ideal for your platform—use for real-time note editing/collaborations without Pusher fees. Handles freemium scaling well. |
| **Soketi** | Open-source, self-hosted Pusher alternative (Node.js-based, actively maintained in 2025). Uses Redis for pub/sub. | - Fully Pusher-compatible (drop-in replacement).<br>- High performance (handles 1M+ connections).<br>- Docker support for easy deployment.<br>- Free with no limits. | - Requires Node.js/Redis setup.<br>- Separate server to run. | Free (self-hosted). | Easy: `npm install -g @soketi/soketi`, configure as Pusher in `config/broadcasting.php` (host: 127.0.0.1, port: 6001). Integrates with Laravel Echo. | Great for immediate Pusher swap in collaborations/exams. Use if you want Pusher-like API without changes. |
| **Laravel WebSockets** (beyondcode/laravel-websockets) | Laravel package providing a Pusher-compatible WebSocket server. Includes a dashboard for debugging. | - Pusher API parity (e.g., multi-tenancy for apps).<br>- Built-in stats and client events (e.g., typing indicators in notes).<br>- Easy debugging UI. | - Less actively maintained than Soketi (last major update ~2023, but stable).<br>- Can have integration issues in complex setups. | Free (self-hosted). | Moderate: `composer require beyondcode/laravel-websockets`, publish config, run `php artisan websockets:serve`. Update Echo to wsHost: 127.0.0.1, wsPort: 6001. | Good for dev/testing real-time features like bookmarks/notifications. Avoid for high-scale freemium if Soketi is viable. |
| **Redis (with Laravel Echo Server or Socket.io)** | Use Redis pub/sub as the driver, with a Socket.io server for client connections. | - Built into Laravel (no extra cost).<br>- Highly scalable if you already use Redis (e.g., for queues).<br>- Free and reliable for basic real-time. | - Needs a separate Socket.io server (e.g., laravel-echo-server, which is unmaintained; use Soketi instead).<br>- More setup for full Pusher compatibility. | Free (requires Redis server). | Moderate: Set `BROADCAST_DRIVER=redis` in `.env`, install Redis, run a Socket.io bridge. | Fallback if you have Redis; suitable for simple notifications in your dashboard. |
| **Supabase (Free Tier)** | Open-source Firebase alternative with real-time Postgres subscriptions. | - Free tier: 500MB DB, 1GB storage, real-time channels.<br>- Handles auth/notifications out-of-box.<br>- No self-hosting needed initially. | - Not purely WebSocket (uses Postgres changes).<br>- Limits: 200k messages/month on free plan.<br>- Less Laravel-specific. | Free tier available; scales to paid. | Moderate: `composer require supabase/supabase-php`, configure broadcasting driver. Use with Echo for subscriptions. | If you want a hosted free option for freemium users (e.g., real-time Q&A in exams). Self-host for unlimited. |

### 🛠️ Step-by-Step Setup for Top Recommendation: Laravel Reverb
Since Reverb is Laravel-native and eliminates Pusher dependency entirely, start here. It's perfect "for now" as it's free, quick to implement, and scales with your freemium model.

1. **Install**:
   ```
   composer require laravel/reverb
   php artisan install:broadcasting
   ```

2. **Configure** (in `.env`):
   ```
   BROADCAST_DRIVER=reverb
   REVERB_APP_ID=your-app-id
   REVERB_APP_KEY=your-app-key
   REVERB_APP_SECRET=your-app-secret
   REVERB_HOST="localhost"
   REVERB_PORT=8080
   REVERB_SCHEME=http

   VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
   VITE_REVERB_HOST="${REVERB_HOST}"
   VITE_REVERB_PORT="${REVERB_PORT}"
   VITE_REVERB_SCHEME="${REVERB_SCHEME}"
   ```

3. **Update Broadcasting Config** (`config/broadcasting.php`):
   ```php
   'reverb' => [
       'driver' => 'reverb',
       'key' => env('REVERB_APP_KEY'),
       'secret' => env('REVERB_APP_SECRET'),
       'app_id' => env('REVERB_APP_ID'),
       'options' => [
           'host' => env('REVERB_HOST', 'localhost'),
           'port' => env('REVERB_PORT', 8080),
           'scheme' => env('REVERB_SCHEME', 'http'),
       ],
   ],
   ```

4. **Client-Side (Laravel Echo in `resources/js/bootstrap.js`)**:
   ```javascript
   import Echo from 'laravel-echo';
   import Pusher from 'pusher-js';

   window.Pusher = Pusher;

   window.Echo = new Echo({
       broadcaster: 'reverb',
       key: import.meta.env.VITE_REVERB_APP_KEY,
       wsHost: import.meta.env.VITE_REVERB_HOST,
       wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
       wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
       forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
       enabledTransports: ['ws', 'wss'],
   });
   ```

5. **Run the Server**:
   - Dev: `php artisan reverb:start --debug`
   - Production: Use Supervisor to daemonize (`php artisan reverb:start`).
   - For scaling: Add Redis (`REVERB_REDIS_CONNECTION=redis` in `.env`).

6. **Usage in Your Project**:
   - Broadcast events (e.g., for collaborations): In a Livewire component or job, `broadcast(new NoteUpdated($note));`.
   - Listen in Volt/Livewire: Use Echo in JS (e.g., `Echo.private('note.' + noteId).listen('NoteUpdated', (e) => { /* Update UI */ });`).
   - For freemium: Track connections via Reverb's stats; limit via middleware (e.g., rate-limit events per user).

7. **Test**: Create a route to fire an event, then listen in your dashboard. No Pusher keys needed!

### Other Quick Wins
- **If Self-Hosting Isn't Ready**: Use Supabase's free tier for hosted real-time (integrate via their PHP client). It's Pusher-like but with DB syncing—great for note updates.
- **Fallback for Dev**: Laravel's `log` driver (`BROADCAST_DRIVER=log`) for testing without real-time.
- **Monitoring**: Use Laravel Pulse or Reverb's built-in dashboard to track usage in freemium.
- **Migration from Pusher**: If switching mid-project, update Echo config to use the new host/port—events remain the same.

These options keep costs at zero while enabling real-time features like live collaborations or exam updates. Reverb is the most future-proof for Laravel. If your "limitations" refer to something else (e.g., specific features), provide more details for tailored advice!