

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
            foreach($syncs as $sync){
                $path = "/".$project_name."/".$sync["folder"]."/unsynchronized";
                $listFile = $dropbox->listFolder($path);
                if(empty($listFile->getItems()->first())) echo "No unsynchronized data on ".$sync["folder"]."\n";
                else{
                    $first_file_name = $listFile->getItems()->first()->getName();
                    $file_download = $dropbox->download($path."/".$first_file_name);
                    $file_content = json_decode($file_download->getContents(),true);
    
                    $attributes = ""; 
                    $values = "";
                    foreach($sync['attribute'] as $i => $attr){
                        $attributes = $attributes.$attr;
                        if($i < count($sync['attribute'])-1) $attributes = $attributes.",";
                    }
                    $j = 0;
                    foreach($file_content as $i => $data){
                        $data = str_replace('"', '\"', $data);
                        $values = $values.'"'.$data.'"';
                        if($j < count($file_content)-1) $values = $values.", ";
                        $j++;
                    }
                    $query = "INSERT INTO ".$sync["table"]."(".$attributes.") VALUES(".$values.")";
                    $conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = $conn->prepare($query);
                    $sql->execute();
    
                    $move_path = "/".$project_name."/".$sync["folder"]."/synchronized";
                    $move = $dropbox->move($path."/".$first_file_name, $move_path."/".$first_file_name, true);

                    echo $query."\n";
                }
            }
        }catch(Exception $e){   
            echo("Connection failed: " . $e->getMessage()."\n");
        }
    }
?>