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

    public function index()
    {
        $inputTypes = InputType::get();
        return view('form-create', compact('inputTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'html' => 'required'
        ]);
        $form = new Form();
        $form->title = $request->title;
        $form->description = $request->description;
        $form->id_user = Auth::user()->id;
        $form->save();
        $last_form_id = Form::max('id');
        foreach($request->html as $html){
            $form_input = new FormInput();
            $form_input->html = $html;
            $form_input->form_id = $last_form_id;
            $form_input->save();
        }

        $htmls = $this->createHtml($request->html, $request->title, $request->description);

        $filename = $request->title.".php";
        Storage::put($filename, $htmls);
        return redirect('show-form/'.$last_form_id);
    }

    public function createHtml($requestHtmls, $title, $description){
        $htmls = "<html>";
        $htmls = $htmls.$this->createHeader();
        $htmls = $htmls.'<body>';
        $htmls = $htmls.'<div class="container">';
        $htmls = $htmls.'<div class="row" style="margin-top:50px;">';
        $htmls = $htmls.'<div class="col-md-2"></div>';
        $htmls = $htmls.'<div id="card" class="col-md-8 shadow-sm" style="margin-top:25px">';
        $htmls = $htmls.'<div>';
        $htmls = $htmls.'<div class="form-group card-title">';
        $htmls = $htmls.'<h3>'.$title.'</h3>';
        if($description!=null) $htmls = $htmls.'<label>'.$description.'</label>';
        $htmls = $htmls.'</div>';
        $htmls = $htmls.'<form action="#" method="POST">';
        foreach($requestHtmls as $html){
            $htmls = $htmls.$html;
        }
        $htmls= $htmls.'<button type="submit" class="btn btn-success">Submit</button>';
        $htmls = $htmls.'</form>';
        $htmls = $htmls.'</div></div></div></div>';
        $htmls = $htmls.'</body>';
        $htmls = $htmls.'</html>';
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

    public function showAll(){
        $forms = Form::where('id_user', Auth::user()->id)->get();
        return view('form-show-all2', compact('forms'));
    }

}
