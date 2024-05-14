<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\GroupChat;
class GroupChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GroupChat::create([
                'name' => 'Group Chat 1',
                'slug' => \Str::random(8),
                'user_id' => 1,
                'status' => 1
            ]);

        GroupChat::create([
                'name' => 'Group Chat 2',
                'slug' => \Str::random(8),
                'user_id' => 3,
                'status' => 0
            ]);
    }
}
