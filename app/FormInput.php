<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormInput extends Model
{
    protected $table = "form_inputs";

    public function form(){
		return $this->belongsTo('App\Form', 'form_id');
    }

    public function inputType(){
		return $this->belongsTo('App\InputType', 'input_type_id');
    }

    public function inputOption(){
        return $this->hasMany('App\InputOption', 'form_input_id');
      }
}
