$(document).ready(function() {
    var max_fields      = 50;
    var wrapper   		= $(".input_fields_wrap"); 
    var add_button      = $("#btn-option-add");         
    var x = 0; 
    var y = 0; 
    var input_is_option;
    var input_label;
    var input_type;
    var input_html;
    var input_key;
    var keys = [];

    $("#btn-submit-input").on("click", function(e){ 
        e.preventDefault();
        input_label = $('#label').val();
        input_is_option = $('#input-types').find(':selected').attr('data-is-option');
        input_type = $('#input-types').val();
        input_key = $('#key').val();
        if(input_label==""||input_key=="") {alert("Label or attribute key can't be empty");return;}

        input_html = '<div id=card-input-'+y+' data-key='+input_key+' data-id='+y+' class=card-input><div class=form-group><label>'+input_label+'</label>';
        if(input_is_option == 1) {
            if(input_type=="dropdown"){
                input_html = input_html + '<select class=form-control name=input_value['+y+']>';
                var i = 0;
                $('.option').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        input_html = input_html + '<option>'+option+'</option>';
                        i++;
                    }
                });
                if(i<2){alert("minimum number of option is 2");return;}
                input_html = input_html + '</select>'
            }
            else if(input_type=="radio"||input_type=="checkbox"){
                var i = 0;
                input_html = input_html+'<div class=check>';
                $('.option').each(function() {
                    var option =  $(this).val();
                    if(option!=""){
                        if(input_type=="checkbox") {
                            input_html = input_html + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+y+']['+i+'] value='+option+'>'+option+'</label></div>';
                        }
                        else input_html = input_html + '<div class=form-check><label class=form-check-label><input type='+input_type+' name=input_value['+y+'] value='+option+'>'+option+'</label></div>';
                        i++;
                    }
                });   
                input_html = input_html+'</div>'                 
                if(i<2){alert("minimum number of option is 2");return;}
            }
            else {alert("input type underconstruction");return;}
        }
        else{ input_html = input_html +
            '<input class=form-control type='+input_type+' name=input_value['+y+'] placeholder='+input_type+' required>';
        }
        var keys_length = keys.length; 
        for(a=0; a<=keys_length; a++){
            if(input_key==keys[a]) {alert("Attribute key must be unique");return;}
        }
        keys.push(input_key);
        input_html=input_html + '<input type=hidden name=input_label['+y+'] value='+input_key+'>' + '</div></div>';
        $("#dynamic-form").append(input_html);  
        $("#dynamic-form").append('<input type="hidden" name="html[]" value="'+input_html+'">');       
        $('#add-modal').modal('toggle');
        $(".card-input").hover(function() {
            $(this).addClass('card-input-shadow').css('cursor', 'pointer'); 
        }, function() {
            $(this).removeClass('card-input-shadow');
        });   
        $(".card-input").click(function() {
            var card_id = $(this).attr('data-id'); 
            var card_key = $(this).attr('data-key'); 
            $('#card-id').val(card_id);            
            $('#card-key').val(card_key);
            $('#action-modal').modal('show');
        });   
        y++;
    });    

    $("#btn-delete").click(function() {
        var card_id = $('#card-id').val();
        var card_key = $('#card-key').val();
        var keys_length = keys.length; 
        $('#card-input-'+card_id).remove();
        for(a=0; a<=keys_length; a++){    
            var index = keys.indexOf(card_key);
            if (index > -1) keys.splice(index, 1);
        }
        $('#action-modal').modal('toggle');
    });  

    $("#btn-edit").click(function() {
        $('#card-id').val(card_id);
    });
});