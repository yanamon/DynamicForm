<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputOption extends Model
{
    protected $table = "input_options";

    public function formInput(){
		return $this->belongsTo('App\FormInput', 'form_input_id');
    }
}
