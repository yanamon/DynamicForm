<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
  protected $table = "forms";

  public function formInput(){
    return $this->hasMany('App\FormInput', 'form_id');
  }

  public function formData(){
    return $this->hasMany('App\FormData', 'form_id');
  }

  public function project(){
    return $this->belongsTo('App\Project', 'project_id');
  }
}
