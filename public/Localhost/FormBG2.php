

<?php
    require_once "vendor/autoload.php";
    use Kunnu\Dropbox\DropboxFile;
    use Kunnu\Dropbox\DropboxApp;
    use Kunnu\Dropbox\Dropbox;


    if(isset($_POST) && !empty($_POST)){
        $app_name = "pguozkqfb6vn8w9";
        $app_secret = "dw5h7xegfdm356a";
        $access_token = "apa_LdNqwrsAAAAAAAABfUSb9a8JZ5YuUMOK9FWi3oQp2AnPKyl8bARec7pjPns2";
        $path = 'barang'.'.json';
        $php_file_name =  "FormBG2.php";

        $values = $_POST['input_value'];
        $labels = $_POST['input_label'];
        $keys = array_keys($values);
        $i = 0;
        $row = array();
        foreach($values as $value){        
            if(is_array($value)) $attr = array($labels[$keys[$i]] => array_values($value));
            else $attr = array($labels[$keys[$i]] => $value);
            $row = $row + $attr;
            $i++;
        }

        $myJSON = json_encode($row);
        $fp = fopen($path, 'w');
        fwrite($fp, $myJSON);
        fclose($fp); 
        $app = new DropboxApp($app_name, $app_secret, $access_token);
        $dropbox = new Dropbox($app);
        $pathToLocalFile = $path;
        $dropboxFile = new DropboxFile($pathToLocalFile);
        $file = $dropbox->upload($dropboxFile, '/'.$path, ['autorename' => true]);
        $file->getName();
        unlink($path);
        header("Location:  $php_file_name");
        exit;
    }
    else{
?>
        <html>
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        </head>
        <body>
            <div class="container">
                <div class="row" style="margin-top:50px;">
                    <div class="col-md-2"></div>
                    <div id="card" class="col-md-8 shadow-sm" style="margin-top:25px">
                        <div>
                            <div class="form-group card-title">
                                <h3>Form Barang</h3>
                                <label>input barang toko</label>
                            </div>
                            <form action="#" method="POST">
                                <div id=card-input-0 data-key=nama_barang data-id=0 class=card-input>
                                    <div class=form-group><label>Nama Barang</label>
                                        <input class=form-control type=text name=input_value[0] placeholder=text>
                                        <input type=hidden name=input_label[0] value=nama_barang>
                                    </div>
                                </div> 
                                <div id=card-input-1 data-key=harga_barang data-id=1 class=card-input>
                                    <div class=form-group><label>Harga Barang</label>
                                        <input class=form-control type=text name=input_value[1] placeholder=text>
                                        <input type=hidden name=input_label[1] value=harga_barang>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
<?php
    }
?>