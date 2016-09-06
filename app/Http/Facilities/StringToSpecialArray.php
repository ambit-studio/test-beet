<?php

namespace App\Http\Facilities;

class StringToSpecialArray {

	static public function handle($string)
	{
    	$string = StringToSpecialArray::clearString($string);
        $array = StringToSpecialArray::getArray($string);
        $array = StringToSpecialArray::cleanSubarrays($array);
        $array = StringToSpecialArray::divideRevenueAndCost($array);
        $array = StringToSpecialArray::joinMonthSubarrays($array);
        $array = StringToSpecialArray::clearArray($array);
        $array = StringToSpecialArray::countProfit($array);
        $array = StringToSpecialArray::addDateElements($array);

        return $array;
	}


    static private function clearString($string)
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

    static private function getArray($string)
    {
        $array = explode(',],[', $string);

        for ($i=0; $i<count($array); $i++) {
            $array[$i] = explode(",'", $array[$i]); 
        }

        return $array;
    }

    static private function cleanSubarrays($array)
    {
        for ($i=0; $i<count($array); $i++) {
            $array[$i][0] = mb_substr($array[$i][0], 9, 7);
            $array[$i][1] = mb_substr($array[$i][1], 7, 1);
            $array[$i][2] = mb_substr($array[$i][2], 9);
		}

		return $array;
    }

    static private function divideRevenueAndCost($array) 
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

    
    static private function joinMonthSubarrays($array)
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

    static private function clearArray($array)
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

    static private function countProfit($array)
    {
		for ($i=0; $i<count($array); $i++) {
			$array[$i]['profit'] = $array[$i]['revenue'] - $array[$i]['cost'];
		}

		return $array;
    }

    static private function addDateElements($array)
    {
		for ($i=0; $i<count($array); $i++) {
			$array[$i]['month_id'] = mb_substr($array[$i]['date'], 5);
			$array[$i]['year'] = mb_substr($array[$i]['date'], 0, 4);
		}

		return $array;    	
    }

}