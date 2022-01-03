<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        \DB::table('events')->insert([
            'title' => "Cron",
            'description' => "Add cron jobs to itic",
            'event_date' => Carbon::now()->add(1, 'day')->format('Y-m-d H:i:s'),
            'category' => "task",
            'calendar_id' => 1,
            'user_id' => 1,
            'color' => "#3fb5ad",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        \DB::table('events')->insert([
            'title' => "Present",
            'description' => "Prepare present for Xmas",
            'event_date' => Carbon::now()->add(2, 'day')->format('Y-m-d H:i:s'),
            'category' => "reminder",
            'calendar_id' => 1,
            'user_id' => 1,
            'color' => "#3f5fb5",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
