<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubForm extends Model
{
    protected $table = "sub_forms";
  
    public function subFormInput(){
      return $this->hasMany('App\SubFormInput', 'sub_form_id');
    }
  
    public function form(){
      return $this->belongsTo('App\Form', 'form_id');
    }

    public function formInput(){
      return $this->belongsTo('App\FormInput', 'form_input_id');
    }
}
