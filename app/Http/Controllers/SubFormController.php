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

        $sub_form = new SubForm();
        $sub_form->title = $request->title;
        $sub_form->description = $request->description;
        $sub_form->form_id = $request->form_id;
        $sub_form->sub_form_name = $request->sub_form_name;
        $sub_form->save();
        $last_sub_form_id = SubForm::max('id');
        foreach($request->html as  $i => $html){
            $sub_form_input = new SubFormInput();
            $sub_form_input->html = $html;
            $sub_form_input->input_key = $request->input_key[$i];
            $sub_form_input->sub_form_id = $last_sub_form_id;
            $sub_form_input->save();
        }

        //INSERT FORM INPUT MODAL
        // $form_input = new FormInput();
        // $form_input->html = $html;
        // $form_input->input_key = $request->input_key[$i];
        // $form_input->form_id = $request->form_id;
        // $form_input->save();



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
