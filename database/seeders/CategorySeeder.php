<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Academic',       'description' => 'Issues related to courses, exams, grades, and lecturers'],
            ['name' => 'Accommodation',  'description' => 'Issues related to hostels, dormitories, and housing'],
            ['name' => 'Financial',      'description' => 'Issues related to fees, payments, and bursaries'],
            ['name' => 'Staff Conduct',  'description' => 'Issues related to behaviour or conduct of staff members'],
            ['name' => 'Facilities',     'description' => 'Issues related to buildings, equipment, and infrastructure'],
            ['name' => 'IT / Technology','description' => 'Issues related to internet, systems, and technology'],
            ['name' => 'Other',          'description' => 'Any other complaints not listed above'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore(array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
