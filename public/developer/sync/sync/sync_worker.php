



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
                $response = $dropbox->postToAPI("/sharing/mount_folder", [
                    "shared_folder_id" => $mount["shared_folder_id"]
                ]);  
                $mount_name = $mount["name"];
                $path = "/".$mount_name;
                $move_path = "/".$project_name."/".$mount_name."/unsynchronized/data";
                $move = $dropbox->copy($path, $move_path, true);   

                $response = $dropbox->postToAPI("/sharing/relinquish_folder_membership", [
                    "shared_folder_id" => $mount["shared_folder_id"],
                    "leave_a_copy" => false
                ]);  

            }

            foreach($syncs as $sync){
                $path = "/".$project_name."/".$sync["folder"]."/unsynchronized";
                $listData = $dropbox->listFolder($path);
                if(empty($listData->getItems()->first())) echo "No unsynchronized data on ".$sync["folder"]."\n";
                else{
                    $attributes = ""; 
                    $values = "";
                    $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $first_folder_name = $listData->getItems()->first()->getName();
                    $file_download = $dropbox->download($path."/".$first_folder_name."/data.json");
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
                        if (($key = array_search($attachment_attr[0], $sync['attribute'])) !== false) unset($sync['attribute'][$key]);
                        array_push($sync['attribute'],$attachment_attr[0]);
                        $k++;
                    }

                    
                    $j = 0;
                    foreach($sync['attribute'] as $i => $attr){
                        $attributes = $attributes.$attr;
                        if($j < count($sync['attribute'])-1) $attributes = $attributes.",";
                        $j++;
                    }
                    $j = 0;
                    foreach($file_content as $i => $data){
                        $data = str_replace('"', '\"', $data);
                        $values = $values.'"'.$data.'"';
                        if($j < count($file_content)-1) $values = $values.", ";
                        $j++;
                    }
                    $query = "INSERT INTO ".$sync["table"]."(".$attributes.") VALUES(".$values.")";
                    $sql = $conn->prepare($query);
                    $sql->execute();
    
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