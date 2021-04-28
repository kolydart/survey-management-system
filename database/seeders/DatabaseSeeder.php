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

        /** hard delete hidden value */
        if (Answer::hidden()->count()) {
            Answer::hidden()->forceDelete();
        }

        $this->call(qstSeed::class);

        /** re-create hidden value */
        Answer::create(['title'=>'value', 'hidden'=>true]);

        $this->call(answerlistTypesSeed::class);
    }
}
