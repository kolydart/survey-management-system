<?php

namespace Database\Seeders;

use App\Answer;
use Database\Seeders\ContentPageSeed;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ContentPageSeed::class);
        $this->call(RoleSeed::class);
        $this->call(UserSeed::class);

        $this->call(SurveySeeder::class);

    }
}
