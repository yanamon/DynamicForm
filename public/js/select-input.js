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
            $('#input_fields_wrap').append('<div><input type="text" name="option[]" placeholder="New Option" class="option form-control form-control-sm" id="usr"><input type="text" name="option[]" placeholder="New Option" class="option form-control form-control-sm" id="usr"></div>');
            $('#btn-option-add').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
            $('#btn-option-add').append('<button id="btnFileUpload" class="btn btn-success">CSV File Option</button>');
            $('#btn-option-add').append('<br><span id="spnFilePath"></span>');
            $('#btn-option-add').append('<input type="file" id="FileUpload1" name="csv" style="display: none" />');
            var fileupload = document.getElementById("FileUpload1");
            var filePath = document.getElementById("spnFilePath");
            var button = document.getElementById("btnFileUpload");
            button.onclick = function () {
                fileupload.click();
            };
            fileupload.onchange = function () {
                var fileName = fileupload.value.split('\\')[fileupload.value.split('\\').length - 1];
                filePath.innerHTML = "<b>Selected File: </b>" + fileName;
            };
        }
    });

    $('#input-types2').change(function() {
        x = 1;
        $('#input_fields_wrap2').empty();
        $('#btn-option-add2').empty();
        if ($('option:selected', this).attr('data-is-option') == 1) {
            $('#input_fields_wrap2').append('<div><input type="text" name="option[]" placeholder="New Option" class="option2 form-control form-control-sm" id="usr"><input type="text" name="option[]" placeholder="New Option" class="option2 form-control form-control-sm" id="usr"></div>');
            $('#btn-option-add2').append('<button class="btn btn-primary add_field_button">Add More Option</button>');
        }
    });
    
    $(add_button).on("click", ".add_field_button", function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" name="option[]" placeholder="New Option" class="option form-control form-control-sm" id="usr"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    $(add_button2).on("click", ".add_field_button", function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper2).append('<div class="row"><div class="col-md-11 col-sm-10 col-9"><input type="text" name="option[]" placeholder="New Option" class="option2 form-control form-control-sm" id="usr"></div><a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a></div>'); //add input box
        }
    });
    
    $(wrapper2).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    
});