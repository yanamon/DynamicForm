<?php $app_key="pguozkqfb6vn8w9"; $app_secret="dw5h7xegfdm356a"; $access_token="apa_LdNqwrsAAAAAAAABfUSb9a8JZ5YuUMOK9FWi3oQp2AnPKyl8bARec7pjPns2"; $project_name="UNUD"; $form_attr["data"][0]["folder"] = "form_dosen";$form_attr["data"][0]["attribute"][0] = "nama_dosen";$form_attr["data"][1]["folder"] = "form_mahasiswa";$form_attr["data"][1]["attribute"][0] = "nama_mahasiswa";if(!isset($_POST["server_name"]) && !isset($_POST["request_update_data"]) ){ ?><script>var form_attr = <?php echo json_encode($form_attr); ?>;</script> <?php } ?>


<?php 
require_once "../dropbox/autoload.php"; 
use Kunnu\Dropbox\DropboxFile; 
use Kunnu\Dropbox\DropboxApp; 
use Kunnu\Dropbox\Dropbox; 



if(isset($_POST["request_update_data"])){ 
    $app = new DropboxApp($app_key, $app_secret, $access_token); 
    $dropbox = new Dropbox($app);  
    $response = $dropbox->postToAPI("/sharing/list_mountable_folders");
    $mounts = $response->getDecodedBody();
    foreach($mounts["entries"] as $mount) {
        if(isset($mount["path_lower"])) $path= $mount["path_lower"];
        else {
            $response = $dropbox->postToAPI("/sharing/mount_folder", [
                "shared_folder_id" => $mount["shared_folder_id"]
            ]); 
            $mount_result = $response->getDecodedBody();
            $path = $mount_result["path_lower"];
        }
        $mount_name = $mount["name"];
        $mount_name = strtok($mount_name,' ');
        $move_path = "/".$project_name."/".$mount_name."/unsynchronized/data";
        $move = $dropbox->copy($path, $move_path, true); 
        if($mount["access_type"][".tag"] == "editor"){
            $response = $dropbox->postToAPI("/sharing/relinquish_folder_membership", [
                "shared_folder_id" => $mount["shared_folder_id"],
                "leave_a_copy" => false
            ]);  
        }
        else if($mount["access_type"][".tag"] == "owner"){  
            $response = $dropbox->postToAPI("/sharing/unshare_folder", [
                "shared_folder_id" => $mount["shared_folder_id"],
                "leave_a_copy" => false
            ]);  
        }
    }
    
    foreach($form_attr["data"] as $sync){
        $file_contents[$sync["folder"]] = array();
        $path = "/".$project_name."/".$sync["folder"]."/unsynchronized";
        $listData = $dropbox->listFolder($path);
        if(!empty($listData->getItems()->first())){
            foreach($listData->getItems() as $item){
                $first_folder_name = $item->getName();
                try{    
                    $file_download = $dropbox->download($path."/".$first_folder_name."/insert.json");
                }catch(Exception $e){
                    $file_download = $dropbox->download($path."/".$first_folder_name."/update.json");
                    $jenis_sync = "update";
                }
                array_push($file_contents[$sync["folder"]],json_decode($file_download->getContents(),true));
            }
        }
    }
    echo json_encode($file_contents);
    exit;
}
else if(isset($_POST["folder"])){ 
    $folders = $_POST["folder"]; 
    $tables = $_POST["table"]; 
    $attributes = $_POST["attribute"]; 
    if(isset($_POST["direct_to_db"])) $direct_to_dbs = $_POST["direct_to_db"]; 
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

        $isPrepend2 = false;
        $prepend2 = '<?php ';
        $prepend2 = $prepend2.'$server="'.$server.'"; ';
        $prepend2 = $prepend2.'$user="'.$user.'"; ';
        $prepend2 = $prepend2.'$pass="'.$pass.'"; ';
        $prepend2 = $prepend2.'$db="'.$db.'"; ';
        $prepend2 = $prepend2.'$table_name="'.$tables[$i].'"; ';

        foreach($attributes[$tables[$i]] as $j => $attribute){
            $prepend = $prepend.'$syncs['.$i.']["table_attr"]['.$j.']="'.$attribute.'"; ';
        }
        foreach($attributes[$folders[$i]] as $j => $attribute){
            $prepend = $prepend.'$syncs['.$i.']["folder_attr"]['.$j.']="'.$attribute.'"; ';
        }
        foreach($attributes[$folders[$i]] as $j => $attribute){
            if(!isset($direct_to_dbs[$folders[$i]][$j])) $direct_to_dbs[$folders[$i]][$j] = "no";
            else{
                $prepend2 = $prepend2.'$direct_to_db_folder['.$j.'] = "'.$attribute.'"; ';
                $prepend2 = $prepend2.'$direct_to_db_table['.$j.'] = "'.$attributes[$tables[$i]][$j].'"; ';
                $isPrepend2 = true;
            }
            $prepend = $prepend.'$syncs['.$i.']["direct_to_db"]["'.$attribute.'"]="'.$direct_to_dbs[$folders[$i]][$j].'"; ';
        }
        if($isPrepend2){
            $prepend2 = $prepend2.' ?> ';
            $prepend2 = $prepend2."\n";
            $file = '../'.$folder.'.php';
            $contents = file_get_contents($file);
            $new_contents = preg_replace('/^.+\n/', '', $contents);
            file_put_contents($file,$new_contents);
            $fileContents = file_get_contents($file);
            file_put_contents($file, $prepend2 . $fileContents);
        }
        else{
            $prepend2 = "";
            $file = '../'.$folder.'.php';
            $contents = file_get_contents($file);
            $new_contents = preg_replace('/^.+\n/', '', $contents);
            file_put_contents($file,$new_contents);
            $fileContents = file_get_contents($file);
            file_put_contents($file, $prepend2 . $fileContents);
        }
    }
    $prepend = $prepend.' ?> ';
    $prepend = $prepend."\n";

    $file = 'sync_worker.php';
    
    $contents = file_get_contents($file);
    $new_contents = preg_replace('/^.+\n/', '', $contents);
    file_put_contents($file,$new_contents);
    
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
            WHERE table_schema = '$db' AND column_key != 'PRI' ");
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
        <!--  -->

        <!-- <div class="container" style="padding-bottom:200px;">
            <div class="row">
                <div class="col-md-12" style="margin-top:20px;margin-bottom:20px">
                    <center><h3>UNUD Dropbox Data</h3></center>
                </div>
            </div>
            <ul class="nav nav-pills" role="tablist" id="view-folder">
            </ul>
            <div class="tab-content" id="view-attr">
            </div>
            <div> 
                <form id="request-update-data" method="POST">
                    <input type="hidden" name="request_update_data" value="yes">
                </form>
                <button id="btn-update-data" class="btn btn-block btn-danger">Show Data</button>
            </div>
        </div> -->

        <!--  -->
        <div class="container" style="padding-bottom:200px;">
            <div class="row">
                <div class="col-md-12" style="margin-top:20px;margin-bottom:20px">
                <center><h3>Synchronize to Database</h3></center>
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
                                    <input class="form-control required2 required3" type="text" name="server_name" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Username</label>
                                    <input class="form-control required2 required3" type="text" name="username" placeholder="" required> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Password</label>
                                    <input class="form-control" type="password" name="password" placeholder=""> 
                                </div>
                                <div class="form-group">
                                    <label for="usr">Database Name</label>
                                    <input class="form-control required2 required3" type="text" name="database_name" placeholder="" required> 
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
                        <div id="dropbox-folder" class="list-group" style="display:none">
                        </div>
                    </form>
                </div>
                <div class="col-md-6 shadow-sm">
                    <div id="database-table" class="list-group" style="display:none">
                    </div>
                </div>
            </div>

            <form id="form-save-settings" action="#" style="margin-top:80px;display:none">
                <div id="sync-wrap">
                    <div id="sync">
                        <div class="card"  style="padding:10px; margin-bottom:15px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="folder[]" class="form-control sync-from required3">
                                        <option value="">-- Select Folder --</option>
                                    </select>
                                </div>
                                <div id="sync-center" class="col-md-1">
                                    <center>
                                        <label  style="margin-top:4%;">Sync to</label>
                                    </center>
                                </div>
                                <div class="col-md-4">
                                    <select name="table[]"  class="form-control sync-to required3">
                                        <option value="">-- Select Table --</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button  type="button" data-toggle="modal" class="btn btn-success btn-block sync-modal-button">Set Attribute</button>
                                </div>
                                <div id="sync-delete" class="col-md-1">
                                    <label></label>
                                </div>
                            </div>
                            <div class="modal sync-modal">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Set Attribute</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>     
                                        <div class="modal-body">
                                            <div class="sync-attr">
                                                <div class="card"  style="padding:10px; margin-bottom:15px;">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <select class="form-control sync-attr-from required3">
                                                                <option value="">-- Select Folder Attribute --</option>
                                                            </select>
                                                        </div>
                                                        <div id="sync-center" class="col-md-1">
                                                            <center>
                                                                <label  style="margin-top:4%;">Sync to</label>
                                                            </center>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select class="form-control sync-attr-to required3">
                                                                <option value="">-- Select Table Attribute --</option>
                                                            </select>
                                                        </div>
                                                        <!-- <div class="col-md-2">
                                                            <div class="card" style="padding:0px; padding-left:7px; display:block">
                                                                <input style="margin-right:7px;" class="direct-to-database" type="checkbox" value="yes">Save to Database
                                                                <br><label style="margin-left:20px;margin-bottom:0px;margin-top:-8px;" >without Dropbox</label>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-md-1 sync-delete-attr" >
                                                            <label></label>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="sync-wrap-attr"></div>
                                            <div class="row" style="margin-top:10px;">
                                                <div class="col-md-2">
                                                    <div id="btn-add-sync-attr">
                                                        <button  type="button" class="btn btn-info add_field_button_attr">Add Sync Attribute</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>   
                                        <div class="modal-footer">
                                            <button id="btn-export" type="button" class="btn btn-success" data-dismiss="modal">Submit</button>
                                        </div>  
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top:10px;">
                    <div class="col-md-2">
                        <div id="btn-add-sync">
                            <button  type="button" class="btn btn-info add_field_button">Add Sync</button>
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
    var append_attr = "adasdas";

    function updateOption(){
        $('#sync').children().children().children('#sync-delete').children().prepend('<a href="#" class="remove_field"><button  type="button" class="btn btn-danger btn-block"><i class="fa fa-trash" style="color:white; font-size:20px;"></i></button></a>');
        new_append=$('#sync').html();
        $('#sync').children().children().children('#sync-delete').children().children('.remove_field').remove();
        return new_append;
    }

    function updateOptionAttr(id){
        $('#sync-attr-'+id).children().children().children('.sync-delete-attr').children().prepend('<a href="#" class="remove_field_attr"><button  type="button" class="btn btn-danger btn-block"><i class="fa fa-trash" style="color:white; font-size:20px;"></i></button></a>');
        new_append=$('#sync-attr-'+id).html();
        $('#sync-attr-'+id).children().children().children('.sync-delete-attr').children().children('.remove_field_attr').remove();
        return new_append;
    }
    
    function updateModalId(){
        var i=0;
        $(".sync-modal").each(function(){
            $(this).attr("id", "sync-modal-"+i);
            $(this).parent().find(".sync-modal-button").attr("id", "sync-modal-button-"+i);
            $(this).parent().find(".sync-wrap-attr").attr("id", "sync-wrap-attr-"+i);
            $(this).parent().find(".add_field_button_attr").attr("data-wrap-id", i);
            $(this).parent().find(".sync-attr").attr("id", "sync-attr-"+i);

            $(this).parent().find("#sync-modal-button-"+i).prop("onclick", null).off("click");
            $(this).parent().find("#sync-modal-button-"+i).click(function () {
                var clicked_button = $(this);
                var selected_folder = $(this).parent().parent().find(".sync-from").val();
                var selected_table = $(this).parent().parent().find(".sync-to").val();
                if(selected_folder!="" && selected_table!=""){
                    if($(this).parent().parent().parent().find(".sync-modal").attr("data-modal-folder") != selected_folder 
                        || $(this).parent().parent().parent().find(".sync-modal").attr("data-modal-table") != selected_table){
                        $(this).parent().parent().parent().find(".sync-wrap-attr").empty();
                        $(this).parent().parent().parent().find(".sync-attr-from").empty();
                        $(this).parent().parent().parent().find(".sync-attr-to").empty();
                        $(this).parent().parent().parent().find(".sync-attr-from").append('<option value="">-- Select Folder Attribute --</option>');
                        $(this).parent().parent().parent().find(".sync-attr-to").append('<option value="">-- Select Folder Attribute --</option>');
                    }
                    if(clicked_button.parent().parent().parent().find(".sync-attr-from").children().length == 1) {
                        $('.'+selected_folder).children().each(function(){
                            var attr = $(this).html();
                            clicked_button.parent().parent().parent().find(".sync-attr-from").append('<option>'+ attr+ '</option>');
                        });
                        $('.'+selected_table).children().each(function(){
                            var attr = $(this).html();
                            clicked_button.parent().parent().parent().find(".sync-attr-to").append('<option>'+ attr+ '</option>');
                        });
                        $(this).parent().parent().parent().find(".sync-modal").attr("data-modal-folder",selected_folder);
                        $(this).parent().parent().parent().find(".sync-modal").attr("data-modal-table",selected_table);
                        $(this).parent().parent().parent().find(".sync-attr-from").attr("name",'attribute['+selected_folder+'][]' );
                        $(this).parent().parent().parent().find(".sync-attr-to").attr("name", 'attribute['+selected_table+'][]' );
                        $(this).parent().parent().parent().find(".direct-to-database").attr("name", 'direct_to_db['+selected_folder+'][]' );
                    }
                    $(this).parent().parent().parent().find(".sync-modal").modal('show');
                }
                else {
                    alert("Please select folder and table first"); return;
                }
            });
            i++;
        });
    }   
    

    append = updateOption();
    updateModalId();
    

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
                            <div class="list-group collapse '+table["table_name"]+'" id="table-'+ i + '">\
                            </div>\
                        ');
                        $.each(data['column'], function(j, column) { 
                            if(table["table_name"] == column["table_name"])
                            $('#table-' +  i).append('<a class="list-group-item">'+column["column_name"]+'</a>')
                        });
                        $('.sync-to').append('<option>'+ table["table_name"] + '</option>')
                    });
                    append = updateOption();
                    $("#form-save-settings").show();
                }
            });
        });

        $('#dropbox-folder').empty();
        $('.sync-from').empty();
        $('.sync-from').append('<option value="">-- Select Folder --</option>')
        $.each(form_attr['data'], function(i, item) {
            $('#dropbox-folder').append('\
                <a data-toggle="collapse" href="#folder-'+ i + '" class="list-group-item">'+item["folder"]+'</a> \
                <div class="list-group collapse '+item["folder"]+'" id="folder-'+ i + '">\
                </div>\
            ');
            
            if(i == 0) var active = "active";
            else var active = "";

            $('#view-folder').append('<li class="nav-item"><a class="nav-link '+active+'" data-toggle="pill" href="#'+item["folder"]+'">'+item["folder"]+'</a></li>');

            $('#view-attr').append('\
                <div id="'+item["folder"]+'" class="container tab-pane '+active+'"><br>\
                    <div class="table-responsive" style="background:white;padding:15px 5px;">\
                        <table class="table table-bordered example">\
                            <thead class="thead-dark">\
                                <tr id="thead-'+item["folder"]+'"></tr>\
                            </thead>\
                            <tbody id="tbody-'+item["folder"]+'">\
                            </tbody>\
                        </table>\
                    </div>  \
                </div>\
            ');

            $.each(item['attribute'], function(j, attribute) { 
                $('#folder-' +  i).append('<a class="list-group-item">'+attribute+'</a>')
                $('#thead-' +  item["folder"]).append('<th>'+attribute+'</th>')
                // $('#folder-' +  i).append('<input type=hidden name=attribute['+item["folder"]+']['+j+'] value='+attribute+'>')
            });
            $('.sync-from').append('<option>'+ item["folder"] + '</option>')
        });
        $('.example').DataTable();
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
            updateModalId();
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        $(this).parent().parent().parent().parent().remove(); 
        x--;
    })  
</script>


<script>

    //-- Variable --//
    var max_fields2      = 50; //maximum input boxes allowed
    var wrapper2   		= "#sync-wrap-attr"; //Fields wrapper
    var add_button2      = $("#form-save-settings"); //Add button ID         
    var x2 = 1; //initlal text box count

    $(add_button2).on("click", ".add_field_button_attr", function(e){ //on add input button click
        e.preventDefault();
        if(x2 < max_fields2){ //max input box allowed
            x2++; //text box increment
            var wrap_id = $(this).attr("data-wrap-id");
            append_attr = updateOptionAttr(wrap_id);
            $(wrapper2+"-"+wrap_id).append(append_attr); //add input box

            $(wrapper2+"-"+wrap_id).on("click",".remove_field_attr", function(e){ //user click on remove text
                e.preventDefault(); 
                $(this).parent().parent().parent().parent().remove(); 
                x--;
            })  
        }
    });
    
    
</script>


<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script> 
<script>
</script>



<script>
    $('#btn-update-data').click(function(){
        $('#btn-update-data').empty();
        $('#btn-update-data').append('<span class="spinner-border spinner-border-sm"></span> Loading..');
    
        jQuery.ajax({
            type: "POST",
            data:  $("#request-update-data").serialize(),
            success: function(data) {
                data = JSON.parse(data);
                console.log(data);
                $.each(form_attr['data'], function(i, folder) { 
                    var i = 0;
                    $.each(data[folder['folder']], function(i, attr) { 
                        if(i==0) $('#tbody-'+ folder['folder']).empty();
                        $('#tbody-'+ folder['folder']).append('<tr id="tr-'+folder['folder']+i+'"></tr>');
                        $.each(attr, function(key, value) { 
                            $('#tr-'+folder['folder']+i).append('<td>'+value+'</td>');
                        });
                        i++;
                    });
                });
                $('#btn-update-data').empty();
                $('#btn-update-data').append('Show Data');
                alert("Retrieve Data Success");
            }
        });

        
    });
</script>
