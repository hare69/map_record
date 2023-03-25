<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NightView;

class NightViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv_path = storage_path('app/csv/yakei02.csv');
        $lines = new \SplFileObject($csv_path);

        foreach ($lines as $index => $line) {

            if($index > 0) {

                $line = mb_convert_encoding($line, 'UTF-8', 'SJIS-win');
                $values = str_getcsv($line);

                if(!is_null($values[0])) {

                    $night_view = new NightView();
                    $night_view->name = $values[1];
                    $night_view->location = [
                        'latitude' => $values[2],
                        'longitude' => $values[3]
                    ];
                    $night_view->address = $values[4];
                    $night_view->save();

                }

            }

        }

    }
}
