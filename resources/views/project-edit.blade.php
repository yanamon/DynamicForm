@extends('layouts.dynamic-form-layout')
@section('body')

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2"></div>
        <div id="card" class="col-md-8 shadow-sm" style="padding-top:10px; padding-bottom:10px;">
            <div>
                @if ($errors->any())
                <div class="card-title">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                    <div class="form-group">
                        <h3>Edit Project</h3>
                    </div>
                </div>
                <form action="/update-project" method="POST" id="dynamic-form" class="dynamic-form">
                    {{ csrf_field() }}  
                    <input value="{{$project->id}}" class=form-control type=hidden name=id>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Project Name</label>
                            <input value="{{$project->project_name}}" class=form-control type=text name=project_name placeholder="May only contain letters, numbers, dashes, underscores">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Dropbox App Key</label>
                            <input value="{{$project->dropbox_app_key}}" class=form-control type=text name=dropbox_app_key placeholder="Example: pguozkqfb6vn1w9">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                    
                        <div class=form-group>
                            <label>Dropbox App Secret</label>
                            <input value="{{$project->dropbox_app_secret}}" class=form-control type=text name=dropbox_app_secret placeholder="Example: dw5h3xegfdm326a">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Dropbox Access Token</label>
                            <input value="{{$project->dropbox_access_token}}" class=form-control type=text name=dropbox_access_token placeholder="Example: apa_LdNqwrsAAAAAAAABfUSb9a7JZ5YuUMOK9FWi3oQp1AnPKyl8bARec7pjPns2">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
