<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class importData extends Controller
{
    public function importCSV(Request $request)
    {
        $path = storage_path() . '/files/';
        if ($request->input('inputfile')) {
            $path = $path . $request->input('inputfile');
        } else $path = $path . 'import-7-5-2019-11-12-04.csv';


        $rows = [];
//        echo "<pre>";

        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row = [];
                for ($c = 0; $c < $num; $c++) {
//                    $cols[] =str_replace("\n", " ", trim($data[$c]));
                    $row[] = trim($data[$c]);

                }
                $rows[] = $row;
            }
            fclose($handle);

        }

//        echo "</pre>";
        $colNames = array_shift($rows);
        foreach ($rows as &$row) {
            $row = array_combine($colNames, $row);
        }
        Schema::dropIfExists('centers');
        Schema::create('centers', function (Blueprint $table) use ($colNames) {
            foreach ($colNames as $columnName) {
                if ($columnName != 'id') {
                    $table->string($columnName)->nullable();
                } else {
                    $table->unsignedInteger('id')->nullable()->index()->comment('ID');
                }
            }
        });

        DB::table('centers')->insert($rows);
        $centers = DB::table('centers')->get();

        return collect($centers)->toJson();
    }

    public function getObjects(Request $request)
    {
        $centers = DB::table('centers')->get();
        return collect($centers)->toJson();
    }
}
