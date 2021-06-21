<?php

namespace App\Http\Controllers;
use App\InputType;
use App\SubForm;
use App\SubFormInput;
use App\Form;
use App\FormInput;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

    public function show($id){
        $sub_form = SubForm::with('SubFormInput')->find($id);
        return view('sub-form-show', compact('sub_form'));
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
        $project = Project::find($form->project_id);
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
        $html2 = $html2."            <table id=example class='table table-bordered'> ";
        $html2 = $html2.'                <thead class=thead-dark> ';
        foreach($request->html as  $i => $html){
            $html2 = $html2.'                    <th>'.$request->input_key[$i].'</th> ';
        }
        $html2 = $html2.'                    <th>action</th> ';
        $html2 = $html2.'                </thead> ';
        $html2 = $html2.'                <tbody id='.$request->sub_form_name.'>  ';
        $html2 = $html2.'                </tbody> ';
        $html2 = $html2.'            </table> ';
        $html2 = $html2.'        </div> ';
        $html2 = $html2.'        <button type=button id=tab-'.$request->sub_form_name.'><a>Tambah '.$request->sub_form_name.'</a></button> ';
        $html2 = $html2.'    </div> ';
        $html2 = $html2.'</div>';

        $user_path = 'table-modal/'.Auth::user()->id.'/';
        $project_path = $user_path.$project->project_name.'/';
        if(Storage::disk('public')->exists($project_path) == 0){
            Storage::disk('public')->makeDirectory($project_path);
        }
        $form_path = $project_path.$form->form_name.'/';
        if(Storage::disk('public')->exists($form_path) == 0){
            Storage::disk('public')->makeDirectory($form_path);
        }
        $sub_form_path = $form_path.$request->sub_form_name;
        if(Storage::disk('public')->exists($sub_form_path) == 0){
            Storage::disk('public')->makeDirectory($sub_form_path);
        }

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

            $tm_name = $request->input_key[$i].".json";
            
            if(isset($request->tm_json[$request->input_key[$i]])){
                $tm_json = $request->tm_json[$request->input_key[$i]];
                Storage::disk('public')->put($sub_form_path."/".$tm_name, $tm_json);
            }
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


    public function destroy($id)
    {
        $sub_form = SubForm::find($id);
        $form_id = $sub_form->form_id;
        $form_input_id = $sub_form->form_input_id;

        
        $form = Form::find($sub_form->form_id);
        $project_id = $form->project_id;
        $project = Project::find($project_id);
        $user_path = 'table-modal/'.Auth::user()->id.'/';
        $project_path = $user_path.$project->project_name.'/';
        $form_path = $project_path.$form->form_name.'/';
        $sub_form_path = $form_path.$sub_form->sub_form_name;
        if(Storage::disk('public')->exists($sub_form_path) == 1){
            Storage::disk('public')->deleteDirectory($sub_form_path);
        }

        $sub_form_inputs = SubFormInput::where('sub_form_id', $id)->delete();
        $sub_form = SubForm::find($id)->delete();
        $form_input = FormInput::find($form_input_id)->delete();


        return redirect('form/'.$form_id.'/sub-forms');
    }

    public function edit($id)
    {
        $inputTypes = InputType::get();
        $sub_form = SubForm::with('subFormInput')->find($id);
        $form_id = $sub_form->form_id;
        $form = Form::find($form_id);
        $project_id = $form->project_id;
        $project = Project::find($project_id);
        $user_id = Auth::user()->id;
        $tm_jsons = array();

        $form_inputs = FormInput::where('form_id', $id)->get();
        foreach($form_inputs as $form_input){
            $tm_json_path = 'table-modal/'.$user_id.'/'.$project->project_name.'/'.$form->form_name.'/'.$sub_form->sub_form_name.'/'.$form_input->input_key.".json";
            if (Storage::disk("public")->exists($tm_json_path)) {
                $tm_json = Storage::disk("public")->get($tm_json_path);
                $tm_jsons[$form_input->input_key] = $tm_json;
            }
        }

        return view('sub-form-edit', compact('sub_form','inputTypes', 'form_id','tm_jsons'));
    }

    
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'form_id' => 'required',
            'html' => 'required',
            'input_key' => 'required',
            'sub_form_name' => 'required',
        ]);
        $sub_form = SubForm::find($request->id_edit);
        $sub_form->title = $request->title;
        $sub_form->description = $request->description;
        $sub_form->sub_form_name = $request->sub_form_name;
        // $auth_file = $request->file('json_identifier');
        // $form->form_type = $request->form_type;
        // if(!empty($auth_file) && $request->form_type==2){
        //     $auth_file = file_get_contents($auth_file);
        //     $auths = json_decode($auth_file);
        //     foreach($auths as  $key => $auth){
        //         foreach($auth as  $key2 => $aut){
        //             $auth_input_key = $key2;
        //             break;
        //         }
        //         break;
        //     }
        //     $form_path = $form->auth_file;
        //     Storage::delete($form->auth_file);
        //     $form_path = substr($form_path, 0, -9);
        //     $path = $request->file('json_identifier')->storeAs(
        //         $form_path, 'auth.json'
        //     );
        //     $form->auth_file = $form_path.'/auth.json';
        // }
        $sub_form->save();
        SubFormInput::where('sub_form_id', $request->id_edit)->delete();

        // if(!empty($auth_file) && $request->form_type==2){
        //     $form_input = new FormInput(); 
        //     $form_input->input_key = $auth_input_key;
        //     $form_input->form_id = $request->id_edit;
        //     $form_input->save();
        // }

        $form = Form::find($sub_form->form_id);
        $project_id = $form->project_id;
        $project = Project::find($project_id);
        $user_path = 'table-modal/'.Auth::user()->id.'/';
        $project_path = $user_path.$project->project_name.'/';
        if(Storage::disk('public')->exists($project_path) == 0){
            Storage::disk('public')->makeDirectory($project_path);
        }
        $form_path = $project_path.$request->form_name.'/';;
        if(Storage::disk('public')->exists($form_path) == 0){
            Storage::disk('public')->makeDirectory($form_path);
        }
        $sub_form_path = $form_path.$request->sub_form_name;
        if(Storage::disk('public')->exists($sub_form_path) == 0){
            Storage::disk('public')->makeDirectory($sub_form_path);
        }

        foreach($request->html as $i => $html){
            $sub_form_input = new SubFormInput();
            $sub_form_input->html = $html;
            $sub_form_input->input_key = $request->input_key[$i];
            $sub_form_input->sub_form_id = $request->id_edit;

            $tm_name = $request->input_key[$i].".json";
            if(isset($request->tm_json[$request->input_key[$i]])){
                $tm_json = $request->tm_json[$request->input_key[$i]];
                Storage::disk('public')->put($sub_form_path."/".$tm_name, $tm_json);
            }
            $sub_form_input->save();
        }
        return redirect('form/'.$request->form_id.'/sub-forms');

    }

}
