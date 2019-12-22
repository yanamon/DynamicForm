@extends('layouts.dynamic-form-layout')
@section('body')

<script>
    function validate() {
        var formTitle = $('#formTitle').val();
        var formName = $('#formName').val();
        var id_edit = $('#id_edit').val();
        var is_edit = $('#is_edit').val();
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
                    id_edit:id_edit,
                    is_edit:is_edit,
                    formName:formName, 
                    projectId:projectId
                },
                success:function(data){
                    console.log(data);
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
        <div id="card" class="col-md-8 shadow-sm"  style="padding-bottom:18px;">
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
                <form action="/update-form" method="POST" id="dynamic-form" class="dynamic-form">
                    {{ csrf_field() }}
                    <div class="form-group card-title">
                        <input value="{{$form->title}}" id="formTitle" class="form-control form-control-lg" type="text" name="title" placeholder="Form Title">
                        <input value="{{$form->description}}" class="form-control form-control-sm" type="text" name="description" placeholder="Description (Optional)">
                        <input id="projectId" type="hidden" name="project_id" value="{{$project_id}}">
                        <input type="hidden" id="is_edit" value="edit">
                        <input type="hidden" id="id_edit" name="id_edit"  value="{{$form->id}}">
                    </div> 
                    <!-- Save Modal -->
                    <div class="modal" id="save-modal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Save Form</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>    
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="usr">Form Name:</label>
                                        <input value="{{$form->form_name}}" id="formName" class="form-control" type="text" name="form_name" placeholder="May only contain letters, numbers, dashes, underscores"> 
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-save" type="button" class="btn btn-danger" onclick="validate();">Save</button>
                                </div>     
                            </div>
                        </div>
                    </div> 
                    @foreach($form->formInput as $input)
                        {!!$input->html!!}
                        <input class="temp-html" value="{{$input->html}}" type="hidden">
                        <input class="temp-input-key" value="{{$input->input_key}}" type="hidden">
                    @endforeach 
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
                <form>
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
                        <select class="form-control" id="input-types2" name="input">
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

<script>
    $(document).ready(function() {
        var temp_html = [];
        var temp_input_key = [];
        $('.temp-html').each(function() {
            var html = '<input type="hidden" name="html[]" value="'+$(this).val()+'">';
            temp_html.push(html);
            $(this).remove();
        });

        $('.temp-input-key').each(function() {
            var input_key = '<input type="hidden" name="input_key[]" value="'+$(this).val()+'">';
            temp_input_key.push(input_key);
            $(this).remove();
        });

        $('.card-input').each(function(i) {
            var id = $(this).attr('data-id');
            if(id>y) y = id;
            var key = $(this).attr('data-key');
            keys[id]=key;
            $(this).append(temp_html[i]);
            $(this).append(temp_input_key[i]);
        })

        y++;
    });
</script>

<script>
    $(".card-input").hover(function() {
        $(this).addClass('card-input-shadow').css('cursor', 'pointer'); 
    }, function() {
        $(this).removeClass('card-input-shadow');
    });   

    $(".card-input").click(function() {
        var card_id = $(this).attr('data-id'); 
        var card_key = $(this).attr('data-key'); 
        var card_required = $(this).attr('data-required'); 
        var card_attr_id = $(this).attr('id');  
        $('#card-id').val(card_id);            
        $('#card-key').val(card_key);
        $('#table-modal-'+card_id).remove();

        var isTableModal = 0;
        var edit_input_type;
        var edit_input_key;
        var edit_input_label = $("#"+card_attr_id).find('label').filter(':visible:first').html();
        var edit_options = [];
        var first_hidden = true;
        $("#"+card_attr_id+" :input").each(function(){
            var input = $(this);
            if(input.attr('type')!='hidden') {
                edit_input_type=input.attr('type');
                edit_options.push(input.val());
                if(edit_input_type == 'tablemodal'){
                    isTableModal = 1;
                }
            }
            else if(first_hidden==true) {
                edit_input_key = input.val();
                first_hidden = false;
            }
        });

        var dropdown_options = [];
        $("#"+card_attr_id).find('select > option').each(function(){
            edit_input_type = 'dropdown'
            var option = $(this);
            dropdown_options.push(option.html());
        });
        if(isTableModal) edit_input_type = 'tablemodal';
        
        if(card_required == 'Yes') $("#radio-Yes").prop("checked", true);
        else if (card_required == 'No') $("#radio-No").prop("checked", true);
        $('#edit-label').val(edit_input_label);
        $('#edit-key').val(edit_input_key);
        $('#input-types2').val(edit_input_type);
        $('#input-types2 option[value='+edit_input_type+']').prop('selected', true);

        $('#input_fields_wrap2').empty();
        $('#btn-option-add2').empty();
        function isOption(options){
            $('#input_fields_wrap2').append('<div><input type="text" name="option[]" value='+options[0]+' placeholder="New Option" class="option2 form-control form-control-sm"><input type="text" name="option[]" value='+options[1]+' placeholder="New Option" class="option2 form-control form-control-sm"></div>');
            $('#btn-option-add2').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
            x = options.length-1;
            options.forEach(myFunction);
            function myFunction(item, index) {
                if(index>1)
                $("#input_fields_wrap2").append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" value='+item+' name="option[]" placeholder="New Option" class="option2 form-control form-control-sm"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>');
            }
        }
        if(isTableModal) {
            $('#btn-option-add2').append('<input type="file" id="json_upload2" name="json_upload"  />');
            $("#json_upload2").change(function(event) {
                var reader = new FileReader();
                reader.onload = onReaderLoad;
                reader.readAsText(event.target.files[0]);
            });
            function onReaderLoad(event){
                var obj = JSON.parse(event.target.result);
                table_modal_json = obj;
            }
        }
        else if(dropdown_options.length > 0) isOption(dropdown_options);
        else if(edit_options.length > 1) isOption(edit_options);
        $('#action-modal').modal('show');
    });   

     jQuery.fn.swap = function(b){ 
        // method from: http://blog.pengoworks.com/index.cfm/2008/9/24/A-quick-and-dirty-swap-method-for-jQuery
        b = jQuery(b)[0]; 
        var a = this[0]; 
        var t = a.parentNode.insertBefore(document.createTextNode(''), a); 
        b.parentNode.insertBefore(a, b); 
        t.parentNode.insertBefore(b, t); 
        t.parentNode.removeChild(t); 
        return this; 
    };


    $( ".card-input" ).draggable({ revert: true, helper: "clone" });

    $( ".card-input" ).droppable({
        accept: ".card-input",
        activeClass: "ui-state-hover",
        hoverClass: "ui-state-active",
        drop: function( event, ui ) {

            var draggable = ui.draggable, droppable = $(this),
                dragPos = draggable.position(), dropPos = droppable.position();
            
            draggable.css({
                left: dropPos.left+'px',
                top: dropPos.top+'px'
            });

            droppable.css({
                left: dragPos.left+'px',
                top: dragPos.top+'px'
            });
            draggable.swap(droppable);
        }
    });
</script>

@endsection
