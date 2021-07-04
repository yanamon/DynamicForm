<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubFormInput extends Model
{
    protected $table = "sub_form_inputs";

    public function subForm(){
		return $this->belongsTo('App\SubForm', 'sub_form_id');
    }

}
