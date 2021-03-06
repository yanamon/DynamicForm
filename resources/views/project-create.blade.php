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
                            <input class=form-control type=text name=project_name placeholder="May only contain letters, numbers, dashes, underscores">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <a target=”_blank” href="https://www.dropbox.com/login?cont=https%3A%2F%2Fwww.dropbox.com%2Fdevelopers%2Fapps%3F_tk%3Dpilot_lp%26_ad%3Dtopbar4%26_camp%3Dmyapps"> 
                                <button type="button" class="btn btn-info btn-block">Create Dropbox App (API)</button>
                            </a>
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Dropbox App Key</label>
                            <input class=form-control type=text name=dropbox_app_key placeholder="Example: pguozkqfb6vn1w9">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                    
                        <div class=form-group>
                            <label>Dropbox App Secret</label>
                            <input class=form-control type=text name=dropbox_app_secret placeholder="Example: dw5h3xegfdm326a">
                        </div>
                    </div>
                    <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                        <div class=form-group>
                            <label>Dropbox Access Token</label>
                            <input class=form-control type=text name=dropbox_access_token placeholder="Example: apa_LdNqwrsAAAAAAAABfUSb9a7JZ5YuUMOK9FWi3oQp1AnPKyl8bARec7pjPns2">
                        </div>
                    </div>
                    <div id="json-identifier" data-key=harga_barang data-id=1 class=card-input >
                        <div class="form-group">
                            <label for="usr">Login Type:</label>
                            <select class="form-control" id="form-type" name="form_type">
                                <option value=1>Login With User's Dropbox</option>
                                <!-- <option value=2>Login With User's Dropbox + Admin Auth</option> -->
                                <option value=0>Without Login</option>
                            </select>
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
