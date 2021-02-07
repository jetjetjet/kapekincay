<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Board;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'superadmin',
            'userpassword' => Hash::make('superadmin'),
            'useractive' => '1',
            'userfullname' => 'superadmin',
            'usercreatedat' => now()->toDateTimeString(),
            'usercreatedby' => '1'
        ]);
        Board::factory()->count(25)->create();
        Menu::factory()->count(25)->create();
    }
}
