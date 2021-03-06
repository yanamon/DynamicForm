<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    protected $table = "form_inputs";

    public function form(){
		return $this->belongsTo('App\Form', 'form_id');
    }

    public function subForm(){
      return $this->hasMany('App\SubForm', 'form_input_id');
    }

}
