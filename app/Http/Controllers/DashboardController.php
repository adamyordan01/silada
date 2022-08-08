<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArchives = Archive::count();
        $totalPpats = Archive::whereIn('document_type_id', [3,4,5,6])->count();
        $totalNonPpat = Archive::whereNotIn('document_type_id', [3,4,5,6])->count();

        return view('dashboard', [
            'totalArchives' => $totalArchives,
            'totalPpats' => $totalPpats,
            'totalNonPpat' => $totalNonPpat,
        ]);
    }

    public function getArchiveByMonth()
    {
        
        $archiveByMonth = DB::select("
        
            SELECT
                COUNT( DISTINCT archive_number ) AS total,
                MONTHNAME( archives.archive_date ) AS month_name 
            FROM
                archives 
            WHERE
                YEAR ( archive_date ) = '2022' 
            GROUP BY
                MONTHNAME( archives.archive_date ) 
            ORDER BY
                STR_TO_DATE(
                CONCAT( '0001 ', MONTHNAME( archives.archive_date ), ' 01' ),
                '%Y %M %d')
        ");

        // dd($archiveByMonth);
    

        $labels = [];
        $data = [];
        foreach ($archiveByMonth as $key => $value) {
            $labels[] = $value->month_name;
            $data[] = $value->total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
        
        // $data = [];

        // foreach ($archiveByMonth as $row) {
        //     $data['labels'] = $row->month_name;
        //     $data['archives'] = $row->total;
        // }


        // $data['chart_archive_by_month'] = json_encode($data);

        // return $data;
    }
}
