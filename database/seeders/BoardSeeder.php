<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Board;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderDetail;
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
        Board::truncate();
        User::truncate();
        
        User::create([
            'username' => 'superadmin',
            'userpassword' => Hash::make('superadmin'),
            'useractive' => '1',
            'userfullname' => 'superadmin',
            'usercreatedat' => now()->toDateTimeString(),
            'usercreatedby' => '1'
        ]);
        Board::factory()->count(25)->create();

        // Menu::factory()->count(25)->create();

        // for ($i=0; $i<=6; $i++) {
        //     $order = Order::factory(1)->create(['orderinvoiceindex' => $i])->first();
        //     $product = OrderDetail::factory(1)->create(['odorderid' => $order->id, 'odindex' => $i])->first();
        // }
    }
}
