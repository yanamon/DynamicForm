$(document).ready(function() {
    var input_is_option;
    var input_label;
    var input_type;
    var div_html;
    var div_html2;
    var input_html;
    var input_html2;
    var hidden_html;
    var hidden_html2;
    var input_key;

    function createIdentifier(json_obj, y){
        var i;
        var id_name;
        
        var identifier_html = "";
        var firstItem = json_obj[0];

        return identifier_html;
    }

    function createTableModal(json_obj, y, input_key){
        var i;
        var id_name;
        
        var table_html = "";
        var firstItem = json_obj[0];
        table_html = table_html +        
            '<div class=modal>';
        table_html = table_html +  
                '<div class=\'modal-dialog modal-xl modal-dialog-scrollable\'>\
                    <div class=modal-content>\
                        <div class=modal-header>\
                            <h4 class=modal-title>Select Data</h4>\
                            <button type=button class=\'close tm-modal-close\' data-dismiss=modal>&times;</button>\
                        </div>\
                        <div class=modal-body>\
                            <div class=table-responsive>\
                                <table id=example class=\'table table-bordered is-data-table\'>\
                                    <thead class=thead-dark>\
                                        <?php\
                                            $table_modal = \''+input_key+'\';\
                                            $dir = \'dropbox/tablemodal/\'.$folder_name.\'/\'.$table_modal.\'.json\';\
                                            $json = json_decode(file_get_contents($dir), true);\
                                            foreach(array_keys($json[0]) as $column){\
                                        ?>\
                                            <th><?php echo($column); ?></th>\
                                        <?php\
                                            }\
                                        ?>\
                                    </thead>\
                                    <tbody>\
                                        <?php \
                                            foreach($json as $rows){\
                                                $i=0;\
                                        ?>\
                                        <tr>\
                                            <?php \
                                                foreach($rows as $row){\
                                                    if($i == 0){\
                                            ?>\
                                                    <td>\
                                                        <center>\
                                                            <input class=tm-radio-input type=radio name=input_value['+y+'] value=<?php echo($row) ?>>\
                                                        </center>\
                                                    </td>\
                                            <?php   } else { ?>\
                                                    <td><?php echo($row);?></td>\
                                            <?php\
                                                    }\
                                                    $i++;\
                                                }\
                                            ?>\
                                        </tr>\
                                        <?php \
                                            }\
                                        ?>\
                                    </tbody>\
                                </table>\
                            </div>\
                        </div>\
                        <div class=modal-footer>\
                            <button type=button class=\'tm-radio-delete btn btn-danger\'>Delete Selected</button>\
                            <button data-dismiss=modal type=button class=\'tm-modal-close btn btn-primary\'>Submit Selected</button>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            <script>\
                $(\'.tm-modal-toggler\').click(function(){\
                    $(this).parent().find(\'.modal\').modal();\
                });\
                $(\'.tm-modal-close\').click(function(){\
                    $(this).parent().parent().parent().parent().modal(\'hide\');\
                });\
                $(\'.tm-radio-input\').click(function(){\
                    var tds = new Array();\
                    var class_name = this.className;\
                    var row = $(this).parent().closest(\'tr\');\
                    row.find(\'td\').each(function() {\
                        var count = $(this).children().length;\
                        if(count == 0) tds.push($(this).html());\
                    });\
                    var tds_string = tds.join(\', \');\
                    $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().find(\'.tm-modal-toggler\').val(tds_string);\
                });\
                $(\'.tm-radio-delete\').click(function(){\
                    $(this).parent().parent().find(\'.tm-radio-input\').prop(\'checked\', false);\
                    $(this).parent().parent().parent().parent().parent().find(\'.tm-modal-toggler\').val(\'\');\
                });\
                $(\'.readonly\').on(\'keydown paste\', function(e) {\
                    $(this).preventDefault();\
                });\
            </script>';
        return table_html;
    }


    $("#btn-submit-input").on("click", function(e){ 
        e.preventDefault();
        input_label = $('#label').val();
        input_required = $('input[name=required]:checked').val();
        input_is_option = $('#input-types').find(':selected').attr('data-is-option');
        input_type = $('#input-types').val();
        input_key = $('#key').val();
        if(input_label==""||input_key=="") {alert("Label or attribute key can't be empty");return;}
        var keys_length = keys.length; 
        for(a=0; a<=keys_length; a++){
            if(input_key==keys[a]) {alert("Attribute key must be unique");return;}
        }

        input_html = '<div id=card-input-'+y+' data-required='+input_required+' data-key='+input_key+' data-id='+y+' class=card-input><div class=form-group><label>'+input_label+'</label>';
        input_html2 = input_html;
        if(input_is_option == 1) {
            if(input_type=="dropdown"){
                input_html2 = input_html2 + '<select required class=form-control name=input_value['+y+']><option value= >-- Select '+input_label+' --</option>';
                input_html = input_html + '<select class=form-control name=input_value['+y+']>';
                var i = 0;
                $('.option').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        input_html2 = input_html2 + '<option>'+option+'</option>';
                        input_html = input_html + '<option>'+option+'</option>';
                        i++;
                    }
                });
                if(i<2){alert("minimum number of option is 2");return;}
                input_html2 = input_html2 + '</select>';
                input_html = input_html + '</select>';
            }
            else if(input_type=="radio"||input_type=="checkbox"){
                var i = 0;
                if(input_type=="checkbox") {
                    input_html = input_html+'<div class=check>';
                    input_html2 = input_html2+'<div class=\'check checkbox-validation\'>';
                }
                else {
                    input_html = input_html+'<div class=check>';
                    input_html2 = input_html2+'<div class=check>';
                }
                $('.option').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        if(input_type=="checkbox") {
                            input_html2 = input_html2 + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+y+']['+i+'] value='+option+'>'+option+'</label></div>';
                            input_html = input_html + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+y+']['+i+'] value='+option+'>'+option+'</label></div>';
                        }
                        else {
                            input_html2 = input_html2 + '<div class=form-check><label class=form-check-label><input required type='+input_type+' name=input_value['+y+'] value='+option+'>'+option+'</label></div>';
                            input_html = input_html + '<div class=form-check><label class=form-check-label><input  type='+input_type+' name=input_value['+y+'] value='+option+'>'+option+'</label></div>';
                        }
                        i++;
                    }
                });   
                input_html = input_html+'</div>'; 
                input_html2 = input_html2 +'</div>';         
                if(i<2){alert("minimum number of option is 2");return;}
            }
            else {alert("input type underconstruction");return;}
        }
        
        else if(input_is_option == 2) {
            var table_html = createTableModal(table_modal_json, y, input_key);
            var placeholder = "\'Click to Set This Input\'";
            tm_json[input_key] = table_modal_json;
            var tm_json_input = '<input type="hidden" id=tm-json-'+y+' name=tm_json['+input_key+'] value=\''+JSON.stringify(tm_json[input_key])+'\'/>';

            input_html2 = input_html + '<input  class=\'tm-modal-toggler form-control readonly\' type='+input_type+' placeholder='+placeholder+' required>';
            input_html2 = input_html2 + table_html;
            input_html = input_html + '<input  class=\'tm-modal-toggler form-control readonly\' type='+input_type+' placeholder='+placeholder+' required>';
            input_html = input_html + table_html;
            table_modal_json = 0;
        }
        
        else if(input_is_option == 3) {
            var table_html = createIdentifier(table_modal_json, y);
            var placeholder = "\'Identifier\'";
            input_html2 = input_html + '<input class=form-control type='+input_type+' name=input_value['+y+'] placeholder='+input_type+' required>';
            input_html = input_html + '<input class=form-control type='+input_type+' name=input_value['+y+'] placeholder='+input_type+'>';
            table_modal_json = 0;
        }
        else{ 
            input_html2 = input_html + '<input class=form-control type='+input_type+' name=input_value['+y+'] placeholder='+input_type+' required>';
            input_html = input_html + '<input class=form-control type='+input_type+' name=input_value['+y+'] placeholder='+input_type+'>';
        }     
        keys.push(input_key);
        div_html = input_html + '<input type=hidden name=input_label['+y+'] value='+input_key+'>' + '</div>';
        div_html2 = input_html2 + '<input type=hidden name=input_label['+y+'] value='+input_key+'>' + '</div>';
        input_html = input_html + '<input type=hidden name=input_label['+y+'] value='+input_key+'>' + '</div></div>';
        input_html2 = input_html2 + '<input type=hidden name=input_label['+y+'] value='+input_key+'>' + '</div></div>';
        hidden_html = div_html + '<input type="hidden" name="html[]" value="'+input_html+'">';
        hidden_html2 = div_html2 + '<input type="hidden" name="html[]" value="'+input_html2+'">';

        hidden_html = hidden_html + '<input type="hidden" name="input_key[]" value="'+input_key+'"></div>';
        hidden_html2 = hidden_html2 + '<input type="hidden" name="input_key[]" value="'+input_key+'"></div>';

        if(input_required == 'Yes')  $("#dynamic-form").append(hidden_html2); 
        else $("#dynamic-form").append(hidden_html); 

        //new tablemodal
        if(input_is_option == 2) {
            $('#card-input-'+y).append(tm_json_input);
            var json_upload_id = "json_upload_"+y;
        }
        
        $('#table-modal-'+y).remove();
        y++;

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
                if(option.val()!="")dropdown_options.push(option.val());
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
                
                $('#btn-option-add2').append('<div id="label_'+json_upload_id+'"><label><span>Table Modal File : '+ edit_input_key +'.json</span></label></div>');
                $('#btn-option-add2').append('Change File : <input type="file" id="'+ json_upload_id +'" name="'+json_upload_id+ '"  />');
                $("#"+json_upload_id).change(function(event) {
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

        
        $('.select2').select2({ width: '100%' });
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
        $('#add-modal').modal('toggle');
    });    

    $("#btn-delete").click(function() {
        var card_id = $('#card-id').val();
        var card_key = $('#card-key').val();
        var keys_length = keys.length; 
        $('#card-input-'+card_id).remove();
        for(a=0; a<=keys_length; a++){    
            var index = keys.indexOf(card_key);
            if (index > -1) keys[index] = null;
        }
        $('#action-modal').modal('toggle');
    });  


    $("#btn-add").click(function() {
        $('#label').val('');
        $('#key').val('');
        $('#input-types').val('text');
        $('#input-types option[value=text]').prop('selected', true);
        $("#radio-Yes-add").prop("checked", true);
        $('#input_fields_wrap').empty();
        $('#btn-option-add').empty();
    });  
});