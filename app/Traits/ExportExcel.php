<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

/*
|--------------------------------------------------------------------------
| Export Excel Trait
|--------------------------------------------------------------------------
|
| This trait will be used for download tables in excel file format.
|
*/

trait ExportExcel
{
    public static function export(Collection $rows, Collection $columns = null, string $name = null)
    {
        $name = $name ?? 'excel_data';
        $name = $name . '_' . date('Y-m-d_H-i-s');

        header("Content-Disposition: attachment; filename=\"$name\".xls");
        header("Content-Type: application/vnd.ms-excel");
        //To define column name in first row.
        $column_names = false;
        // run loop through each row in $customers_data
        foreach ($rows->toArray() as $row) {
            if (!$column_names) {
                echo implode("\t", array_keys($row)) . "\n";
                $column_names = true;
            }
            // The array_walk() function runs each array element in a user-defined function.
            array_walk($row, function (&$str) {
                $str = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $str);
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if (strstr($str, '"')) {
                    $str = '"' . str_replace('"', '""', $str) . '"';
                }
            });
            echo implode("\t", array_values($row)) . "\n";
        }
        exit;
    }
}
