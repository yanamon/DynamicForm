<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\InputType;
use App\Form;
use App\FormInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id){
        $form = Form::with('formInput')->find($id);
        return view('form-show', compact('form'));
    }

    public function index($project_id){
        $forms = Form::where('project_id', $project_id)->orderBy('id', 'DESC')->get();
        return view('form-show-all', compact('forms','project_id'));
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

    public function exportProject(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'html' => 'required',
        //     'form_name' => 'required'
        // ]);
        // $form = new Form();
        // $form->title = $request->title;
        // $form->description = $request->description;
        // $form->id_user = Auth::user()->id;
        // $form->save();
        // $last_form_id = Form::max('id');
        // foreach($request->html as $html){
        //     $form_input = new FormInput();
        //     $form_input->html = $html;
        //     $form_input->form_id = $last_form_id;
        //     $form_input->save();
        // }
        // $htmls = $this->createHtml($request);
        // $filename = $request->title.".php";
        // Storage::put($filename, $htmls);
        // return redirect('show-form/'.$last_form_id);
    }

    public function exportForm(Request $request)
    {
        // $request->validate([
        //     'title' => 'required',
        //     'html' => 'required',
        //     'form_name' => 'required'
        // ]);
        // $form = new Form();
        // $form->title = $request->title;
        // $form->description = $request->description;
        // $form->id_user = Auth::user()->id;
        // $form->save();
        // $last_form_id = Form::max('id');
        // foreach($request->html as $html){
        //     $form_input = new FormInput();
        //     $form_input->html = $html;
        //     $form_input->form_id = $last_form_id;
        //     $form_input->save();
        // }
        // $htmls = $this->createHtml($request);
        // $filename = $request->title.".php";
        // Storage::put($filename, $htmls);
        // return redirect('show-form/'.$last_form_id);
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
        foreach($request->html as $html){
            $htmls = $htmls.$html;
        }
        $htmls= $htmls.'<button type="submit" class="btn btn-success">Submit</button>';
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
        $head = $head."</head>";
        return $head;
    }

    public function createPhpSubmit($request){
        $php = '';
        $php = $php.'<?php ';
        $php = $php.    'require_once "vendor/autoload.php"; ';
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
        $php = $php.        'unlink($path); ';
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

    public function ajaxCheckFormName(Request $request){
        $row = Form::where('project_id', $request->projectId)->where('form_name', $request->formName)->get();
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
