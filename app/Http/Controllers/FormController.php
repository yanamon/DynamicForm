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
use Kunnu\Dropbox\DropboxFile; 
use Kunnu\Dropbox\DropboxApp; 
use Kunnu\Dropbox\Dropbox; 

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
            'input_key' => 'required',
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
        foreach($request->html as  $i => $html){
            $form_input = new FormInput();
            $form_input->html = $html;
            $form_input->input_key = $request->input_key[$i];
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
            'input_key' => 'required',
            'form_name' => 'required'
        ]);
        $form = Form::find($request->id_edit);
        $form->title = $request->title;
        $form->description = $request->description;
        $form->form_name = $request->form_name;
        $form->save();
        
        FormInput::where('form_id', $request->id_edit)->delete();

        foreach($request->html as $i => $html){
            $form_input = new FormInput();
            $form_input->html = $html;
            $form_input->input_key = $request->input_key[$i];
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
        $forms = $forms->with('formInput')->get();
        $project = Project::find($id);

        $user_path = Auth::user()->id.'/';
        Storage::disk('public')->deleteDirectory($user_path);
        Storage::disk('public')->makeDirectory($user_path);
        $project_path = $user_path.$project->project_name.'-master';
        $share_path = $user_path.$project->project_name;
        Storage::disk('public')->makeDirectory($project_path);
        Storage::disk('public')->makeDirectory($project_path."/attachment");
        Storage::disk('public')->makeDirectory($share_path);
        $storage_path1 = storage_path('app/dropbox');
        $storage_path3 = storage_path('app/sync/sync');
        $storage_path2 = storage_path('app/public/' . $project_path);
        $storage_path4 = storage_path('app/public/' . $share_path);
        $storage_path5 = storage_path('app/public/' . $user_path);
        File::copyDirectory($storage_path1 , $storage_path2);
        File::copyDirectory($storage_path1 , $storage_path4);
          
        foreach($forms as $i => $form){
            $this->export($form->id, $share_path);
        }
        $zip_file = $project->project_name.'.zip';
        $zip = new \ZipArchive();
        $zip->open($storage_path2."/".$zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $path = storage_path('app/public/'.$share_path);
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

        File::copy($storage_path3.'/sync_setter.php', $storage_path2.'/sync_setter.php');
        File::copy($storage_path3.'/sync_worker.php', $storage_path2.'/sync_worker.php');
        $prepend = '<?php ';
        $prepend = $prepend.'$app_key="'.$project->dropbox_app_key.'"; ';
        $prepend = $prepend.'$app_secret="'.$project->dropbox_app_secret.'"; ';
        $prepend = $prepend.'$access_token="'.$project->dropbox_access_token.'"; ';
        $prepend = $prepend.'$project_name="'.$project->project_name.'"; ';
        foreach($forms as $i => $form){
            $prepend = $prepend.'$form_attr["data"]['.$i.']["folder"] = "'.$form->form_name.'";';
            foreach($form->formInput as $j => $formInput){
                $prepend = $prepend.'$form_attr["data"]['.$i.']["attribute"]['.$j.'] = "'.$formInput->input_key.'";';
            }
        }  
        $prepend = $prepend.'if(!isset($_POST["server_name"])){ ?>';
        $prepend = $prepend.'<script>var form_attr = <?php echo json_encode($form_attr); ?>;</script> <?php } ?>';
        $file = storage_path('app/public/'.$project_path.'/sync_setter.php');
        $fileContents = file_get_contents($file);
        file_put_contents($file, $prepend . $fileContents);  

        $zip_file = $project->project_name.'-master.zip';
        $zip = new \ZipArchive();
        $zip->open($storage_path5."/".$zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $path = storage_path('app/public/'.$project_path);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file)
        {
            $filePath     = $file->getRealPath();
            $relativePath = $project->project_name.'-master/'. substr($filePath, strlen($path));
            if (!$file->isDir()) {
                $zip->addFile($filePath, $relativePath);
            }else {
                if($relativePath !== false)
                    $zip->addEmptyDir($relativePath);
            }
        }
        $zip->close();


        $app = new DropboxApp($project->dropbox_app_key, $project->dropbox_app_secret, $project->dropbox_access_token);
        $dropbox = new Dropbox($app);
        $project_name = $project->project_name;
        foreach($forms as $i => $form){
            $folder_name = $form->form_name;
            try{
                $project_folder = $dropbox->getMetadata("/".$project_name);
            }catch(\Exception $e){
                $project_folder = $dropbox->createFolder("/".$project_name);
            }

            try{
                $folder = $dropbox->getMetadata("/".$project_name."/".$folder_name);
            }catch(\Exception $e){
                $folder = $dropbox->createFolder("/".$project_name."/".$folder_name);
            }

            try{
                $folder = $dropbox->getMetadata("/".$project_name."/".$folder_name."/synchronized");
                $folder = $dropbox->getMetadata("/".$project_name."/".$folder_name."/unsynchronized");
            }catch(\Exception $e){
                $folder = $dropbox->createFolder("/".$project_name."/".$folder_name."/synchronized");
                $folder = $dropbox->createFolder("/".$project_name."/".$folder_name."/unsynchronized");
            }
        }
        
        return response()->download($storage_path5."/".$zip_file);
    }

    public function export($id, $share_path)
    {
        $form = Form::with('formInput')->find($id);
        $project = Project::find($form->project_id);

        $request = (array)$form;
        $request['app_key'] = $project->dropbox_app_key;
        $request['app_secret'] = $project->dropbox_app_secret;
        $request['access_token'] = $project->dropbox_access_token;
        $request['project_name'] = $project->project_name;
        $request['title'] = $form->title;
        $request['description'] = $form->description;
        $request['form_name'] = $form->form_name;
        $request['formInput'] = $form->formInput;
        $request = (object)$request;

        $htmls = $this->createHtml($request);
        $filename = $form->form_name.".php";
        Storage::disk('public')->put($share_path."/".$filename, $htmls);
    }

    public function createHtml($request){
        $htmls = "";
        $htmls = $htmls.$this->createPhpSubmit($request);
        $htmls = $htmls."<html>";
        $htmls = $htmls.$this->createHeader();
        $htmls = $htmls.'<body>';
        $htmls = $htmls.'<nav class="navbar navbar-expand-lg navbar-light bg-light"> ';
        $htmls = $htmls.'    <div class="container"> ';
        $htmls = $htmls.'        <a href="<?php $link = $_SERVER["PHP_SELF"]; $link = substr($link, 1); $link = substr($link, 0, strpos($link, "/"));  echo "/".$link;?>"> <button type="button" id="sidebarCollapse" class="btn btn-info"> ';
        $htmls = $htmls.'            <i class="fa fa-home"></i> ';
        $htmls = $htmls.'            <span>Back</span> ';
        $htmls = $htmls.'        </button></a> ';
        $htmls = $htmls.'        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> ';
        $htmls = $htmls.'            <i class="fa fa-user"></i> ';
        $htmls = $htmls.'        </button> ';
        $htmls = $htmls.'        <div class="collapse navbar-collapse" id="navbarSupportedContent"> ';
        $htmls = $htmls.'            <ul class="nav navbar-nav ml-auto"> ';
        $htmls = $htmls.'                <li class="nav-item dropdown"> ';
        $htmls = $htmls.'                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ';
        $htmls = $htmls.'                       <?php echo $_SESSION["display_name"]; ?> ';
        $htmls = $htmls.'                    </a> ';
        $htmls = $htmls.'                    <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="navbarDropdown"> ';
        $htmls = $htmls.'                       <a class="dropdown-item" href="?logout=yes">Logout</a> ';
        $htmls = $htmls.'                    </div> ';
        $htmls = $htmls.'                </li> ';
        $htmls = $htmls.'            </ul> ';
        $htmls = $htmls.'        </div> ';
        $htmls = $htmls.'    </div> ';
        $htmls = $htmls.'</nav> ';
        $htmls = $htmls.'<div class="container">';
        $htmls = $htmls.'<div class="row" style="margin-top:50px;">';
        $htmls = $htmls.'<div class="col-md-2"></div>';
        $htmls = $htmls.'<div id="card" class="col-md-8 shadow-sm" style="margin-top:25px">';
        $htmls = $htmls.'<div>';
        $htmls = $htmls.'<div class="form-group card-title">';
        $htmls = $htmls.'<h3>'.$request->title.'</h3>';
        if($request->description!=null) $htmls = $htmls.'<label>'.$request->description.'</label>';
        $htmls = $htmls.'</div>';
        $htmls = $htmls.'<form action="#" method="POST" enctype="multipart/form-data">';
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
        $head = $head.'<script src="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js"></script>';
        $head = $head.'<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/css/select2.min.css">';
        $head = $head.'<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">';
        $head = $head.'<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
        $head = $head.'<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
        
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
        $php = $php.    '$app_key = "'.$request->app_key.'";  ';
        $php = $php.    '$app_secret = "'.$request->app_secret.'"; ';
        $php = $php.    '$app = new DropboxApp($app_key, $app_secret); ';
        $php = $php.    '$dropbox = new Dropbox($app); ';
        $php = $php.    '$authHelper = $dropbox->getAuthHelper(); ';
        $php = $php.    'session_start(); ';

        $php = $php.    'if(isset($_GET["logout"])=="yes"){ ';
        $php = $php.    '    session_destroy(); ';
        $php = $php.    '    header("Location: ".$_SERVER["PHP_SELF"]);     ';     
        $php = $php.    '    exit; ';
        $php = $php.    '} ';

        $php = $php.    'else if(isset($_POST["access_token"])){  ';
        $php = $php.    '    try{ ';
        $php = $php.    '        $code = $_POST["access_token"]; ';
        $php = $php.    '        $authToken = $authHelper->getAccessToken($code); ';
        $php = $php.    '        $access_token =  $authToken->getToken(); ';
        $php = $php.    '        $app = new DropboxApp($app_key , $app_secret, $access_token); ';
        $php = $php.    '        $dropbox = new Dropbox($app);  ';
        $php = $php.    '        $response = $dropbox->postToAPI("/sharing/share_folder",["path" => "/ "]); ';
        $php = $php.    '   } catch(Exception $e){ ';  
        $php = $php.    '        $error = json_decode($e->getMessage(),true); ';  
        $php = $php.    '        $error_share_entire = \'Error in call to API function "sharing/share_folder": You can’t share an entire Dropbox.\'; ';       
        $php = $php.    '        if($e->getMessage() == $error_share_entire) { $account = $dropbox->getCurrentAccount(); $_SESSION["display_name"] = $account->getDisplayName(); $_SESSION["access_token"] = $access_token; }';     
        $php = $php.    '        else if ($error["error"][".tag"] == "email_unverified") $_SESSION["login_error"] = "Login Fail ".$error["user_message"]["text"]." Verify your email here : https://www.dropbox.com/share/folders"; ';  
        $php = $php.    '        else $_SESSION["login_error"] = "Login Fail ".$e->getMessage(); ';          
        $php = $php.    '        header("Location: ".$_SERVER["PHP_SELF"]); ';           
        $php = $php.    '        exit; ';  
        $php = $php.    '   } ';
        $php = $php.    '   $account = $dropbox->getCurrentAccount(); ';
        $php = $php.    '   $_SESSION["display_name"] = $account->getDisplayName(); ';
        $php = $php.    '   $_SESSION["access_token"] = $access_token; ';
        $php = $php.    '   header("Location: ".$_SERVER["PHP_SELF"]);  ';
        $php = $php.    '   exit; ';
        $php = $php.    '} ';

        $php = $php.    'else if(!isset($_SESSION["access_token"])){ ';
        $php = $php.    '   include "dropbox/login.php";  ';
        $php = $php.    '} ';

        $php = $php.    'else { ';
        $php = $php.    '   $access_token = $_SESSION["access_token"]; ';
        
    
        $php = $php.    'if(isset($_POST["input_value"])){ ';
        $php = $php.        '$folder_name = "'.$request->form_name.'"; ';
        $php = $php.        '$values = $_POST["input_value"]; ';
        $php = $php.        '$labels = $_POST["input_label"]; ';
        $php = $php.        '$row = array(); ';
        $php = $php.        'mkdir("dropbox/tmp"); ';
        $php = $php.        'mkdir("dropbox/tmp/attachment"); ';

        $php = $php.        '$j=0;  ';
        $php = $php.        '$file_names = $_FILES["input_value"]["name"]; ';
        $php = $php.        '$file_keys = array_keys($file_names); ';
        $php = $php.        'foreach($file_names as $file_name){  ';
        $php = $php.        '    $new_value = array($file_keys[$j] => $file_name);  ';
        $php = $php.        '    $values = $values + $new_value; ';
        $php = $php.        '    $j++; ';
        $php = $php.        '}  ';
        $php = $php.        '$keys = array_keys($values); ';

        $php = $php.        '$i = 0; ';
        $php = $php.        'foreach($values as $value){ ';      
        $php = $php.            'if(is_array($value)) $attr = array($labels[$keys[$i]] => implode(", ",$value));  ';
        $php = $php.            'else $attr = array($labels[$keys[$i]] => $value); ';
        $php = $php.            '$row = $row + $attr; ';
        $php = $php.            '$i++; ';
        $php = $php.        '} ';
        
        $php = $php.        '$myJSON = json_encode($row); ';
        $php = $php.        '$fp = fopen("dropbox/tmp/data.json", "w"); ';
        $php = $php.        'fwrite($fp, $myJSON); ';
        $php = $php.        'fclose($fp); ';
        $php = $php.        '$app = new DropboxApp($app_key, $app_secret, $access_token); ';
        $php = $php.        '$dropbox = new Dropbox($app); ';

        $php = $php.        '$folder = $dropbox->createFolder("/".$folder_name, true);  ';
        $php = $php.        '$data_folder_name= $folder->getName();  ';
        $php = $php.        '$folder = $dropbox->createFolder("/".$data_folder_name."/".$folder_name, true);  ';

        $php = $php.        '$path = "/".$data_folder_name."/".$folder_name."/data.json"; ';
        $php = $php.        '$dropboxFile = new DropboxFile("dropbox/tmp/data.json");  ';
        $php = $php.        '$file = $dropbox->upload($dropboxFile, $path, ["autorename" => true]); ';
        $php = $php.        '$attachment_folder = $dropbox->createFolder("/".$data_folder_name."/".$folder_name."/attachment", true); ';
        
        $php = $php.        '$k=0; ';
        $php = $php.        '$file_names = $_FILES["input_value"]["name"]; ';
        $php = $php.        '$file_keys = array_keys($file_names); ';
        $php = $php.        '$files = $_FILES["input_value"]["tmp_name"]; ';    
        
        $php = $php.        'foreach($files as $file){ ';
        $php = $php.        '   $file_name = basename($_FILES["input_value"]["name"][$file_keys[$k]]); ';
        $php = $php.        '   $tmp = explode(".", $file_name); $ext = end($tmp); ';
        $php = $php.        '   $target_file = "dropbox/tmp/attachment/" .$labels[$file_keys[$k]].".".$ext; ';   
        $php = $php.        '   move_uploaded_file($file, $target_file); ';
        $php = $php.        '   $path = "/".$data_folder_name."/".$folder_name."/attachment/".$labels[$file_keys[$k]].".".$ext; ';
        $php = $php.        '   $dropboxFile = new DropboxFile($target_file); ';
        $php = $php.        '   $file = $dropbox->upload($dropboxFile, $path, ["autorename" => true]); ';
        $php = $php.        '   $k++; ';
        $php = $php.        '   unlink($target_file); ';
        $php = $php.        '} ';
        $php = $php.        'unlink("dropbox/tmp/data.json"); ';

        $php = $php.        '$response = $dropbox->postToAPI("/sharing/share_folder",[ ';
        $php = $php.        '    "path" => "/".$data_folder_name."/".$folder_name, ';
        $php = $php.        '    "acl_update_policy" => "editors", ';
        $php = $php.        '    "force_async" => false, ';
        $php = $php.        '    "member_policy" => "anyone", ';
        $php = $php.        '    "access_inheritance" => "inherit" ';
        $php = $php.        ']); ';
        $php = $php.        '$data = $response->getDecodedBody(); ';
        $php = $php.        '$shared_folder_id = $data["shared_folder_id"]; ';
        $php = $php.        '$member = json_decode(json_encode(array( ';
        $php = $php.        '    ".tag" => "email", ';
        $php = $php.        '    "email" => "gusyana124@gmail.com" ';
        $php = $php.        ')), true); ';

        $php = $php.        '$response = $dropbox->postToAPI("/sharing/add_folder_member", [ ';
        $php = $php.        '    "shared_folder_id" => $shared_folder_id, ';
        $php = $php.        '    "members" => array( ';
        $php = $php.        '        json_decode(json_encode(array( ';
        $php = $php.        '            "member"=> $member, ';
        $php = $php.        '            "access_level" => "editor" ';
        $php = $php.        '        )), true) ';
        $php = $php.        '), ';
        $php = $php.        '"quiet" => true, ';
        $php = $php.        ']); ';
        $php = $php.        '$data = $response->getDecodedBody(); ';

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
        $php = $php.    'if (isset($_SESSION["success"])) { ';
        $php = $php.        'unset($_SESSION["success"]); ';
        $php = $php.'?> ' ;
        $php = $php.        '<script> alert("Input Data Sukses"); </script> ';
        $php = $php.'<?php ';
        $php = $php.            '} ';
        $php = $php.        '} ';
        $php = $php.    '} ';
        $php = $php.'?> ';
        $php = $php.'<script>$(".select2").select2({ width: "100%" });</script> ';
        $php = $php.'<?php ';
        $php = $php.'if (file_exists("dropbox/tmp/attachment")) rmdir("dropbox/tmp/attachment"); ';
        $php = $php.'if (file_exists("dropbox/tmp")) rmdir("dropbox/tmp"); ';
        $php = $php.'?> ';
        return $php;
    }

    public function createCss(){
        $css = '<style>';
        $css = $css.'#card {border-radius:5px;background-color:white;padding-top:30px;padding-bottom:0px;padding-right:0px;padding-left:0px;margin-bottom: 10px;}';
        $css = $css.'.card-title{padding-right: 30px;padding-left: 30px; }';
        $css = $css.'.card-input { padding-top:15px; padding-bottom:5px;padding-right: 30px;padding-left: 30px;}';
        $css = $css.'.select2-selection__arrow {margin-top:3px!important;}';
        $css = $css.'.select2-selection.select2-selection--single {height: 36px!important; padding:3px !important;}';
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
