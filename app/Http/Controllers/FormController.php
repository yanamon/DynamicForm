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
            'form_name' => 'required|alpha_dash'
        ]);
        $project = Project::find($request->project_id);
        $form = new Form();
        $form->title = $request->title;
        $form->description = $request->description;
        $form->project_id = $request->project_id;
        $form->form_name = $request->form_name;
        $form->form_type = $request->form_type;
        $auth_file = $request->file('json_identifier');
        if(!empty($auth_file)){
            $auth_file = file_get_contents($auth_file);
            $auths = json_decode($auth_file);
            foreach($auths as  $key => $auth){
                foreach($auth as  $key2 => $aut){
                    $auth_input_key = $key2;
                    break;
                }
                break;
            }
            $user_path = 'file/'.Auth::user()->id.'/';
            Storage::makeDirectory($user_path);
            $project_path = $user_path.$project->project_name;
            Storage::makeDirectory($project_path);
            $form_path = $user_path.$project->project_name.'/'.$request->form_name;;
            Storage::makeDirectory($form_path);
            $path = $request->file('json_identifier')->storeAs(
                $form_path, 'auth.json'
            );
            $form->auth_file = $form_path.'/auth.json';
        }
        $form->save();
        $last_form_id = Form::max('id');

        
        $form_input = new FormInput();
        if(!empty($auth_file)) $form_input->input_key = $auth_input_key;
        $form_input->form_id = $last_form_id;
        $form_input->save();
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
        $auth_file = $request->file('json_identifier');
        if(!empty($auth_file)){
            $auth_file = file_get_contents($auth_file);
            $auths = json_decode($auth_file);
            foreach($auths as  $key => $auth){
                foreach($auth as  $key2 => $aut){
                    $auth_input_key = $key2;
                    break;
                }
                break;
            }
            $form_path = $form->auth_file;
            Storage::delete($form->auth_file);
            $form_path = substr($form_path, 0, -9);
            $path = $request->file('json_identifier')->storeAs(
                $form_path, 'auth.json'
            );
            $form->auth_file = $form_path.'/auth.json';
        }
        $form->save();
        
        FormInput::where('form_id', $request->id_edit)->delete();

        
        $form_input = new FormInput();
        $form_input->input_key = $auth_input_key;
        $form_input->form_id = $request->id_edit;
        $form_input->save();
        foreach($request->html as $i => $html){
            $form_input = new FormInput();
            $form_input->html = $html;
            $form_input->input_key = $request->input_key[$i];
            $form_input->form_id = $request->id_edit;
            $form_input->save();
        }
        return redirect('project/'.$request->project_id);

    }

    public function createMysql($project_name, $forms){
        $sql = "";
        
        $sql = $sql."create DATABASE `".$project_name."`; ".PHP_EOL;
        $sql = $sql."USE `".$project_name."`; ".PHP_EOL;

        foreach($forms as $i => $form){
            $sql = $sql."DROP TABLE IF EXISTS `".$form->form_name."`; ".PHP_EOL;
            $sql = $sql."create TABLE `".$form->form_name."` ( ".PHP_EOL;
            $sql = $sql."`id` int(12) NOT NULL AUTO_INCREMENT, ".PHP_EOL;
            foreach($form->formInput as $j => $formInput){
                $sql = $sql."`".$formInput->input_key."` varchar(255) DEFAULT NULL, ".PHP_EOL;
            }
            $sql = $sql."PRIMARY KEY (`id`) ".PHP_EOL;
            $sql = $sql.") ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1; ".PHP_EOL;
        }
  
        return $sql;
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
        $storage_path3 = storage_path('app/sync/');
        $storage_path2 = storage_path('app/public/' . $project_path);
        $storage_path4 = storage_path('app/public/' . $share_path);
        $storage_path5 = storage_path('app/public/' . $user_path);
        File::copyDirectory($storage_path1 , $storage_path2);
        File::copyDirectory($storage_path1 , $storage_path4);
          
        foreach($forms as $i => $form){
            $this->export($form->id, $share_path, $project_path);
        }
        if(!empty($request->export_sql)){
            $sql = $this->createMysql($project->project_name, $forms);
            $sql_file_name = $project->project_name.".sql";
            Storage::disk('public')->put($project_path."/".$sql_file_name, $sql);
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

        File::copyDirectory($storage_path3, $storage_path2);
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
        $prepend = $prepend.'if(!isset($_POST["server_name"]) && !isset($_POST["request_update_data"]) ){ ?>';
        $prepend = $prepend.'<script>var form_attr = <?php echo json_encode($form_attr); ?>;</script> <?php } ?>';
        $file = storage_path('app/public/'.$project_path.'/sync/sync_setter.php');
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

    public function export($id, $share_path, $project_path)
    {
        $form = Form::with('formInput')->find($id);
        $project = Project::find($form->project_id);
        $app = new DropboxApp($project->dropbox_app_key, $project->dropbox_app_secret, $project->dropbox_access_token);
        $dropbox = new Dropbox($app);
        $account = $dropbox->getCurrentAccount(); 
        $project_email = $account->getEmail();

        $request = (array)$form;
        $request['project_email'] = $project_email;
        $request['app_key'] = $project->dropbox_app_key;
        $request['app_secret'] = $project->dropbox_app_secret;
        $request['access_token'] = $project->dropbox_access_token;
        $request['project_name'] = $project->project_name;
        $request['title'] = $form->title;
        $request['description'] = $form->description;
        $request['form_name'] = $form->form_name;
        $request['form_type'] = $form->form_type;
        $request['auth_file'] = $form->auth_file;
        $request['formInput'] = $form->formInput;
        $request = (object)$request;

        if(!empty($request->auth_file)){
            Storage::disk('public')->makeDirectory($project_path."/dropbox/auth/".$form->form_name);
            Storage::disk('public')->makeDirectory($share_path."/dropbox/auth/".$form->form_name);
            File::copy(storage_path('app/'.$form->auth_file), storage_path('app/public/'.$project_path."/dropbox/auth/".$form->form_name."/auth.json"));
            File::copy(storage_path('app/'.$form->auth_file), storage_path('app/public/'.$share_path."/dropbox/auth/".$form->form_name."/auth.json"));

        }

        $htmls = $this->createHtml($request);
        $filename = $form->form_name.".php";
        Storage::disk('public')->put($share_path."/".$filename, $htmls);
        Storage::disk('public')->put($project_path."/".$filename, $htmls);
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

        if($request->form_type!='Without User Dropbox'){
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
        }

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
        $htmls= $htmls.'<div class="form-group card-title" style="margin-bottom:30px;"><button id="btn-submit-form" type="submit" class="col-md-12 btn btn-success btn-block">Submit</button></div>';
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
        $php = "\n\n\n";
        $php = $php.'<?php ';
        $php = $php.    'require_once "dropbox/autoload.php"; ';
        $php = $php.    'use Kunnu\Dropbox\DropboxFile; ';
        $php = $php.    'use Kunnu\Dropbox\DropboxApp; ';
        $php = $php.    'use Kunnu\Dropbox\Dropbox; ';

        if(!empty($request->auth_file)) $php = $php.    '$auth_file = "dropbox/auth/'.$request->form_name.'/auth.json";  ';
        
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

        $php = $php.    'else if(isset($_POST["access_token"]) && isset($_POST["password"]) ){    ';  
        $php = $php.    '    $password = $_POST["password"]; ';
        $php = $php.    '    $logged_in = false; ';
        $php = $php.    '    $verified = false; ';
        $php = $php.    '    try{          ';
        $php = $php.    '        $code = $_POST["access_token"]; ';     
        $php = $php.    '        $authToken = $authHelper->getAccessToken($code); ';   
        $php = $php.    '         $access_token =  $authToken->getToken(); ';  
        $php = $php.    '         $app = new DropboxApp($app_key , $app_secret, $access_token); ';       
        $php = $php.    '        $dropbox = new Dropbox($app);         ';  
        $php = $php.    '        $response = $dropbox->postToAPI("/sharing/share_folder",["path" => "/ "]); ';  
        $php = $php.    '    } catch(Exception $e){ ';
        $php = $php.    '        $error = json_decode($e->getMessage(),true); ';         
        $php = $php.    '        $error_share_entire = \'Error in call to API function "sharing/share_folder": You can’t share an entire Dropbox.\'; ';         
        $php = $php.    '        if($e->getMessage() == $error_share_entire) $verified = true; '; 
        $php = $php.    '         else if ($error["error"][".tag"] == "email_unverified") $_SESSION["login_error"] = "Login Fail ".$error["user_message"]["text"]." Verify your email here : https://www.dropbox.com/share/folders"; ';         
        $php = $php.    '        else $_SESSION["login_error"] = "Login Fail ".$e->getMessage(); ';         
        $php = $php.    '    } ';    
        $php = $php.    '    if(!$verified) { ';
        $php = $php.    '        header("Location: ".$_SERVER["PHP_SELF"]); ';         
        $php = $php.    '        exit; ';   
        $php = $php.    '    } ';
        $php = $php.    '    $auth_file = file_get_contents($auth_file); ';
        $php = $php.    '    $auths = json_decode($auth_file); ';
        $php = $php.    '    $auth_keys = array_keys((array)$auths[0]); ';
        $php = $php.    '    foreach($auth_keys as $i => $auth_key){ ';
        $php = $php.    '        if($i == 0) $username_key = $auth_key; ';
        $php = $php.    '        else  $password_key = $auth_key; ';
        $php = $php.    '    } ';
        $php = $php.    '    foreach($auths as $auth){ ';
        $php = $php.    '        $auth = (array)$auth; ';
        $php = $php.    '        if($password == $auth[$password_key]) { ';
        $php = $php.    '            $logged_in = true; ';
        $php = $php.    '            $username =  $auth[$username_key]; ';
        $php = $php.    '        } ';
        $php = $php.    '    } ';
        $php = $php.    '    if($logged_in){ ';
        $php = $php.    '        $account = $dropbox->getCurrentAccount(); ';   
        $php = $php.    '        $_SESSION["display_name_key"] = $username_key; ';    
        $php = $php.    '        $_SESSION["display_name"] = $username; ';    
        $php = $php.    '        $_SESSION["access_token"] = $access_token; ';    
        $php = $php.    '        header("Location: ".$_SERVER["PHP_SELF"]); ';     
        $php = $php.    '        exit; '; 
        $php = $php.    '    } ';
        $php = $php.    '    else{ ';
        $php = $php.    '        $_SESSION["login_error"] = "Login Fail Wrong Key"; ';        
        $php = $php.    '        header("Location: ".$_SERVER["PHP_SELF"]); ';         
        $php = $php.    '        exit; ';   
        $php = $php.    '    } ';
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

        if($request->form_type!='Without User Dropbox'){
            $php = $php.    'else if(!isset($_SESSION["access_token"])){ ';
            if(!empty($request->auth_file)) $php = $php.    '   include "dropbox/register.php";  ';
            else $php = $php.    '   include "dropbox/login.php";  ';
            $php = $php.    '} ';
        }

        $php = $php.    'else { ';
        
        if($request->form_type=='Without User Dropbox') $php = $php.    '   $access_token = "'.$request->access_token.'"; ';
        else $php = $php.    '   $access_token = $_SESSION["access_token"]; ';

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

          
        $php = $php.'if(isset($server)){ ';
        $php = $php.'    $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass); ';
        $php = $php.'    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); ';
        $php = $php.'    $direct_attributes = ""; ';
        $php = $php.'    $direct_values = ""; ';
        $php = $php.'    $excepted_attrs = array(); ';
        
        $php = $php.'    if(isset($auth_file)){ ';
        $php = $php.'        $attr = array($_SESSION["display_name_key"] => $_SESSION["display_name"]); ';
        $php = $php.'        $row = $row + $attr; ';
        $php = $php.'    } ';
        
        $php = $php.'    foreach($direct_to_db_folder as $j => $attr){ ';
        $php = $php.'        $direct_attributes = $direct_attributes.$direct_to_db_table[$j]; ';
        $php = $php.'        $value_index = array_search($attr, $labels); ';
        $php = $php.'        $data = str_replace(\'"\', \'\\"\', $values[$value_index]); ';
        $php = $php.'        $direct_values = $direct_values.\'"\'.$data.\'"\';  ';
        
                
        $php = $php.'        array_push($excepted_attrs,$labels[$value_index]); ';
        $php = $php.'        if($j < count($direct_to_db_folder)-1) { ';
        $php = $php.'            $direct_attributes = $direct_attributes.","; ';
        $php = $php.'            $direct_values = $direct_values.","; ';
        $php = $php.'        } ';
        $php = $php.'    } ';
        $php = $php.'    $query = "INSERT INTO ".$table_name."(".$direct_attributes.") VALUES(".$direct_values.")"; ';
        $php = $php.'    $sql = $conn->prepare($query); ';
        $php = $php.'    $sql->execute(); ';

        $php = $php.'   $lastInsertId = $conn->lastInsertId();  ';
        $php = $php.'   $query ="SHOW KEYS FROM ".$table_name." WHERE Key_name = \'PRIMARY\'"; ';
        $php = $php.'   $sql = $conn->prepare($query); ';
        $php = $php.'   $sql->execute(); ';
        $php = $php.'   $result = $sql->fetchAll(); ';
        $php = $php.'   foreach( $result as $baris ) { ';
        $php = $php.'       $primary_column = $baris["Column_name"];  ';
        $php = $php.'   } ';
        $php = $php.'   $attr = array($primary_column => $lastInsertId);  ';
        $php = $php.'   $row = $row + $attr;  ';

        $php = $php.'    $json_name = "update.json"; ';
        $php = $php.     '$i = 0; ';
        $php = $php.     'foreach($values as $value){ ';   
        $php = $php.        '$is_value = true; '; 
        $php = $php.        'foreach($excepted_attrs as $excepted_attr){ ';  
        $php = $php.            'if($labels[$i] == $excepted_attr) $is_value = false; ';    
        $php = $php.        '} ';
        $php = $php.        'if($is_value){ ';
        $php = $php.            'if(is_array($value)) $attr = array($labels[$keys[$i]] => implode(", ",$value));  ';
        $php = $php.            'else $attr = array($labels[$keys[$i]] => $value); ';
        $php = $php.            '$row = $row + $attr; ';
        $php = $php.        '} ';
        $php = $php.         '$i++; ';
        $php = $php.     '} ';
        $php = $php.'} ';

        $php = $php.'else{ ';
        $php = $php.'    $json_name = "insert.json"; ';
        $php = $php.        '$i = 0; ';
        
        $php = $php.'    if(isset($auth_file)){ ';
        $php = $php.'        $attr = array($_SESSION["display_name_key"] => $_SESSION["display_name"]); ';
        $php = $php.'        $row = $row + $attr; ';
        $php = $php.'    } ';

        $php = $php.        'foreach($values as $value){ ';      
        $php = $php.            'if(is_array($value)) $attr = array($labels[$keys[$i]] => implode(", ",$value));  ';
        $php = $php.            'else $attr = array($labels[$keys[$i]] => $value); ';
        $php = $php.            '$row = $row + $attr; ';
        $php = $php.            '$i++; ';
        $php = $php.        '} ';
        $php = $php.'} ';
      
        $php = $php.        '$myJSON = json_encode($row); ';
        $php = $php.        '$fp = fopen("dropbox/tmp/".$json_name, "w"); ';
        $php = $php.        'fwrite($fp, $myJSON); ';
        $php = $php.        'fclose($fp); ';
        $php = $php.        '$app = new DropboxApp($app_key, $app_secret, $access_token); ';
        $php = $php.        '$dropbox = new Dropbox($app); ';

        $php = $php.        '$folder = $dropbox->createFolder("/".$folder_name, true);  ';
        $php = $php.        '$data_folder_name= $folder->getName();  ';
        $php = $php.        '$folder = $dropbox->createFolder("/".$data_folder_name."/".$folder_name, true);  ';

        $php = $php.        '$path = "/".$data_folder_name."/".$folder_name."/".$json_name; ';
        $php = $php.        '$dropboxFile = new DropboxFile("dropbox/tmp/".$json_name);  ';
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
        $php = $php.        '   unset($dropboxFile); ';
        $php = $php.        '   unlink($target_file); ';
        $php = $php.        '} ';
        $php = $php.        'unset($dropboxFile); '; 
        $php = $php.        'unlink("dropbox/tmp/".$json_name); ';

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
        $php = $php.        '    "email" => "'.$request->project_email.'" ';
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

        $php = $php.'<script> ';
        $php = $php.'    $("#btn-submit-form").click(function() { ';
        $php = $php.'        $(".checkbox-validation").each(function(e){ ';
        $php = $php.'            var flag = false; ';
        $php = $php.'            $(this).find(":input").each(function(e){ ';
        $php = $php.'                if ($(this).prop("checked") == true){ ';
        $php = $php.'                    flag=true; ';
        $php = $php.'                } ';
        $php = $php.'            }); ';
        $php = $php.'            if(!flag){ ';
        $php = $php.'                $(this).find(":input").each(function(e){ ';
        $php = $php.'                     var checkbox = $(this); ';
        $php = $php.'                    var element = checkbox[0]; ';
        $php = $php.'                    element.setCustomValidity("At least one checkbox must be selected."); ';
        $php = $php.'                }); ';
        $php = $php.'             } ';
        $php = $php.'        }); ';
        $php = $php.'    }); ';
        $php = $php.'    $("input[type=checkbox]").on("change", function() { ';
        $php = $php.'        $(this).closest(".checkbox-validation").find(":input").each(function(e){ ';
        $php = $php.'            var checkbox = $(this); ';
        $php = $php.'            var element = checkbox[0]; ';
        $php = $php.'            element.setCustomValidity(""); ';
        $php = $php.'        }); ';
        $php = $php.'    }); ';
        $php = $php.'</script> ';

        return $php;
    }

    public function createCss(){
        $css = '<style>';
        $css = $css.'#card {border-radius:5px;background-color:white;padding-top:30px;padding-bottom:0px;padding-right:0px;padding-left:0px;margin-bottom: 10px;}';
        $css = $css.'.card-title{padding-right: 30px;padding-left: 30px; }';
        $css = $css.'.card-input { padding-top:15px; padding-bottom:5px;padding-right: 30px;padding-left: 30px;}';
        $css = $css.'.select2-selection__arrow {margin-top:3px!important;}';
        $css = $css.'.select2-selection.select2-selection--single {height: 36px!important; padding:3px !important;}';
        $css = $css.'input[type=radio],input[type=checkbox] {margin-right:5px;}';
        $css = $css.'.check{border: 1px solid rgb(206, 212, 218);padding: 10px 0px;}';
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
