<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::factory()->count(100)->create();
        Article::factory()->count(100)->create(['user_id' => 1]);
        Article::factory()->count(100)->trashed()->create(['user_id' => 1]);

        // 記事データの削除処理
        // Article::truncate();
    }
}
