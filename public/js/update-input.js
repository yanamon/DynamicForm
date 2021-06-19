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
                input_html2 = input_html2 + '<select required class=form-control name=input_value['+card_id+']><option value= >-- Select '+input_label+' --</option>';
                input_html = input_html + '<select class=form-control name=input_value['+card_id+']>';
                var i = 0;
                $('.option2').each(function() {
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
                $('.option2').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        if(input_type=="checkbox") {
                            input_html2 = input_html2 + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+card_id+']['+i+'] value='+option+'>'+option+'</label></div>';
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
            var table_html = createTableModal(table_modal_json, card_id, input_key);
            var placeholder = "\'Click to Set This Input\'";
            
            if(table_modal_json!=0) tm_json[input_key] = table_modal_json;
            else tm_json[input_key] = tm_json[keys[card_id]];

            var tm_json_input = '<input type="hidden" id=tm-json-'+y+' name=tm_json['+input_key+'] value=\''+JSON.stringify(tm_json[input_key])+'\'/>';

            input_html2 = input_html + '<input class=\'tm-modal-toggler form-control readonly\' type='+input_type+' placeholder='+placeholder+' required>';
            input_html2 = input_html2 + table_html;
            input_html = input_html + '<input class=\'tm-modal-toggler form-control readonly\' type='+input_type+' placeholder='+placeholder+' required>';
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

        if(input_is_option == 2) $('#card-input-'+card_id).append(tm_json_input);
        $('#table-modal-'+card_id).remove(); 
        $('.select2').select2({ width: '100%' });
        $('#action-modal').modal('toggle');
    });    
 
});