$(document).ready(function() {
    //-- Variable --//
    var max_fields      = 50; //maximum input boxes allowed
    var wrapper   		= $("#input_fields_wrap"); //Fields wrapper
    var add_button      = $("#btn-option-add"); //Add button ID  
    var wrapper2   		= $("#input_fields_wrap2"); //Fields wrapper
    var add_button2      = $("#btn-option-add2"); //Add button ID           
    x = 1; //initlal text box count

    //-- Add Input Modal : Option --//
    $('#input-types').change(function() {
        x = 1;
        $('#input_fields_wrap').empty();
        $('#btn-option-add').empty();
        if ($('option:selected', this).attr('data-is-option') == 1) {
            $('#input_fields_wrap').append('<div><input type="text" name="option[]" placeholder="New Option" class="option form-control form-control-sm"><input type="text" name="option[]" placeholder="New Option" class="option form-control form-control-sm"></div>');
            $('#btn-option-add').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
        }
        else if($('option:selected', this).attr('data-is-option') >= 2){
            $('#btn-option-add').append('<input type="file" id="json_upload" name="json_upload"  />');
            $("#json_upload").change(function(event) {
                var reader = new FileReader();
                reader.onload = onReaderLoad;
                reader.readAsText(event.target.files[0]);
            });
            function onReaderLoad(event){
                var obj = JSON.parse(event.target.result);
                table_modal_json = obj;
            }
        }
    });

    $('#input-types2').change(function() {
        x = 1;
        $('#input_fields_wrap2').empty();
        $('#btn-option-add2').empty();
        if ($('option:selected', this).attr('data-is-option') == 1) {
            $('#input_fields_wrap2').append('<div><input type="text" name="option[]" placeholder="New Option" class="option2 form-control form-control-sm"><input type="text" name="option[]" placeholder="New Option" class="option2 form-control form-control-sm"></div>');
            $('#btn-option-add2').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
        }
        else if($('option:selected', this).attr('data-is-option') == 2){
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
    });
    
    $(add_button).on("click", ".add_field_button", function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" name="option[]" placeholder="New Option" class="option form-control form-control-sm"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    $(add_button2).on("click", ".add_field_button", function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper2).append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" name="option[]" placeholder="New Option" class="option2 form-control form-control-sm"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>'); //add input box
        }
    });
    
    $(wrapper2).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    
    $('#form-type').change(function() {
        $('#json-identifier-upload').remove();
        if($('#form-type').val() == 2){
            $('#json-identifier').append('\
                <div class="form-group" id="json-identifier-upload">\
                    <label for="usr">Your Auth File:</label>\
                    <input type="file" name="json_identifier"  />\
                </div>'
            );
        }
    });

});