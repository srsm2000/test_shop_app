<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'アウトドア'
        ]);

        Category::create([
            'name' => 'スポーツ/アスレジャー'
        ]);

        Category::create([
            'name' => '古着'
        ]);

        Category::create([
            'name' => 'ファストファッション'
        ]);

        Category::create([
            'name' => 'ラグジュアリー'
        ]);

        Category::create([
            'name' => 'デザイナーズ'
        ]);

        Category::create([
            'name' => 'ラグジュアリー'
        ]);

        Category::create([
            'name' => 'ストリート'
        ]);

        Category::create([
            'name' => 'フォーマル'
        ]);

        Category::create([
            'name' => 'モード'
        ]);

        Category::create([
            'name' => 'アメカジ'
        ]);

        Category::create([
            'name' => 'アクセサリー'
        ]);

        Category::create([
            'name' => 'シューズ'
        ]);

        Category::create([
            'name' => '小物'
        ]);

        Category::create([
            'name' => 'ドメスティック'
        ]);

        Category::create([
            'name' => 'インポート'
        ]);
    }
}
