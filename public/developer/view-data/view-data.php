


<?php 
require_once "../dropbox/autoload.php"; 
use Kunnu\Dropbox\DropboxFile; 
use Kunnu\Dropbox\DropboxApp; 
use Kunnu\Dropbox\Dropbox; 

$setting_path = '../sync/sync_setting.json';
if(file_exists($setting_path)){
    $mysql_json = file_get_contents($setting_path);
    $sync_setting = json_decode($mysql_json, true);
}

if(isset($_GET["folder"])){$this_form = $_GET["folder"];}
if(isset($_GET["table"])){$this_form = $_GET["table"];}

function download_file($dropbox, $dropbox_path, $local_path){
	try{    
		$file_download = $dropbox->download($dropbox_path, $local_path);
	}catch(Exception $e){
		echo("Download failed: " . $e->getMessage()."\n");
	}
}

function download_data($dropbox, $project_name, $form_name, $last_folder_id , $sync){
	$local_path = "data/".$form_name."/";
	$dropbox_path = "/".$project_name."/".$form_name."/".$sync."/";
	
	$listData = $dropbox->listFolder($dropbox_path);
	$folders = $listData->getItems();

	$is_download = 0;
	foreach($folders as $folder){
		$folder_id = substr($folder->getId(), 3);
		if(!$is_download){
			if($last_folder_id != null){
				if($folder_id != $last_folder_id) continue;
				else {
					$is_download = 1;
					continue;
				}
			}
			else $is_download = 1;
		}
		if($is_download) {
			mkdir($local_path.$folder_id);
			$from = $dropbox_path.$folder->getName()."/insert.json";
			$to = $local_path.$folder_id."/insert.json";
			download_file($dropbox, $from, $to);
			$json = json_decode(file_get_contents($to),true);
			if($sync == "synchronized") $json['sync_status'] = "synchronized";
			else $json['sync_status'] = "unsynchronized";
			file_put_contents($to, json_encode($json));
			mkdir($local_path.$folder_id."/attachment");
			$listAttachment = $dropbox->listFolder($dropbox_path.$folder->getName()."/attachment");
			$files = $listAttachment->getItems();
			foreach($files as $file){
				if($file->getData()['.tag'] == 'file'){
					$from = $dropbox_path.$folder->getName()."/attachment/".$file->getName();
					$to = $local_path.$folder_id."/attachment/".$file->getName();
					download_file($dropbox, $from, $to);
				}
				else if($file->getData()['.tag'] == 'folder'){
					mkdir($local_path.$folder_id."/attachment/".$file->getName());
					$listSubform = $dropbox->listFolder($dropbox_path.$folder->getName()."/attachment/".$file->getName());
					$subforms = $listSubform->getItems();
					foreach($subforms as $subform){
						mkdir($local_path.$folder_id."/attachment/".$file->getName()."/".$subform->getName());
						$listAttachment2 = $dropbox->listFolder($dropbox_path.$folder->getName()."/attachment/".$file->getName()."/".$subform->getName());
						$files2 = $listAttachment2->getItems();
						foreach($files2  as $file2){
							$from = $dropbox_path.$folder->getName()."/attachment/".$file->getName()."/".$subform->getName()."/".$file2->getName();
							$to = $local_path.$folder_id."/attachment/".$file->getName()."/".$subform->getName()."/".$file2->getName();
							download_file($dropbox, $from, $to);
						}
					}
				}
			}
		}
	}
	if(isset($folder_id))return $folder_id;
	else return "0";
}


function mount_shared_folder($dropbox, $project_name, $folder_name){
	$response = $dropbox->postToAPI("/sharing/list_mountable_folders");
	$mounts = $response->getDecodedBody();
	foreach($mounts["entries"] as $mount) {
		if($mount["name"] == $folder_name){
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
	}
}



if(isset($_POST["download"])){ 
	try{    
		$app = new DropboxApp($app_key, $app_secret, $access_token); 
		$dropbox = new Dropbox($app);  
		
		mount_shared_folder($dropbox, $project_name, $this_form);
		if (!file_exists("data")) mkdir("data");
        if (!file_exists("data/".$this_form)) mkdir("data/".$this_form);
		if (!file_exists("last-unsync-folder-id")) mkdir("last-unsync-folder-id");
		if (!file_exists("last-sync-folder-id")) mkdir("last-sync-folder-id");

		if (!file_exists("last-unsync-folder-id/".$this_form.".txt")) {
			$last_folder_id_1 = download_data($dropbox,$project_name, $this_form, null, "unsynchronized");
			if($last_folder_id_1!="0") file_put_contents("last-unsync-folder-id/".$this_form.".txt", $last_folder_id_1);
			echo("a");
		}
		else{
			$last_folder_id_1 = file_get_contents("last-unsync-folder-id/".$this_form.".txt");
			$lastest_folder_id_1 = download_data($dropbox,$project_name, $this_form, $last_folder_id_1, "unsynchronized");
			if($lastest_folder_id_1!="0") file_put_contents("last-unsync-folder-id/".$this_form.".txt", $lastest_folder_id_1);
			echo("b");
		}
		if (!file_exists("last-sync-folder-id/".$this_form.".txt")) {
			$last_folder_id_2 = download_data($dropbox,$project_name, $this_form, null, "synchronized");
			if($last_folder_id_2!="0") file_put_contents("last-sync-folder-id/".$this_form.".txt", $last_folder_id_2);
			echo("c");
		}
		else{
			$last_folder_id_2 = file_get_contents("last-sync-folder-id/".$this_form.".txt");
			$lastest_folder_id_2 = download_data($dropbox,$project_name, $this_form, $last_folder_id_2, "synchronized");
			if($lastest_folder_id_2!="0") file_put_contents("last-sync-folder-id/".$this_form.".txt", $lastest_folder_id_2);
			echo("d");
		}
	}catch(Exception $e){   
		echo("Connection failed: " . $e->getMessage()."\n");
	}

    echo "Save settings success";
	header("Location: ".$_SERVER['PHP_SELF'].'?folder='.$this_form);
    exit;
}

else if(isset($_GET["folder"]) || isset($_GET["table"]) ){
	$jsons = [];
	$json_keys = [];
	if(isset($_GET["folder"]) ){
		$path = "data/".$_GET["folder"];
		if(file_exists($path)){
			$di = new RecursiveDirectoryIterator($path);
			$jsons = [];
			$json_keys = [];
			foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
				if($file->getExtension() == 'json'){
					$json = file_get_contents($file);
					$json = json_decode($json, true);
					array_push($jsons, $json);
				}
			}
			if(!empty($json)) $json_keys = array_keys($json);
		}
	}
	if(isset($_GET["table"]) && file_exists($setting_path)){
		try {
			$server = $sync_setting['server_name'];
			$db = $sync_setting['database_name'];
			$user = $sync_setting['username'];
			$pass = $sync_setting['password'];
			$conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$table = $_GET["table"];
			$query = $conn->prepare("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS
				WHERE table_schema = '$db' AND TABLE_NAME = '$table' ");
			$query->execute();
			$result['column'] = $query -> fetchAll(\PDO::FETCH_ASSOC); 
			$query = $conn->prepare("SELECT * FROM $table");
			$query->execute();
			$result['table'] = $query -> fetchAll(\PDO::FETCH_ASSOC);
			$jsons = $result['table'];
			$json_keys = array();
			foreach($result['column'] as $field){
				array_push($json_keys, $field['column_name']); 
			}
		}
		catch(PDOException $e){
			$result['message'] = "Connection failed: " . $e->getMessage();
		}
	}

?> 

<html>
    <head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/css/select2.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
		<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
		<link rel="stylesheet" href="../dropbox/sidebar.css">
        <style>
            #card {border-radius:5px;background-color:white;padding-top:30px;padding-bottom:0px;padding-right:17px;
            padding-left:2px;margin-bottom: 10px;}.card-title{padding-right: 30px;padding-left: 30px; }.card-input { 
            padding-top:15px; padding-bottom:5px;padding-right: 30px;padding-left: 30px;}
        </style>
    </head>
    
    <body>
        
		<div class="wrapper">
			<nav id="sidebar">
				<div class="sidebar-header"><h3><?php echo $project_name; ?></h3> </div>
				<ul class="list-unstyled components" style="padding:12px;">
					<li>
						<a href="#pageSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dropbox Data</a>
						<ul class="collapse list-unstyled" id="pageSubmenu1">
							<?php
								foreach($form_attr["data"] as $form){
									if($form['folder']["type"] == "0")
									echo '<li><a href="../view-data/view-data.php?folder='.$form['folder']["name"].'">'.$form['folder']["name"].'</a></li>';
								}
							?>
						</ul>
					</li>
					<li>
						<a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">MySQL Data</a>
						<ul class="collapse list-unstyled" id="pageSubmenu2">
							<?php
								if(isset($sync_setting)){
                                    foreach($sync_setting[0] as $table){
                                        echo '<li><a href="../view-data/view-data.php?table='.$table.'">'.$table.'</a></li>';
                                    }
                                }
                                else {
					                echo '<li style="font-size:12">Set your MySQL on Sync Setter Page!</li>';
                                }
							?>
						</ul>
					</li>
				</ul>
				<ul class="list-unstyled CTAs">
					<li> <a class="download" href="../sync/sync_setter.php">GO TO SYNC SETTER</a> </li>
				</ul>
			</nav>
			<div id="content" style="padding-bottom:30px">
				<nav class="navbar navbar-expand-lg navbar-light bg-light">
					<div class="container-fluid">
						<button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <i class="fa fa-user"></i> </button>
						<div class="collapse navbar-collapse" id="navbarSupportedContent">
							<ul class="nav navbar-nav ml-auto">
								<li class="nav-item dropdown">
									<a class="nav-link" href="#" id="navbarDropdown" role="button">Admin Page&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</li>
							</ul>
						</div>
					</div>
				</nav>
                <div class="container">
                    <div class="row">
						<div class="col-md-1"></div>
						<div id="card" class="col-md-10 shadow-sm">
                            <div class="table-responsive">	
                                <h2 class="text-center">View <?php echo($this_form); ?> Data</h2>
                                <table id=example class='table table-bordered is-data-table'>
                                    <thead class=thead-dark>
										<?php 
											echo '<th>No</th>';
											foreach($json_keys as $attribute){
												echo '<th>'.$attribute.'</th>';
											}
										?>
                                    </thead>
                                    <tbody>
										<?php 
											foreach($jsons as $i => $row){
												echo '<tr>';
												echo '<td>'.++$i.'</td>';
												foreach($row as $key => $data){
													if(is_array($data)){
														$string = json_encode($data);
														echo "<td><a class='td-subform' style='color:rgb(105, 151, 244);' href=# data-subform-name='".$key."' data-json='".$string."'>Click to Show Data</a></td>";
													} 
													else echo '<td>'.$data.'</td>';
												}
												echo '</tr>';
											}
										?>
                                    </tbody>
                                </table>
                                <form method="POST" style="padding-left:15px">
									<input type="hidden" name="download" value="yes">
                                    <?php if(!isset($_GET['table'])) echo '<button type="submit" class="btn btn-info">Download New Data From Dropbox</button>'?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html> 

<div id="modal-subform" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="subform-name" class="modal-title">Subform Data</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="subform-json" class="modal-body">
				<div class="table-responsive">	
					<h2 class="text-center"></h2>
					<table id=example2 class='table table-bordered is-data-table'>
						<thead id="thead-subform" class=thead-dark>
						</thead>
						<tbody id="tbody-subform">
						</tbody>
					</table>
				</div>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>   
        </div>
    </div>
</div>


<script> 
    $('.td-subform').click(function(e){
		e.preventDefault();
		var keys;
		var json = JSON.parse($(this).attr('data-json'));
		var subform_name = $(this).attr('data-subform-name');

		$('#tbody-subform').empty();
		$('#thead-subform').empty();
		$('#subform-name').text(subform_name+' Data');

		$.each(json, function(i, items) {
			keys = Object.keys(items);
			var no = i+1;
			$('#tbody-subform').append('<tr id="tr-subform-'+i+'"></tr>')
			$('#tr-subform-'+i).append('<td>'+no+'</td>')
			$.each(items, function(j, item) {
				$('#tr-subform-'+i).append('<td>'+item+'</td>')
			});
		});

		$('#thead-subform').append('<th>No</th>')
		$.each(keys, function(i, item) {
			$('#thead-subform').append('<th>'+item+'</th>')
		});

			
		$('#modal-subform').modal('toggle');
	});
</script> 

<script> 
    $('#example').DataTable(); 	
</script> 



<?php } ?>