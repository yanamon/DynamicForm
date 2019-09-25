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
                <form action="/store-user-form" method="POST" id="dynamic-form" class="dynamic-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_user" value="{{$form->id_user}}">
                    <input type="hidden" name="form_id" value="{{$form->id}}">
                    <div class="form-group card-title">
                        <h3>{{$form->title}}</h3>
                        @if($form->description!=null)<label>{{$form->description}}</label>@endif
                    </div>  
                    @foreach($form->formInput as $input)
                        {!!$input->html!!}
                    @endforeach
                    <div class="form-group card-title" style="margin-bottom:30px;">
                        <button type="submit" class="col-md-12 btn btn-success btn-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection