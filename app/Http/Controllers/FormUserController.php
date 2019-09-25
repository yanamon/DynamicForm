<?php

namespace App\Http\Controllers;

use App\Form;
use App\User;
use App\FormData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FormUserController extends Controller
{
    
    public function show($id){
        $form = Form::with('formInput')->find($id);
        return view('form-show', compact('form'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'input_value' => 'required',
        ]);
        $values = $request->input_value;
        $labels = $request->input_label;
        $keys = array_keys($values);
        $i = 0;
        $row = array();
        $row2 = array();
        foreach($values as $value){        
            if(is_array($value)) $attr = array($labels[$keys[$i]] => array_values($value));
            else $attr = array($labels[$keys[$i]] => $value);
            $row = $row + $attr;
            $i++;
        }
        $id_max_data = FormData::max('id');
        if($id_max_data==null) $id_max_data = 0;
        $id_max_data++;
        $path_client = 'json/json-'.$id_max_data.'.json';
        $form_data = new FormData();
        $form_data->file_path= $path_client;
        $form_data->form_id = $request->form_id;
        $form_data->save();

        $path_server = public_path('json/json-'.$id_max_data.'.json');
        $myJSON = json_encode($row);
        $fp = fopen($path_server, 'w');
        fwrite($fp, $myJSON);
        fclose($fp);

        return redirect()->back()->with('message','Input Succcess');
    }

    public function getClient($path){
    }
}
