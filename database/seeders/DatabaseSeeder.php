<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
     public function run(): void
{
    // استخدام updateOrCreate يمنع خطأ الـ Duplicate Entry
    \App\Models\User::updateOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Mariam',
            'password' => bcrypt('password'), // كلمة السر ستكون password
        ]
    );

    $this->call([
        QuestionSeeder::class,
    ]);
}
}