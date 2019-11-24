

<?php 
require_once "../dropbox/autoload.php"; 
use Kunnu\Dropbox\DropboxFile; 
use Kunnu\Dropbox\DropboxApp; 
use Kunnu\Dropbox\Dropbox; 


if(isset($_POST["folder"])){ 
    $folders = $_POST["folder"]; 
    $tables = $_POST["table"]; 
    $attributes = $_POST["attribute"]; 
    $app_key = $_POST["dropbox_app_key"]; 
    $app_secret = $_POST["dropbox_app_secret"]; 
    $access_token = $_POST["dropbox_access_token"];  
    $project_name = $_POST["project_folder_name"];  
    $server = $_POST["server_name"]; 
    $user = $_POST["username"]; 
    $pass = $_POST["password"]; 
    $db = $_POST["database_name"]; 

    $prepend = '<?php ';
    $prepend = $prepend.'$app_key="'.$app_key.'"; ';
    $prepend = $prepend.'$app_secret="'.$app_secret.'"; ';
    $prepend = $prepend.'$access_token="'.$access_token.'"; ';
    $prepend = $prepend.'$project_name="'.$project_name.'"; ';
    $prepend = $prepend.'$server="'.$server.'"; ';
    $prepend = $prepend.'$user="'.$user.'"; ';
    $prepend = $prepend.'$pass="'.$pass.'"; ';
    $prepend = $prepend.'$db="'.$db.'"; ';

    foreach($folders as $i => $folder){
        $prepend = $prepend.'$syncs['.$i.']["folder"]="'.$folders[$i].'"; ';
        $prepend = $prepend.'$syncs['.$i.']["table"]="'.$tables[$i].'"; ';
        foreach($attributes[$folders[$i]] as $j => $attribute){
            $prepend = $prepend.'$syncs['.$i.']["attribute"]['.$j.']="'.$attribute.'"; ';
        }
    }
    $prepend = $prepend.' ?> ';

    $file = 'sync_worker.php';
    $fileContents = file_get_contents($file);
    file_put_contents($file, $prepend . $fileContents);
    echo "Save settings success";
    exit;
}

else if(isset($_POST["server_name"])){ 
    $server = $_POST["server_name"]; 
    $user = $_POST["username"]; 
    $pass = $_POST["password"]; 
    $db = $_POST["database_name"]; 

    try {
        $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $conn->prepare("SELECT table_name, column_name
            FROM information_schema.columns 
            WHERE table_schema = '$db'");
        $query->execute();
        $result['column'] = $query -> fetchAll(); 
        $query = $conn->prepare("SELECT table_name
        FROM information_schema.tables 
        WHERE table_schema = '$db'");
        $query->execute();
        $result['table'] = $query -> fetchAll();
        $result['message'] = "Connected to database: ".$db;
        $result['success'] = 1;
        echo json_encode($result);
    }
    catch(PDOException $e){
        $result['message'] = "Connection failed: " . $e->getMessage();
        $result['success'] = 0;
        echo json_encode($result);
    }

    exit;
} 

else{ 
?> 

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <style>
            #card {border-radius:5px;background-color:white;padding-top:30px;padding-bottom:0px;padding-right:0px;
            padding-left:0px;margin-bottom: 10px;}.card-title{padding-right: 30px;padding-left: 30px; }.card-input { 
            padding-top:15px; padding-bottom:5px;padding-right: 30px;padding-left: 30px;}
        </style>
    </head>
    
    <body>
        <div class="container" style="padding-bottom:200px;">
            <div class="row">
                <div class="col-md-12" style="margin-top:20px;margin-bottom:20px">
                <center><h3>Synchronize Data</h3></center>
                </div>
            </div>
            <div class="row" style="margin-top:0px;">
                <div id="card" class="col-md-6 shadow-sm" style="padding-top:0px">
                    <div>
                        <form id="form-dropbox" action="#" method="POST">
                            <div class="modal-header">
                                <h4 class="modal-title">Dropbox</h4>
                            </div>    
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="usr">Dropbox App Key</label>
                                    <input readonly="readonly" value="<?php echo $app_key ?>" class="form-control required" type="text" name="dropbox_app_key" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Dropbox App Secret</label>
                                    <input readonly="readonly" value="<?php echo $app_secret ?>" class="form-control required" type="text" name="dropbox_app_secret" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Dropbox Access Token</label>
                                    <input readonly="readonly" value="<?php echo $access_token ?>" class="form-control required" type="text" name="dropbox_access_token" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Project Name</label>
                                    <input readonly="readonly" value="<?php echo $project_name ?>" class="form-control required" type="text" name="project_folder_name" placeholder="" required> 
                                </div>
                            </div>
                        </form>  
                    </div>
                </div>
                <div id="card" class="col-md-6 shadow-sm" style="padding-top:0px">
                    <div>
                        <form id="form-database" action="#" method="POST">
                            <div class="modal-header">
                                <h4 class="modal-title">Database</h4>
                            </div>    
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="usr">Server Name</label>
                                    <input class="form-control required2" type="text" name="server_name" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Username</label>
                                    <input class="form-control required2" type="text" name="username" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Password</label>
                                    <input class="form-control" type="text" name="password" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Database Name</label>
                                    <input class="form-control required2" type="text" name="database_name" placeholder="" required> 
                                </div>
                                <button id="btn-database" type="button" class="btn btn-danger">Connect to Database</button> 
                            </div>
                        </form>  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 shadow-sm">
                    <form>
                        <div id="dropbox-folder" class="list-group">
                        </div>
                    </form>
                </div>
                <div class="col-md-6 shadow-sm">
                    <div id="database-table" class="list-group">
                    </div>
                </div>
            </div>

            <form id="form-save-settings" action="#" style="margin-top:80px;">
                <div id="sync-wrap">
                    <div id="sync">
                        <div class="row">
                            <div class="col-md-5">
                                <select name="folder[]" class="form-control sync-from required3">
                                    <option value="">-- Select Folder --</option>
                                </select>
                            </div>
                            <div id="sync-center" class="col-md-2">
                                <center>
                                    <label>Sync to -></label>
                                </center>
                            </div>
                            <div class="col-md-5">
                                <select name="table[]"  class="form-control sync-to required3">
                                    <option value="">-- Select Table --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-5"></div>
                    <div class="col-md-2">
                        <div id="btn-add-sync">
                            <center><button  type="button" class="btn btn-primary btn-info add_field_button">Add Sync</button></center>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:40px;">
                    <div class="col-md-12">
                        <button id="btn-save-settings" type="button" class="btn btn-primary btn-block">Save Settings</button> 
                    </div>
                </div>
            </form>
        </div>
    </body>

</html> 

<?php } ?>

<script>
    var append;
    function updateOption(){
        $('#sync').children().children('#sync-center').children().prepend('<a href="#" class="remove_field"><i class="fa fa-times fa-lg"></i></a>');
        new_append=$('#sync').html();
        $('#sync').children().children('#sync-center').children().children('.remove_field').remove();
        return new_append;
    }
    append = updateOption();

    $(document).ready(function(){
        $("#btn-save-settings").click(function () {
            if(validateEmptyField(".required3")) return;
            jQuery.ajax({
                type: "POST",
                data:  $("form").serialize(),
                success: function(message) {
                    alert(message);
                }
            });
        });

        $("#btn-database").click(function () {
            if(validateEmptyField(".required2")) return;
            jQuery.ajax({
                type: "POST",
                data:  $("#form-database").serialize(),
                success: function(data) {
                    data = JSON.parse(data);
                    alert(data['message']);
                    if(!data['success']){
                        return;
                    }
                    $('#database-table').empty();
                    $('.sync-to').empty();
                    $('.sync-to').append('<option value="">-- Select Table --</option>')
                    $.each(data['table'], function(i, table) {
                        $('#database-table').append('\
                            <a data-toggle="collapse" href="#table-'+ i + '" class="list-group-item">'+table["table_name"]+'</a> \
                            <div class="list-group collapse" id="table-'+ i + '">\
                            </div>\
                        ');
                        $.each(data['column'], function(j, column) { 
                            if(table["table_name"] == column["table_name"])
                            $('#table-' +  i).append('<a class="list-group-item">'+column["column_name"]+'</a>')
                        });
                        $('.sync-to').append('<option>'+ table["table_name"] + '</option>')
                    });
                    append = updateOption();
                }
            });
        });

        $('#dropbox-folder').empty();
        $('.sync-from').empty();
        $('.sync-from').append('<option value="">-- Select Folder --</option>')
        $.each(form_attr['data'], function(i, item) {
            $('#dropbox-folder').append('\
                <a data-toggle="collapse" href="#folder-'+ i + '" class="list-group-item">'+item["folder"]+'</a> \
                <div class="list-group collapse" id="folder-'+ i + '">\
                </div>\
            ');
            $.each(item['attribute'], function(j, attribute) { 
                $('#folder-' +  i).append('<a class="list-group-item">'+attribute+'</a>')
                $('#folder-' +  i).append('<input type=hidden name=attribute['+item["folder"]+']['+j+'] value='+attribute+'>')
            });
            $('.sync-from').append('<option>'+ item["folder"] + '</option>')
        });
        append = updateOption();
               


        function validateEmptyField(class_name){
            var isError = false;
            $(class_name).each(function(){
                if($(this).val() == "") isError = true;
            });
            if(isError){
                alert("Field can't be empty");
                return 1;
            }  
        }
    });
</script>


<script>

    //-- Variable --//
    var max_fields      = 50; //maximum input boxes allowed
    var wrapper   		= $("#sync-wrap"); //Fields wrapper
    var add_button      = $("#btn-add-sync"); //Add button ID         
    var x = 1; //initlal text box count

    $(add_button).on("click", ".add_field_button", function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append(append); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        $(this).parent().parent().parent().remove(); 
        x--;
    })  
</script>