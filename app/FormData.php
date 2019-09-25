<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $table = "form_datas";
    public function form(){
        return $this->belongsTo('App\Form', 'form_id');
    }

}
