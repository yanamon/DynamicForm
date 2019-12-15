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
                        <h3>New Project</h3>
                    </div>
                </div>
                <form action="/store-project" method="POST" id="dynamic-form" class="dynamic-form">
                    {{ csrf_field() }}  
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Project Name</label>
                            <input class=form-control type=text name=project_name>
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Dropbox App Key</label>
                            <input class=form-control type=text name=dropbox_app_key>
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                    
                        <div class=form-group>
                            <label>Dropbox App Secret</label>
                            <input class=form-control type=text name=dropbox_app_secret>
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Dropbox Access Token</label>
                            <input class=form-control type=text name=dropbox_access_token>
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
