@extends('layouts.dynamic-form-layout')
@section('body')

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2"></div>
        <div id="card" class="col-md-8 shadow-sm">
            <div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
                <div>
                    <input type="hidden" name="id_user" value="{{$sub_form->id_user}}">
                    <input type="hidden" name="form_id" value="{{$sub_form->id}}">
                    <div class="form-group card-title">
                        <h3>{{$sub_form->title}}</h3>
                        @if($sub_form->description!=null)<label>{{$sub_form->description}}</label>@endif
                    </div>  
                    @foreach($sub_form->subFormInput as $input)
                        {!!$input->html!!}
                    @endforeach
                    <div class="form-group card-title" style="margin-bottom:30px;">
                        <button type="button" class="col-md-12 btn btn-success btn-block">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-1">
            <button id="btn-add" data-toggle="modal" data-target="#export-modal" title="Export Form" class="btn btn-info btn-circle" type="button"><i class="fa fa-arrow-right fa-lg"></i></button>
        </div>     --}}
    </div>
</div>

<!-- Export Modal -->
<div class="modal" id="export-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Export Form</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>     
            <div class="modal-body">
                Export this form?
            </div>   
            <div class="modal-footer">
                <a href="/export-form/{{$sub_form->id}}"><button id="btn-export" type="button" class="btn btn-danger">Export</button></a>
            </div>  
        </div>
    </div>
</div> 

@endsection