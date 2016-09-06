<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Http\Facilities\StringToSpecialArray;
use App\Http\Facilities\Helper;

use DB;

use App\Monthdata;

class DataController extends Controller
{
    public function fileToReport(Request $request)
    {
    	$string = file_get_contents($request->file('file'));

        $prepared_array = StringToSpecialArray::handle($string);

        DB::table('monthdatas')->delete();

    	$this->saveIntoDB($prepared_array);

        return redirect('/report')->with('message', 'Data is successfully uploaded and report is ready');
    }

    public function showTable()
    {
        $data_collection = Monthdata::orderBy('date')->take(6)->get();

        $profit_percentage = Helper::countProfitPercentage($data_collection);

        $report = true;

        return view('welcome', compact('data_collection', 'report', 'profit_percentage'));
    }

    /*
    |--------------------------------------------------------------------------
    | Service functions
    |--------------------------------------------------------------------------
    */

    private function saveIntoDB($array)
    {
        foreach ($array as $month) {
            Monthdata::create([
                'date' => $month['date'],
                'month_id' => $month['month_id'],
                'year' => $month['year'],
                'revenue' => $month['revenue'],
                'cost' => $month['cost'],
                'profit' => $month['profit']
            ]);
        }
    }


}
