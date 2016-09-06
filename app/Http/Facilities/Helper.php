<?php

namespace App\Http\Facilities;

class Helper {

	static public function countProfitPercentage($data_collection)
	{
    	$percentage = [];

    	$max = $data_collection->max('profit');
    	$min = $data_collection->min('profit');

    	$max = max($max, abs($min));

    	foreach ($data_collection as $data) {
    		$percentage[] = round((100*$data->profit)/$max);
    	}

    	return $percentage;		
	}
}