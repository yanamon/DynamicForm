<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
  protected $table = "forms";

  public function formInput(){
    return $this->hasMany('App\FormInput', 'form_id');
  }

  public function project(){
    return $this->belongsTo('App\Project', 'project_id');
  }

  public function subForm(){
    return $this->hasMany('App\SubForm', 'form_id');
  }
}
