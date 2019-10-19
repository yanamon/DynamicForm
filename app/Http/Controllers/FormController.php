<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\InputType;
use App\Form;
use App\FormInput;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use File;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($project_id)
    {
        $forms = Form::where('project_id', $project_id)->orderBy('id', 'DESC')->get();
        return view('form-show-all', compact('forms','project_id'));
    }

    public function show($id){
        $form = Form::with('formInput')->find($id);
        return view('form-show', compact('form'));
    }

    public function create($project_id)
    {
        $inputTypes = InputType::get();
        return view('form-create', compact('inputTypes', 'project_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'project_id' => 'required',
            'html' => 'required',
            'form_name' => 'required'
        ]);
        $form = new Form();
        $form->title = $request->title;
        $form->description = $request->description;
        $form->project_id = $request->project_id;
        $form->form_name = $request->form_name;
        $form->save();
        $last_form_id = Form::max('id');
        foreach($request->html as $html){
            $form_input = new FormInput();
            $form_input->html = $html;
            $form_input->form_id = $last_form_id;
            $form_input->save();
        }
        return redirect('project/'.$request->project_id);

    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'project_id' => 'required',
            'html' => 'required',
            'form_name' => 'required'
        ]);
        $form = Form::find($request->id_edit);
        $form->title = $request->title;
        $form->description = $request->description;
        $form->form_name = $request->form_name;
        $form->save();
        
        FormInput::where('form_id', $request->id_edit)->delete();

        foreach($request->html as $html){
            $form_input = new FormInput();
            $form_input->html = $html;
            $form_input->form_id = $request->id_edit;
            $form_input->save();
        }
        return redirect('project/'.$request->project_id);

    }

    public function exportProject($id, Request $request)
    {
        if(empty($request->checked_form)) return redirect('project/'.$id);

        foreach($request->checked_form as $i => $checked_form_id){
            if($i==0) $forms = Form::where('id', $checked_form_id);
            else $forms->orWhere('id', $checked_form_id);
        }  
        $forms = $forms->get();
        $project = Project::find($id);

        $user_path = Auth::user()->id.'/';
        Storage::disk('public')->deleteDirectory($user_path);
        Storage::disk('public')->makeDirectory($user_path);
        $project_path = $user_path.$project->project_name.'/';
        Storage::disk('public')->makeDirectory($project_path);
        $storage_path1 = storage_path('app/dropbox');
        $storage_path2 = storage_path('app/public/' . $project_path);
        File::copyDirectory($storage_path1 , $storage_path2);
        
        foreach($forms as $form){
            $this->export($form->id, $project_path);
        }  

        $zip_file = $project->project_name.'.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = storage_path('app/public/'.$project_path);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            if (!$file->isDir()) {
                $filePath     = $file->getRealPath();
                $relativePath = $project->project_name.'/'. substr($filePath, strlen($path));
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        return response()->download($zip_file);
    }

    public function export($id, $project_path)
    {
        $form = Form::with('formInput')->find($id);
        $project = Project::find($form->project_id);

        $request = (array)$form;
        $request['app_key'] = $project->dropbox_app_key;
        $request['app_secret'] = $project->dropbox_app_secret;
        $request['access_token'] = $project->dropbox_access_token;
        $request['title'] = $form->title;
        $request['description'] = $form->description;
        $request['form_name'] = $form->form_name;
        $request['formInput'] = $form->formInput;
        $request = (object)$request;

        $htmls = $this->createHtml($request);
        $filename = $form->form_name.".php";
        Storage::disk('public')->put($project_path.$filename, $htmls);
    }

    public function createHtml($request){
        $htmls = "";
        $htmls = $htmls.$this->createPhpSubmit($request);
        $htmls = $htmls."<html>";
        $htmls = $htmls.$this->createHeader();
        $htmls = $htmls.'<body>';
        $htmls = $htmls.'<div class="container">';
        $htmls = $htmls.'<div class="row" style="margin-top:50px;">';
        $htmls = $htmls.'<div class="col-md-2"></div>';
        $htmls = $htmls.'<div id="card" class="col-md-8 shadow-sm" style="margin-top:25px">';
        $htmls = $htmls.'<div>';
        $htmls = $htmls.'<div class="form-group card-title">';
        $htmls = $htmls.'<h3>'.$request->title.'</h3>';
        if($request->description!=null) $htmls = $htmls.'<label>'.$request->description.'</label>';
        $htmls = $htmls.'</div>';
        $htmls = $htmls.'<form action="#" method="POST">';
        foreach($request->formInput as $formInput){
            $htmls = $htmls.$formInput->html;
        }
        $htmls= $htmls.'<div class="form-group card-title" style="margin-bottom:30px;"><button type="submit" class="col-md-12 btn btn-success btn-block">Submit</button></div>';
        $htmls = $htmls.'</form>';
        $htmls = $htmls.'</div></div></div></div>';
        $htmls = $htmls.'</body>';
        $htmls = $htmls.'</html>';
        $htmls = $htmls.$this->createPhpSuccess();
        return $htmls;
    }

    public function createHeader(){
        $head = "<head>";
        $head = $head.'<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
        $head = $head.'<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">';
        $head = $head.'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
        $head = $head.'<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>';
        $head = $head.$this->createCss();
        $head = $head."</head>";
        return $head;
    }

    public function createPhpSubmit($request){
        $php = '';
        $php = $php.'<?php ';
        $php = $php.    'require_once "dropbox/autoload.php"; ';
        $php = $php.    'use Kunnu\Dropbox\DropboxFile; ';
        $php = $php.    'use Kunnu\Dropbox\DropboxApp; ';
        $php = $php.    'use Kunnu\Dropbox\Dropbox; ';
            
        $php = $php.    'if(isset($_POST["input_value"])){ ';
        $php = $php.        '$app_key = "'.$request->app_key.'"; ';
        $php = $php.        '$app_secret = "'.$request->app_secret.'"; ';
        $php = $php.        '$access_token = "'.$request->access_token.'"; ';
        $php = $php.        '$folder_name = "'.$request->form_name.'"; ';
        $php = $php.        '$file_name = "data".".json"; ';
        
        $php = $php.        '$values = $_POST["input_value"]; ';
        $php = $php.        '$labels = $_POST["input_label"]; ';
        $php = $php.        '$keys = array_keys($values); ';
        $php = $php.        '$i = 0; ';
        $php = $php.        '$row = array(); ';
        $php = $php.        'foreach($values as $value){ ';      
        $php = $php.            'if(is_array($value)) $attr = array($labels[$keys[$i]] => array_values($value)); ';
        $php = $php.            'else $attr = array($labels[$keys[$i]] => $value); ';
        $php = $php.            '$row = $row + $attr; ';
        $php = $php.            '$i++; ';
        $php = $php.        '} ';
        
        $php = $php.        '$myJSON = json_encode($row); ';
        $php = $php.        '$fp = fopen($file_name, "w"); ';
        $php = $php.        'fwrite($fp, $myJSON); ';
        $php = $php.        'fclose($fp); ';
        $php = $php.        '$app = new DropboxApp($app_key, $app_secret, $access_token); ';
        $php = $php.        '$dropbox = new Dropbox($app); ';

        $php = $php.        'try{';
        $php = $php.        '    $folder = $dropbox->getMetadata("/".$folder_name);';
        $php = $php.        '}catch(Exception $e){';
        $php = $php.        '    $folder = $dropbox->createFolder("/".$folder_name);';
        $php = $php.        '}';

        $php = $php.        '$path = "/".$folder_name."/".$file_name;';
        $php = $php.        '$dropboxFile = new DropboxFile($file_name); ';
        $php = $php.        '$file = $dropbox->upload($dropboxFile, $path, ["autorename" => true]);';
        $php = $php.        '$file->getName(); ';
        $php = $php.        'unlink($file_name); ';
        $php = $php.        'session_start(); ';
        $php = $php.        '$_SESSION["success"] = 1; ';
        $php = $php.        'header("Location: ".$_SERVER["PHP_SELF"]); ';
        $php = $php.        'exit; ';
        $php = $php.    '} ';
        $php = $php.    'else{ ';
        $php = $php.'?> ';
        return $php;
    }

    public function createPhpSuccess(){
        $php = '';
        $php = $php.' <?php ';
        $php = $php.    'session_start(); ';
        $php = $php.    'if (isset($_SESSION["success"])) { ';
        $php = $php.        'unset($_SESSION["success"]); ';
        $php = $php.'?> ' ;
        $php = $php.        '<script> alert("Input Data Sukses"); </script> ';
        $php = $php.'<?php ';
        $php = $php.        '} ';
        $php = $php.    '} ';
        $php = $php.'?> ';
        return $php;
    }

    public function createCss(){
        $css = '<style>';
        $css = $css.'#card {border-radius:5px;background-color:white;padding-top:30px;padding-bottom:0px;padding-right:0px;padding-left:0px;margin-bottom: 10px;}';
        $css = $css.'.card-title{padding-right: 30px;padding-left: 30px; }';
        $css = $css.'.card-input { padding-top:15px; padding-bottom:5px;padding-right: 30px;padding-left: 30px;}';
        $css = $css.'</style>';
        return $css;
    }

    public function ajaxCheckFormName(Request $request){
        if($request->is_edit == "edit"){
            $data = Form::where('project_id', $request->projectId)->where('form_name', $request->formName)->get();
            $row = count($data);
            if(count($data) > 0){
                foreach($data as $data){
                    if($data->id == $request->id_edit) $row = 0;
                }
            }
        }
        else {
            $data = Form::where('project_id', $request->projectId)->where('form_name', $request->formName)->get();
            $row = count($data);
        }

        return response()->json($row);
    }

    
    public function edit($id)
    {
        $inputTypes = InputType::get();
        $form = Form::with('formInput')->find($id);
        $project_id = $form->project_id;
        return view('form-edit', compact('form','inputTypes','project_id'));
    }


    public function destroy($id)
    {
        $form = Form::find($id);
        $project_id = $form->project_id;
        $inputs = FormInput::where('form_id', $form->id)->delete();
        $form = Form::find($id)->delete();
        return redirect('project/'.$project_id);
    }

}
