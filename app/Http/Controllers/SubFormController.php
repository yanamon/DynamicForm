<?php

namespace App\Http\Controllers;
use App\InputType;
use App\SubForm;
use App\SubFormInput;
use App\Form;
use App\FormInput;
use App\Project;
use App\User;

use Illuminate\Http\Request;

class SubFormController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($form_id)
    {
        
        $form = Form::find($form_id);
        $project_id = $form->project_id;
        $sub_forms = SubForm::where('form_id', $form_id)->get();
        return view('sub-form-show-all', compact('sub_forms','form_id','project_id'));
    }

    public function create($form_id)
    {
        $inputTypes = InputType::get();
        return view('sub-form-create', compact('inputTypes', 'form_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'form_id' => 'required',
            'input_key' => 'required',
            'html' => 'required',
            'sub_form_name' => 'required|alpha_dash'
        ]);
        $form = Form::find($request->form_id);
        $last_form_input = SubForm::where('form_id', $request->form_id)->max('html_key');
        if($last_form_input==null) $last_form_input = 1000;
        else $last_form_input++;
        $count = 0;
        foreach($request->html as  $i => $html){
            $count++;
        }

        $html2= '';
        $html2 = $html2.'<div id=card-input-'.$last_form_input.' data-required=Yes data-key='.$request->sub_form_name.' data-id='.$last_form_input.' class=card-input> ';
        $html2 = $html2.'    <div class=form-group> ';
        $html2 = $html2.'        <input type=hidden name=input_label['.$last_form_input.'][main] value='.$request->sub_form_name.'> ';
        $html2 = $html2.'        <input type=hidden name=input_label['.$last_form_input.'][count] value='.$count.'> ';
        $html2 = $html2.'        <label>Anggota Keluarga</label> ';
        $html2 = $html2.'        <div class=table-responsive> ';
        $html2 = $html2.'            <table id=example class="table table-bordered"> ';
        $html2 = $html2.'                <thead class=thead-dark> ';
        foreach($request->html as  $i => $html){
            $html2 = $html2.'                    <th>'.$request->input_key[$i].'</th> ';
        }
        $html2 = $html2.'                    <th>action</th> ';
        $html2 = $html2.'                </thead> ';
        $html2 = $html2.'                <tbody>  ';
        $html2 = $html2.'                </tbody> ';
        $html2 = $html2.'            </table> ';
        $html2 = $html2.'        </div> ';
        $html2 = $html2.'        <button type="button"><a id="tab-'.$request->sub_form_name.'">Tambah '.$request->sub_form_name.'</a></button> ';
        $html2 = $html2.'    </div> ';
        $html2 = $html2.'</div>';

        //INSERT FORM INPUT
        $form_input = new FormInput();
        $form_input->html = $html2;
        $form_input->input_key = $request->sub_form_name;
        $form_input->form_id = $request->form_id;
        $form_input->save();
        $max_form_input_id = FormInput::max('id');

        //INSERT SUBFORM
        $sub_form = new SubForm();
        $sub_form->title = $request->title;
        $sub_form->description = $request->description;
        $sub_form->form_id = $request->form_id;
        $sub_form->sub_form_name = $request->sub_form_name;
        $sub_form->html_key = $last_form_input;
        $sub_form->form_input_id = $max_form_input_id;
        $sub_form->save();
        $last_sub_form_id = SubForm::max('id'); 
        //INSERT SUBFORM INPUT
        foreach($request->html as  $i => $html){
            $sub_form_input = new SubFormInput();
            $sub_form_input->html = $html;
            $sub_form_input->input_key = $request->input_key[$i];
            $sub_form_input->sub_form_id = $last_sub_form_id;
            $sub_form_input->save();
        }

        return redirect('form/'.$request->form_id.'/sub-forms');
    }

    public function ajaxCheckSubFormName(Request $request){
        if($request->is_edit == "edit"){
            $data = SubForm::where('form_id', $request->formId)->where('sub_form_name', $request->subFormName)->get();
            $row = count($data);
            if(count($data) > 0){
                foreach($data as $data){
                    if($data->id == $request->id_edit) $row = 0;
                }
            }
        }
        else {
            $data = SubForm::where('form_id', $request->formId)->where('sub_form_name', $request->subFormName)->get();
            $row = count($data);
        }
        return response()->json($row);
    }
}
