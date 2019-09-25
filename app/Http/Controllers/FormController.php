<?php

namespace App\Http\Controllers;

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
        return redirect('show-form/'.$last_form_id);
    }

    public function showAll(){
        $forms = Form::where('id_user', Auth::user()->id)->get();
        return view('form-show-all', compact('forms'));
    }

}
