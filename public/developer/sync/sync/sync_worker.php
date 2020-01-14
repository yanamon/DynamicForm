<?php $app_key="pguozkqfb6vn8w9"; $app_secret="dw5h7xegfdm356a"; $access_token="apa_LdNqwrsAAAAAAAABfUSb9a8JZ5YuUMOK9FWi3oQp2AnPKyl8bARec7pjPns2"; $project_name="Toko"; $server="localhost"; $user="root"; $pass=""; $db="db_toko"; $syncs[0]["folder"]="master_barang"; $syncs[0]["table"]="tb_barang"; $syncs[0]["table_attr"][0]="kode_barang"; $syncs[0]["folder_attr"][0]="kode_barang"; $syncs[0]["direct_to_db"]["kode_barang"]="no";  ?> 


<?php
    require_once "../dropbox/autoload.php"; 
    use Kunnu\Dropbox\DropboxFile; 
    use Kunnu\Dropbox\DropboxApp; 
    use Kunnu\Dropbox\Dropbox; 

    set_time_limit(0);
    $sleep_time = 2;
    while(true){
        sleep($sleep_time);
        try{    
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

            foreach($syncs as $sync){
                $jenis_sync = "insert";
                $path = "/".$project_name."/".$sync["folder"]."/unsynchronized";
                $listData = $dropbox->listFolder($path);
                if(empty($listData->getItems()->first())) echo "No unsynchronized data on ".$sync["folder"]."\n";
                else{
                    $attributes = ""; 
                    $values = "";
                    $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $first_folder_name = $listData->getItems()->first()->getName();
                    try{    
                        $file_download = $dropbox->download($path."/".$first_folder_name."/insert.json");
                    }catch(Exception $e){
                        $file_download = $dropbox->download($path."/".$first_folder_name."/update.json");
                        $jenis_sync = "update";
                    }
                    
                    echo '#'.$jenis_sync."\n";
                    $file_content = json_decode($file_download->getContents(),true);


                    $query ="SHOW KEYS FROM ".$sync["table"]." WHERE Key_name = 'PRIMARY'";
                    $sql = $conn->prepare($query);
                    $sql->execute();
                    $result = $sql->fetchAll();
                    foreach( $result as $row ) {
                        $primary_column = $row['Column_name']; 
                    }
                    $query ="SELECT MAX(".$primary_column.") as max_id FROM ".$sync["table"];
                    $sql = $conn->prepare($query);
                    $sql->execute();
                    $result = $sql->fetchAll();
                    foreach( $result as $row ) {
                        if($row['max_id'] == NULL) $max_id = 0 + 1; 
                        else $max_id = $row['max_id'] + 1; 
                    }
                    $attachment_folder = $max_id;

                    if (!file_exists("../attachment")) mkdir("../attachment");
                    if (!file_exists("../attachment/".$attachment_folder)) mkdir("../attachment/".$attachment_folder);

                    $listAttachment = $dropbox->listFolder($path."/".$first_folder_name."/attachment");
                    $attachments = $listAttachment->getItems();
                    $k=1;
                    foreach($attachments as $attachment){
                        $tmp = explode(".", $attachment->getName());
                        $ext = end($tmp);
                        $attachment_download = $dropbox->download($path."/".$first_folder_name."/attachment/".$attachment->getName());
                        $attachment_content = $attachment_download->getContents();
                        file_put_contents("../attachment/".$attachment_folder."/".$attachment_folder."_".$k.".".$ext, $attachment_content);
                        
                        $last = array_pop($tmp);
                        $attachment_attr = array(implode('.', $tmp), $last);
                        $file_content[$attachment_attr[0]] = $attachment_folder."/".$attachment_folder."_".$k.".".$ext;
                        // if (($key = array_search($attachment_attr[0], $sync['folder_attr'])) !== false) {
                        //     unset($sync['folder_attr'][$key]);
                        //     array_push($sync['folder_attr'],$attachment_attr[0]);
                        // }
                        $k++;
                    }
                    
                    if($jenis_sync == "insert"){
                        $j = 0;
                        foreach($sync['table_attr'] as $i => $attr){
                            $attributes = $attributes.$attr;
                            if($j < count($sync['table_attr'])-1) $attributes = $attributes.",";

                            $data = str_replace('"', '\"', $file_content[$sync['folder_attr'][$i]]);
                            $values = $values.'"'.$data.'"';
                            if($j < count($sync['table_attr'])-1) $values = $values.", ";
                            $j++;
                        }
                        $query = "INSERT INTO ".$sync["table"]."(".$attributes.") VALUES(".$values.")";
                        $sql = $conn->prepare($query);
                        $sql->execute();
                    }
                    else if($jenis_sync == "update"){
                        $j = 0;
                        foreach($sync['folder_attr'] as $i => $attr){
                            if(isset($file_content[$attr]) && $attr != $primary_column){
                                $data = str_replace('"', '\"', $file_content[$attr]);
                                $values = $values.$sync['table_attr'][$j].' = "'.$data.'"';
                                if($j < count($sync['folder_attr'])-1) $values = $values.", ";
                            }
                            $j++;
                        }
                        $primary_id = $file_content[$primary_column];
                        $query = "UPDATE ".$sync["table"]." SET ".$values." WHERE ".$primary_column." = ".$primary_id;
                        $sql = $conn->prepare($query);
                        $sql->execute();
                    }

    
                    $move_path = "/".$project_name."/".$sync["folder"]."/synchronized";
                    $move = $dropbox->move($path."/".$first_folder_name, $move_path."/".$first_folder_name, true);

                    echo $query."\n";
                }
            }
        }catch(Exception $e){   
            echo("Connection failed: " . $e->getMessage()."\n");
        }
    }
?>