@extends('layouts.dynamic-form-layout')
@section('body')

<script>
    function validate() {
        var formTitle = $('#formTitle').val();
        var formName = $('#formName').val();
        var projectId = $('#projectId').val();
        if (!formTitle) {
            alert("Form title is required");
            $('#save-modal').modal('toggle');
        }
        else if(!formName) {
            alert("Form Name is required");
        }
        else{
            $.ajax({
                url: "/ajax-check-form-name",
                method : 'post',
                data:{
                    formName:formName, 
                    projectId:projectId
                },
                success:function(data){
                    if(data==0){
                        $('#dynamic-form').submit();
                    }
                    else alert("Form Name already used in your project");
                },
            });
        }
    }
</script>

<!-- Dynamic Form -->
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-2"></div>
        <div id="card" class="col-md-8 shadow-sm" style="padding-bottom : 18px;">
            <div>
                @if ($errors->any())
                <div class="card-title">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                @if($error == "The html field is required.") <li>Form must have at least 1 input field.</li>
                                @elseif($error == "The input key field is required.")
                                @else <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                <form action="/store-form" method="POST" id="dynamic-form" class="dynamic-form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group card-title">
                        <input id="formTitle" class="form-control form-control-lg" type="text" name="title" placeholder="Form Title">
                        <input class="form-control form-control-sm" type="text" name="description" placeholder="Description (Optional)">
                        <input id="projectId" type="hidden" name="project_id" value="{{$project_id}}">
                    </div>  
                    <!-- Save Modal -->
                    <div class="modal" id="save-modal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Save Form</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>    
                                <div class="modal-body" id="json-identifier">
                                    <div class="form-group">
                                        <label for="usr">Form Name:</label>
                                        <input id="formName" class="form-control" type="text" name="form_name" placeholder="May only contain letters, numbers, dashes, underscores"> 
                                    </div>
                                    <div class="form-group">
                                        <label for="usr">Login Type:</label>
                                        <select class="form-control" id="form-type" name="form_type">
                                            <option value=1>Login With User's Dropbox</option>
                                            <option value=2>Login With User's Dropbox + Admin Auth</option>
                                            <option value=0>Without Login</option>
                                        </select>
                                         <!-- <input  id="identifier" name="identifier" type="checkbox" value="yes"> Add Identifier -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-save" type="button" class="btn btn-danger" onclick="validate();">Save</button>
                                </div>     
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
        <div class="col-md-1">
            <button id="btn-add" data-toggle="modal" data-target="#add-modal" title="Add New Input" class="btn btn-success btn-circle" type="button"><i class="fa fa-plus fa-lg"></i></button>
            <button data-toggle="modal" data-target="#save-modal" title="Save Form" class="btn btn-info btn-circle" type="button"><i class="fa fa-save fa-lg"></i></button>
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
                <form enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="usr">Label:</label>
                        <input type="text" class="form-control" id="label" placeholder="Example: Product Name">
                    </div>
                    <div class="form-group">
                        <label for="usr">Attribute Key:</label>
                        <input type="text" class="form-control" id="key" placeholder="Example: product_name">
                    </div>
                    <div class="form-group">
                        <label for="usr">Required:</label>
                        <div class="radio">
                            <label><input id="radio-Yes-add" type="radio" name="required" value="Yes" checked>Yes</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="required" value="No">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="usr">Input Type:</label>
                        <select class="form-control" id="input-types" name="input">
                            @foreach($inputTypes as $i => $inputType)
                                <option data-is-option="{{$inputType->is_option}}" value="{{$inputType->html}}">{{$inputType->input_type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="input_fields_wrap"></div>
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
                <input type="hidden" id="card-required">       
                <form enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="usr">Label:</label>
                        <input id="edit-label" type="text" class="form-control" placeholder="Example: Product Name">
                    </div>
                    <div class="form-group">
                        <label for="usr">Attribute Key:</label>
                        <input id="edit-key" type="text" class="form-control" placeholder="Example: product_name">
                    </div>
                    <div class="form-group">
                        <label for="usr">Required:</label>
                        <div class="radio">
                            <label><input id="radio-Yes" type="radio" name="required2" value="Yes" checked>Yes</label>
                        </div>
                        <div class="radio">
                            <label><input id="radio-No" type="radio" name="required2" value="No">No</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="usr">Input Type:</label>
                        <select class="form-control" id="input-types2" name="input" style="width:100%">
                            @foreach($inputTypes as $i => $inputType)
                                <option data-is-option="{{$inputType->is_option}}" value="{{$inputType->html}}">{{$inputType->input_type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="input_fields_wrap2"></div>
                    <div id="btn-option-add2"></div>
                </form> 
            </div> 
            <div style="display: unset;" class="modal-footer">  
                <div class="row">
                    <div class="col-md-6">
                        <button id="btn-edit" type="button" class="btn btn-primary btn-block">Edit</button>
                    </div>      
                    <div class="col-md-6">
                        <button id="btn-delete" type="button" class="btn btn-danger btn-block">Delete</button>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>   

@endsection
