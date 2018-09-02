<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    //
    public function __construct()
    {
        ini_set('memory_limit', '-1');
    }

    public function getdata(Request $req)
    {

        // $this->downloadJSONFile();
        // die;

        $database_list = array("database1", "database2", "database3", "database4", "database5");

        foreach ($database_list as $dbname) {
            $this->Export_Database($dbname);
        }

    }

    public function Export_Database($name)
    {

        $backup_name = false;
        $queryTables = DB::connection($name)->select('SHOW TABLES');
 
        foreach ($queryTables as $table) {
            foreach ($table as $t) {
                $target_tables[] = $t;
            }

        }

        if ($queryTables) {

            foreach ($target_tables as $table) {

                $result = DB::connection($name)->table($table)->get();
                $fields_amount = $result->count();
                $res = DB::connection($name)->select('SHOW CREATE TABLE ' . $table);
                $dbcreateQ= DB::connection($name)->select('SHOW CREATE DATABASE '. $name);


                $content = (!isset($content) ? '' : $content) . "\n\n";

                $ab = 0;
                foreach ($dbcreateQ[0] as $rd) {
                    if ($ab++ != 0) {
                        $content .= $rd . ";\n\n";
                    }

                }
                $a = 0;
                foreach ($res[0] as $r) {
                    if ($a++ != 0) {
                        $content .= $r . ";\n\n";
                    }

                }

                for ($i = 0, $st_counter = 0; $i < count($result); $i++, $st_counter++) {
                    if ($st_counter % 100 == 0 || $st_counter == 0) {
                        $content .= "\nINSERT INTO " . $table . " VALUES";
                    }
                    $content .= "\n(";
                    $j = 0;

                    $row_amount = count((array) $result[$i]);

                    foreach ($result[$i] as $value) {
                        $value = str_replace("\n", "\\n", addslashes($value));
                        if (isset($value)) {
                            $content .= '"' . $value . '"';
                        } else {
                            $content .= '""';
                        }
                        if ($j++ < ($row_amount - 1)) {
                            $content .= ',';
                        }
                    }
                    $content .= ")";

                    //   every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $fields_amount) {
                        $content .= ";";
                    } else {
                        $content .= ",";
                    }

                }

            }

            // $backup_name = $backup_name ? $backup_name : $name . "___(" . date('H-i-s') . "_" . date('d-m-Y') . ")__rand" . rand(1, 11111111) . ".sql";
            $backup_name = $backup_name ? $backup_name : $name . ".sql";
            $this->downloadSqlFile($backup_name, $content);

        }
    }

    public function downloadSqlFile($backup_name, $data)
    {

        File::put(public_path('/download/databases/' . $backup_name), $data);

    }
}
