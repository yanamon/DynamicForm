<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InputType extends Model
{
    protected $table = "input_types";

    public function formInput(){
        return $this->hasMany('App\FormInput', 'input_type_id');
    }
}
