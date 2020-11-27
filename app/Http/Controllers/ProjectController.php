<?php

namespace App\Http\Controllers;

use App\Project;
use App\Form;
use App\FormInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $projects = Project::where('user_id', Auth::user()->id)->orderBy('id','DESC')->get();
        return view('project-show-all', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('project-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|alpha_dash|unique:projects',
            'dropbox_app_key' => 'required',
            'dropbox_app_secret' => 'required',
            'dropbox_access_token' => 'required',
            'form_type' => 'required'
        ]);
                
        $project = new Project();
        $project->project_name = $request->project_name;
        $project->dropbox_app_key = $request->dropbox_app_key;
        $project->dropbox_app_secret = $request->dropbox_app_secret;
        $project->dropbox_access_token = $request->dropbox_access_token;
        $project->form_type = $request->form_type;
        $project->user_id = Auth::user()->id;

        
        $project->save();
        return redirect('all-project/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        return view('project-edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'project_name' => 'required|alpha_dash|unique:projects,project_name,'.$request->id,
            'dropbox_app_key' => 'required',
            'dropbox_app_secret' => 'required',
            'dropbox_access_token' => 'required',
            'form_type' => 'required'
        ]);
        $project = Project::find($request->id);
        $project->project_name = $request->project_name;
        $project->dropbox_app_key = $request->dropbox_app_key;
        $project->dropbox_app_secret = $request->dropbox_app_secret;
        $project->dropbox_access_token = $request->dropbox_access_token;
        $project->form_type = $request->form_type;
        $project->save();
        return redirect('all-project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $forms = Form::where('project_id', $id)->get();
        foreach($forms as $form){
            $inputs = FormInput::where('form_id', $form->id)->delete();
        }
        $forms = Form::where('project_id', $id)->delete();
        $project = Project::findOrFail($id)->delete();
        return redirect('/all-project');
    }
}
