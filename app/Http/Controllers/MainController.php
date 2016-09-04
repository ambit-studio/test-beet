<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Monthdata;

class MainController extends Controller
{
    public function uploadFile(Request $request)
    {
    	$string = file_get_contents($request->file('file'));
    	$string = $this->clearString($string);
        $array = $this->getArray($string);
        $array = $this->cleanSubarrays($array);
        $array = $this->divideRevenueAndCost($array);
        $array = $this->joinMonthSubarrays($array);
        $array = $this->clearArray($array);
        $array = $this->countProfit($array);

        $this->clearMonthdatasTable();

    	$this->saveInDB($array);

        return redirect('/report')->with('message', 'Data is successfully uploaded and report is ready');
    }

    public function showTable()
    {
        $datas = Monthdata::orderBy('date')->take(6)->get();
        $profit_percentage = $this->countProfitPercentage($datas);

        $report = true;

        return view('welcome', compact('datas', 'report', 'profit_percentage'));
    }

    /*
    |--------------------------------------------------------------------------
    | Service functions
    |--------------------------------------------------------------------------
    */


    private function clearString($string)
    {
        $first = strpos($string, '[');
        $second = strpos($string, '[', $first+1) + 2;
        $last = strripos($string, ']');

        $string = mb_substr($string, $second, $last-2-$second-7);

        $string = str_replace("\n", "", $string);
        $string = str_replace(" ", "", $string);
        $string = str_replace("][", "],[", $string);

    	return $string;
    }

    private function getArray($string)
    {
        $array = explode(',],[', $string);

        for ($i=0; $i<count($array); $i++) {
            $array[$i] = explode(",'", $array[$i]); 
        }

        return $array;
    }

    private function cleanSubarrays($array)
    {
        for ($i=0; $i<count($array); $i++) {
            $array[$i][0] = mb_substr($array[$i][0], 9, 7);
            $array[$i][1] = mb_substr($array[$i][1], 7, 1);
            $array[$i][2] = mb_substr($array[$i][2], 9);
		}

		return $array;
    }

    private function divideRevenueAndCost($array) 
    {
        for ($i=0; $i<count($array); $i++) {

    		if ($array[$i][1] == 3) {
	    		$array[$i]['revenue'] = $array[$i][2];
	    		$array[$i]['cost'] = 0;
    		}
    		elseif ($array[$i][1] == 5 || $array[$i][1] == 6 || $array[$i][1] == 7 || $array[$i][1] == 8) {
	    		$array[$i]['revenue'] = 0;
	    		$array[$i]['cost'] = $array[$i][2];
    		}
    		else {
    			$array[$i]['revenue'] = 0;
	    		$array[$i]['cost'] = 0;
    		}

    		$array[$i]['date'] = $array[$i][0];

    		unset($array[$i][0]);
    		unset($array[$i][1]);
    		unset($array[$i][2]);
    	}

    	return $array;
    }

    
    private function joinMonthSubarrays($array)
    {
		for ($i=0; $i<count($array); $i++) {
			if ($array[$i]['date'] !== 0) {
				for ($j=$i+1; $j<count($array); $j++) {

					if ($array[$i]['date'] == $array[$j]['date']) {

						$array[$i]['revenue'] += $array[$j]['revenue'];
						$array[$i]['cost'] += $array[$j]['cost'];

						$array[$j]['date'] = 0;
					}
				}
			}
		}

		return $array;
    }

    private function clearArray($array)
    {
		$length = count($array);

		for ($i=0; $i<$length; $i++) {
			if ($array[$i]['date'] == 0) {
				unset($array[$i]);
			}
		}

		$array = array_values($array);

		return $array;
    }

    private function countProfit($array)
    {
		for ($i=0; $i<count($array); $i++) {
			$array[$i]['profit'] = $array[$i]['revenue'] - $array[$i]['cost'];
		}

		return $array;
    }

    private function clearMonthdatasTable()
    {
        $datas = Monthdata::all();
        if ($datas) {
            foreach ($datas as $data) {
                $data->delete();
            }
        }

        return true;
    }

    private function saveInDB($array)
    {
    	foreach ($array as $month) {
    		Monthdata::create([
                'date' => $month['date'],
                'month_id' => mb_substr($month['date'], 5),
                'year' => mb_substr($month['date'], 0, 4),
                'revenue' => $month['revenue'],
                'cost' => $month['cost'],
                'profit' => $month['profit']
            ]);
    	}
    }

    private function countProfitPercentage($datas)
    {
    	$percentage = [];

    	$max = $datas->max('profit');
    	$min = $datas->min('profit');

    	$max = max($max, abs($min));

    	foreach ($datas as $data) {
    		$percentage[] = round((100*$data->profit)/$max);
    	}

    	return $percentage;
    }

}
