<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monthdata extends Model
{
    protected $fillable = [
    	'date', 'month_id', 'year', 'revenue', 'cost', 'profit'
    ];

    public $timestamps = false;

    public function month()
    {
    	return $this->belongsTo('App\Month');
    }
}
