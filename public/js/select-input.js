$(document).ready(function() {
    //-- Variable --//
    var max_fields      = 50; //maximum input boxes allowed
    var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $("#btn-option-add"); //Add button ID           
    var x = 1; //initlal text box count
    var y = 1; //initial input count
    var input_is_option;
    var input_label;
    var input_type;

    //-- Add Input Modal : Option --//
    $('#input-types').change(function() {
        x = 1;
        $('.input_fields_wrap').empty();
        $('#btn-option-add').empty();
        if ($('option:selected', this).attr('data-is-option') == 1) {
            $('.input_fields_wrap').append('<div><input type="text" name="option[]" placeholder="Option 1" class="option form-control form-control-sm" id="usr"><input type="text" name="option[]" placeholder="Option 2" class="option form-control form-control-sm" id="usr"></div>');
            $('#btn-option-add').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
        }
    });

    $(add_button).on("click", ".add_field_button", function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" name="option[]" placeholder="Option '+ (x+1) +'" class="option form-control form-control-sm" id="usr"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});