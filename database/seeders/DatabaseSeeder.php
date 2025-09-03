<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Follower;
use App\Models\Note;
use App\Models\NoteAi;
use App\Models\Tag;
use App\Models\NoteTag;
use App\Models\Bookmark;
use App\Models\Collaboration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Create Roles and Permissions (Spatie)
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $permissions = [
            'create notes',
            'edit notes',
            'delete notes',
            'view notes',
            'manage collaborations',
            'view analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole->givePermissionTo($permissions);
        $userRole->givePermissionTo(['create notes', 'edit notes', 'view notes', 'manage collaborations']);

        // 2. Create Users (20)
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = User::create([
                'name' => htmlspecialchars($faker->name, ENT_QUOTES, 'UTF-8'),
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'email_verified_at' => $faker->dateTimeThisYear,
                'remember_token' => Str::random(10),
            ]);

            $user->assignRole($i < 2 ? 'admin' : 'user');

            Profile::create([
                'user_id' => $user->id,
                'username' => $faker->unique()->userName,
                'avatar' => $faker->imageUrl(200, 200, 'people'),
                'cover_photo' => $faker->imageUrl(1200, 400, 'nature'),
                'bio' => htmlspecialchars($faker->sentence(15) . ' <b>Passionate learner!</b>', ENT_QUOTES, 'UTF-8'),
                'location' => htmlspecialchars($faker->city . ' & Beyond', ENT_QUOTES, 'UTF-8'),
                'website' => htmlspecialchars($faker->url, ENT_QUOTES, 'UTF-8'),
                'birthday' => $faker->dateTimeBetween('-30 years', '-18 years'),
                'gender' => $faker->randomElement(['male', 'female']),
                'social_links' => json_encode([
                    'twitter' => htmlspecialchars($faker->url, ENT_QUOTES, 'UTF-8'),
                    'linkedin' => htmlspecialchars($faker->url, ENT_QUOTES, 'UTF-8'),
                ]),
                'followers_count' => 0,
                'following_count' => 0,
                'notes_count' => 0,
                'visibility' => $faker->randomElement(['public', 'private']),
                'theme' => $faker->randomElement(['light', 'dark']),
                'language' => $faker->randomElement(['en', 'es', 'fr']),
            ]);

            $users[] = $user;
        }

        // 3. Create Followers (20 relationships)
        for ($i = 0; $i < 20; $i++) {
            $follower = $users[$faker->numberBetween(0, 19)];
            $followed = $users[$faker->numberBetween(0, 19)];

            if ($follower->id !== $followed->id) {
                Follower::create([
                    'follower_id' => $follower->id,
                    'followed_id' => $followed->id,
                ]);

                $followed->profile->increment('followers_count');
                $follower->profile->increment('following_count');
            }
        }

        // 4. Create Tags (20)
        $tagNames = [
            'Mathematics', 'Physics', 'Chemistry', 'Biology', 'History', 'Literature',
            'Computer Science', 'Economics', 'Psychology', 'Philosophy', 'Art',
            'Music', 'Engineering', 'Statistics', 'Calculus', 'Algebra', 'Geometry',
            'Sociology', 'Political Science', 'Environmental Science'
        ];
        $tags = [];
        foreach ($tagNames as $name) {
            $tag = Tag::create([
                'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'slug' => Str::slug($name),
            ]);
            $tags[] = $tag;
        }

        // 5. Create Notes (20 text-based notes with longer HTML content)
        $notes = [];
        for ($i = 0; $i < 20; $i++) {
            $user = $users[$faker->numberBetween(0, 19)];
            $rawTitle = $faker->sentence(5) . ' <b>Comprehensive Study Guide</b>';

            // Generate longer HTML content (~10-20 KB)
            $rawContent = '<h1>' . $faker->sentence(4) . '</h1>' .
                          '<p>' . implode(' ', array_fill(0, 5, $faker->paragraph(10))) . ' & <strong>key concept</strong></p>' .
                          '<h2>Key Concepts</h2>' .
                          '<ul>' .
                          implode('', array_map(function () use ($faker) {
                              return '<li>' . $faker->sentence(8) . ' & more</li>';
                          }, range(1, 10))) . // 10 list items
                          '</ul>' .
                          '<h2>Summary</h2>' .
                          '<p>' . implode(' ', array_fill(0, 3, $faker->paragraph(15))) . '</p>' .
                          '<h2>Key Terms</h2>' .
                          '<table border="1">' .
                          '<tr><th>Term</th><th>Definition</th></tr>' .
                          implode('', array_map(function () use ($faker) {
                              return '<tr><td>' . $faker->word . '</td><td>' . $faker->sentence(10) . '</td></tr>';
                          }, range(1, 5))) . // 5 table rows
                          '</table>' .
                          '<blockquote>' . $faker->paragraph(5) . ' &gt; Important quote</blockquote>' .
                          '<p><em>' . implode(' ', array_fill(0, 2, $faker->paragraph(10))) . '</em></p>';

            // Encode HTML special entities
            $encodedContent = htmlspecialchars($rawContent, ENT_QUOTES, 'UTF-8');

            // Check storage limit (1 MB per note)
            if (strlen($encodedContent) > 1_000_000) {
                continue; // Skip if note exceeds limit
            }

            $note = Note::create([
                'user_id' => $user->id,
                'title' => htmlspecialchars($rawTitle, ENT_QUOTES, 'UTF-8'),
                'content' => $encodedContent, // Longer HTML with special entities
                'file_path' => null,
                'visibility' => $faker->randomElement(['public', 'private', 'friends']),
                'downloads_count' => $faker->numberBetween(0, 50),
                'ratings_sum' => $faker->numberBetween(0, 100),
                'ratings_count' => $faker->numberBetween(0, 20),
            ]);

            $user->profile->increment('notes_count');

            // Create Note AI
            $rawSummary = '<p>' . implode(' ', array_fill(0, 2, $faker->paragraph(5))) . ' & <strong>summary</strong></p>';
            NoteAi::create([
                'note_id' => $note->id,
                'summary' => htmlspecialchars($rawSummary, ENT_QUOTES, 'UTF-8'),
                'keywords' => json_encode(array_map(function () use ($faker) {
                    return htmlspecialchars($faker->word . ' & more', ENT_QUOTES, 'UTF-8');
                }, range(1, 5))),
                'embedding' => json_encode(array_map(function () use ($faker) {
                    return $faker->randomFloat(0, -1, 1);
                }, range(1, 5))),
                'topics' => json_encode(array_map(function () use ($faker) {
                    return htmlspecialchars($faker->word, ENT_QUOTES, 'UTF-8');
                }, range(1, 3))),
                'qa_cache' => json_encode([
                    [
                        'question' => htmlspecialchars($faker->sentence . ' <b>Q?</b>', ENT_QUOTES, 'UTF-8'),
                        'answer' => htmlspecialchars($faker->paragraph(5), ENT_QUOTES, 'UTF-8'),
                    ],
                    [
                        'question' => htmlspecialchars($faker->sentence, ENT_QUOTES, 'UTF-8'),
                        'answer' => htmlspecialchars($faker->paragraph(5) . ' & more', ENT_QUOTES, 'UTF-8'),
                    ],
                ]),
                'generated_by' => 'Grok',
            ]);

            $notes[] = $note;
        }

        // 6. Create Note-Tag Associations (20)
        for ($i = 0; $i < 20; $i++) {
            NoteTag::create([
                'note_id' => $notes[$faker->numberBetween(0, count($notes) - 1)]->id,
                'tag_id' => $tags[$faker->numberBetween(0, 19)]->id,
            ]);
        }

        // 7. Create Bookmarks (20)
        for ($i = 0; $i < 20; $i++) {
            Bookmark::create([
                'user_id' => $users[$faker->numberBetween(0, 19)]->id,
                'note_id' => $notes[$faker->numberBetween(0, count($notes) - 1)]->id,
            ]);
        }

        // 8. Create Collaborations (20)
        for ($i = 0; $i < 20; $i++) {
            $note = $notes[$faker->numberBetween(0, count($notes) - 1)];
            $collaborator = $users[$faker->numberBetween(0, 19)];

            if ($collaborator->id !== $note->user_id) {
                Collaboration::create([
                    'note_id' => $note->id,
                    'owner_id' => $note->user_id,
                    'collaborator_id' => $collaborator->id,
                    'role' => $faker->randomElement(['editor', 'viewer']),
                    'status' => 'active',
                    'expires_at' => $faker->optional(0.3)->dateTimeBetween('now', '+1 month'),
                ]);
            }
        }
    }
}
?>