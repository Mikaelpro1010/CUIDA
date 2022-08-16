<?php

use App\Models\AppUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AppUser::class, 50)->create();
    }
}
