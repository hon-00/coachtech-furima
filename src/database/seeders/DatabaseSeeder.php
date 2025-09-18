<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategoriesTableSeeder::class,
        ]);

        if (app()->environment(['local', 'testing'])) {
            \App\Models\User::factory(5)->create()->each(function ($user) {
                $items = \App\Models\Item::factory(2)->create(['user_id' => $user->id]);
                foreach ($items as $item) {
                    \App\Models\Comment::factory(2)->create([
                        'user_id' => $user->id,
                        'item_id' => $item->id,
                    ]);
                }
            });
        }
    }
}
