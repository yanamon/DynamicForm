@extends('layouts.dynamic-form-layout')
@section('body')

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2"></div>
        <div id="card" class="col-md-8 shadow-sm">
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
                <form action="/store-form" method="POST" id="dynamic-form" class="dynamic-form" onsubmit="return validate(this)">
                    {{ csrf_field() }}
                    <div class="form-group card-title">
                        <input id="formTitle" class="form-control form-control-lg" type="text" name="title" placeholder="Form Title">
                        <input class="form-control form-control-sm" type="text" name="description" placeholder="Description (Optional)">
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
                                    <div class="form-group">
                                        <label for="usr">Dropbox App Key:</label>
                                        <input id="appKey" class="form-control" type="text" name="app_key" placeholder="pguozkqfb6vn8w9" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="usr">Dropbox App Secret:</label>
                                        <input id="appSecret" class="form-control" type="text" name="app_secret" placeholder="dw5h7xegfdm356a" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="usr">Dropbox Access token:</label>
                                        <input id="accessToken" class="form-control" type="text" name="access_token" placeholder="apa_LdNqwrsAAAAAAAABfUSb9a8JZ5YuUMOK9FWi3oQp2AnPKyl8bARec7pjPns2" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="usr">Dropbox Folder Name:</label>
                                        <input id="folderName" class="form-control" type="text" name="folder_name" placeholder="tb_barang" required> 
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-export" type="button" class="btn btn-danger">Export</button>
                                </div>     
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
        <div class="col-md-1">
            <button data-toggle="modal" data-target="#add-modal" title="Add New Input" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
            <button data-toggle="modal" data-target="#export-modal" title="Export Form" class="btn btn-info btn-circle" type="button"><i class="fa fa-arrow-right fa-lg"></i></button>
        </div>
    </div>
</div>

<!-- Add Input Modal -->
<div class="modal" id="add-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Input</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="usr">Label:</label>
                        <input type="text" class="form-control" id="label" placeholder="Example: Product Name">
                    </div>
                    <div class="form-group">
                        <label for="usr">Attribute Key:</label>
                        <input type="text" class="form-control" id="key" placeholder="Example: product_name">
                    </div>
                    <div class="form-group">
                        <label for="usr">Input Type:</label>
                        <select class="form-control" id="input-types" name="input">
                            @foreach($inputTypes as $i => $inputType)
                                <option data-is-option="{{$inputType->is_option}}" value="{{$inputType->html}}">{{$inputType->input_type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input_fields_wrap"></div>
                    <div id="btn-option-add"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-submit-input" type="button" class="btn btn-danger">Submit</button>
            </div>     
        </div>
    </div>
</div>   



<!-- Action Modal -->
<div class="modal" id="action-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Action</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="card-id">
                <input type="hidden" id="card-key">
                <div class="row">
                    {{-- <div class="col-md-6">
                        <button id="btn-edit" type="button" class="btn btn-primary btn-block">Edit</button>
                    </div>       --}}
                    <div class="col-md-12">
                        <button id="btn-delete" type="button" class="btn btn-danger btn-block">Delete</button>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>   

@endsection

<script>
    function validate(form) {
        var formTitle = form.formTitle.value;
        var appKey = form.appKey.value;
        var appSecret = form.appSecret.value;
        var accessToken = form.accessToken.value;
        var folderName = form.folderName.value;
        if (!formTitle) {
            form.formTitle.focus();
            alert("Form title is required");
            return false;
        }
        else if(!appKey) {
            form.appKey.focus();
            alert("Dropbox App Key is required");
            return false;
        }
        else if(!appSecret) {
            form.appSecret.focus();
            alert("Dropbox App Secret is required");
            return false;
        }
        else if(!accessToken) {
            form.accessToken.focus();
            alert("Dropbox Access Token is required");
            return false;
        }
        else if(!folderName) {
            form.folderName.focus();
            alert("Dropbox Folder Name is required");
            return false;
        }
        else return true;
    }
</script>