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

    function createTableModal(json_obj, y){
        var i;
        var id_name;
        
        var table_html = "";
        var firstItem = json_obj[0];
        table_html = table_html +        
            '<div class=modal id=table-modal-'+y+'>';
        table_html = table_html +  
                '<div class=\'modal-dialog modal-xl modal-dialog-scrollable\'>\
                    <div class=modal-content>\
                        <div class=modal-header>\
                            <h4 class=modal-title>Select Data</h4>\
                            <button type=button class=close data-dismiss=modal>&times;</button>\
                        </div>\
                        <div class=modal-body>\
                            <div class=table-responsive>\
                                <table id=example class=table>\
                                    <thead class=thead-dark>\
                                        <th></th>';
                                        $i = 0;
                                        for(key in firstItem) {
                                            if($i>0)table_html = table_html + '<th>'+key+'</th>';
                                            $i++;
                                        }
        table_html = table_html +  '</thead>\
                                    <tbody>\
                                        <tr>';
                                            i = 0
                                            $.each(json_obj, function(key1, items) {
                                                $.each(items, function(key2, item) {
                                                    if(i==0) {
                                                        id_name = key2;
                                                        if(id_name == key2) table_html = table_html + '<td><center><input type=radio name=input_value['+y+'] value='+item+'></center></td>';
                                                    }
                                                    else {
                                                        if(id_name == key2) table_html = table_html + '</tr><tr><td><center><input type=radio name=input_value['+y+'] value='+item+'></center></td>';
                                                        else table_html = table_html + '<td>'+item+'</td>';
                                                    }
                                                    i++;
                                                });
                                            });
        table_html = table_html +       '</tr>\
                                    </tbody>\
                                </table>\
                            </div>\
                        </div>\
                        <div class=modal-footer>\
                            <button data-dismiss=modal id=btn-export type=button class=\'btn btn-danger\'>Submit</button>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            <script>$(\'.table\').DataTable();</script>'
        return table_html;
    }


    $("#btn-edit").on("click", function(e){ 
        e.preventDefault();
        var card_id = $('#card-id').val();
        function deleteInput(){
            var card_id2 = $('#card-id').val();
            var card_key = $('#card-key').val();
            var keys_length = keys.length; 
            $('#card-input-'+card_id).empty();
            $('#html-'+card_id2).remove(); 
        }

        input_label = $('#edit-label').val();
        input_required = $('input[name=required2]:checked').val();
        input_is_option = $('#input-types2').find(':selected').attr('data-is-option');
        input_type = $('#input-types2').val();
        input_key = $('#edit-key').val();

        if(input_label==""||input_key=="") {alert("Label or attribute key can't be empty");return;}
        var keys_length = keys.length; 
        for(a=0; a<=keys_length; a++){
            if(input_key == keys[card_id]);
            else if(input_key==keys[a]) {alert("Attribute key must be unique");return;}
        }

        input_html = '<div class=form-group><label>'+input_label+'</label>';
        input_html2 = input_html;
        if(input_is_option == 1) {
            if(input_type=="dropdown"){
                input_html = input_html + '<select class=select2 name=input_value['+card_id+']>';
                var i = 0;
                $('.option2').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        input_html = input_html + '<option>'+option+'</option>';
                        i++;
                    }
                });
                if(i<2){alert("minimum number of option is 2");return;}
                input_html2 = input_html;
                input_html = input_html + '</select>';
            }
            else if(input_type=="radio"||input_type=="checkbox"){
                var i = 0;
                input_html = input_html+'<div class=check>';
                input_html2 = input_html2+'<div class=check>';
                $('.option2').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        if(input_type=="checkbox") {
                            input_html2 = input_html2 + '<div class=form-check><label class=form-check-label><input required type='+input_type+' name=input_value['+card_id+']['+i+'] value='+option+'>'+option+'</label></div>';
                            input_html = input_html + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+card_id+']['+i+'] value='+option+'>'+option+'</label></div>';
                        }
                        else {
                            input_html2 = input_html2 + '<div class=form-check><label class=form-check-label><input required type='+input_type+' name=input_value['+card_id+'] value='+option+'>'+option+'</label></div>';            
                            input_html = input_html + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+card_id+'] value='+option+'>'+option+'</label></div>';
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
            var table_html = createTableModal(table_modal_json, card_id);
            input_html2 = input_html + '<input readonly data-toggle=modal data-target=#table-modal-'+card_id+' class=form-control type='+input_type+' placeholder='+input_type+' required>';
            input_html2 = input_html2 + table_html;
            input_html = input_html + '<input readonly data-toggle=modal data-target=#table-modal-'+card_id+' class=form-control type='+input_type+' placeholder='+input_type+' required>';
            input_html = input_html + table_html
            table_modal_json = 0;
        }
        else{ 
            input_html2 = input_html + '<input class=form-control type='+input_type+' name=input_value['+card_id+'] placeholder='+input_type+' required>';
            input_html = input_html + '<input class=form-control type='+input_type+' name=input_value['+card_id+'] placeholder='+input_type+' >';
        }
        deleteInput();
        keys[card_id] = input_key;


        
        input_html=input_html + '<input type=hidden name=input_label['+card_id+'] value='+input_key+'>' + '</div>';
        input_html2=input_html2 + '<input type=hidden name=input_label['+card_id+'] value='+input_key+'>' + '</div>';
        
        var updated_html = '<div id=card-input-'+card_id+' data-required='+input_required+' data-key='+input_key+' data-id='+card_id+' class=card-input>'
        updated_html = updated_html + input_html;
        updated_html = updated_html + '</div>';
        var updated_html2 = '<div id=card-input-'+card_id+' data-required='+input_required+' data-key='+input_key+' data-id='+card_id+' class=card-input>'
        updated_html2 = updated_html2 + input_html2;
        updated_html2 = updated_html2 + '</div>';
        hidden_html = input_html+'<input type="hidden" name="html[]" value="'+updated_html+'">';
        hidden_html2 = input_html2+'<input  type="hidden" name="html[]" value="'+updated_html2+'">';

        hidden_html = hidden_html+'<input type="hidden" name="input_key[]" value="'+input_key+'">';
        hidden_html2 = hidden_html2+'<input  type="hidden" name="input_key[]" value="'+input_key+'">';

        if(input_required == 'Yes') {
            $('#card-input-'+card_id).append(hidden_html2);  
            $('#card-input-'+card_id).attr('data-required', input_required);   
        }
        else   {   
            $('#card-input-'+card_id).append(hidden_html);  
            $('#card-input-'+card_id).attr('data-required', input_required);
        }

        $('#table-modal-'+card_id).remove();

        // $(".card-input").hover(function() {
        //     $(this).addClass('card-input-shadow').css('cursor', 'pointer'); 
        // }, function() {
        //     $(this).removeClass('card-input-shadow');
        // });   

        // $(".card-input").click(function() {
        //     var card_id = $(this).attr('data-id'); 
        //     var card_key = $(this).attr('data-key'); 
        //     var card_required = $(this).attr('data-required'); 
        //     var card_attr_id = $(this).attr('id');  
        //     $('#card-id').val(card_id);            
        //     $('#card-key').val(card_key);
            
        //     var edit_input_type;
        //     var edit_input_key;
        //     var edit_input_label = $("#"+card_attr_id).find('label').filter(':visible:first').html();
        //     var edit_options = [];
        //     var first_hidden = true;
        //     $("#"+card_attr_id+" :input").each(function(){
        //         var input = $(this);
        //         if(input.attr('type')!='hidden') {
        //             edit_input_type=input.attr('type');
        //             edit_options.push(input.val());
        //         }
        //         else if(first_hidden==true) {
        //             edit_input_key = input.val();
        //             first_hidden = false;
        //         }
        //     });

        //     var dropdown_options = [];
        //     $("#"+card_attr_id).find('select > option').each(function(){
        //         edit_input_type = 'dropdown'
        //         var option = $(this);
        //         dropdown_options.push(option.html());
        //     });
            
            
        //     if(card_required == 'Yes') $("#radio-Yes").prop("checked", true);
        //     else if (card_required == 'No') $("#radio-No").prop("checked", true);
        //     $('#edit-label').val(edit_input_label);
        //     $('#edit-key').val(edit_input_key);
        //     $('#input-types2').val(edit_input_type);
        //     $('#input-types2 option[value='+edit_input_type+']').prop('selected', true);

        //     $('#input_fields_wrap2').empty();
        //     $('#btn-option-add2').empty();
        //     function isOption(options){
        //         $('#input_fields_wrap2').append('<div><input type="text" name="option[]" value='+options[0]+' placeholder="New Option" class="option2 form-control form-control-sm" id="usr"><input type="text" name="option[]" value='+options[1]+' placeholder="New Option" class="option2 form-control form-control-sm" id="usr"></div>');
        //         $('#btn-option-add2').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
        //         x = options.length-1;
        //         options.forEach(myFunction);
        //         function myFunction(item, index) {
        //             if(index>1)
        //             $("#input_fields_wrap2").append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" value='+item+' name="option[]" placeholder="New Option" class="option2 form-control form-control-sm" id="usr"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>');
        //         }
        //     }
        //     if(dropdown_options.length > 0) isOption(dropdown_options);
        //     else if(edit_options.length > 1) isOption(edit_options);
        //     $('#action-modal').modal('show');
        // });   
        $('.select2').select2({ width: '100%' });
        $('#action-modal').modal('toggle');
    });    
 
});