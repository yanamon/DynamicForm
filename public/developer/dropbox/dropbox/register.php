<?php $app_key = "pguozkqfb6vn8w9";  $app_secret = "dw5h7xegfdm356a"; ?>


<?php
    require_once "autoload.php"; 
    use Kunnu\Dropbox\DropboxFile; 
    use Kunnu\Dropbox\DropboxApp; 
    use Kunnu\Dropbox\Dropbox; 
    $app = new DropboxApp($app_key, $app_secret);
    $dropbox = new Dropbox($app);
    $authHelper = $dropbox->getAuthHelper();
    $authUrl = $authHelper->getAuthUrl();
?>

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/css/select2.min.css">
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <style>
            #card {border-radius:5px;background-color:white;padding-top:30px;padding-bottom:0px;padding-right:0px;padding-left:0px;
            margin-bottom: 10px;}.card-title{padding-right: 30px;padding-left: 30px; }.card-input { padding-top:15px; padding-bottom:5px;
            padding-right: 30px;padding-left: 30px;}.select2-selection__arrow {margin-top:3px!important;}
            .select2-selection.select2-selection--single{height: 36px!important; padding:3px !important;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row" style="margin-top:50px;">
                <div class="col-md-2"></div>
                <form id="card" method="POST" class="col-md-8 shadow-sm" style="margin-top:100px">
                    <div class="form-group card-title">
                        <center>
                            <h3>Register</h3>
                            <a class="card-title" id="dropbox-web-auth" target="_blank" href="<?php echo $authUrl; ?>">Get Access Token</a>
                        </center>
                    </div>
                    <div class=card-input>
                        <div class=form-group>
                            <label>Dropbox Access Token</label>
                            <input class=form-control name="access_token" placeholder="Example : QDnzbNyOrxAAAAAAAAAAJ7bK2t2IkbpkTP2VgawIUBo" required>
                        </div>
                        <div class=form-group>
                            <label>Key</label>
                            <input type="password" class=form-control name="password" placeholder="Example : bK2t2209" required>
                        </div>
                    </div>
                    <div class="form-group card-title" style="margin-bottom:30px;">
                        <button type="submit" class="col-md-12 btn btn-success btn-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

<?php if (isset($_SESSION["login_error"])) { 
    $b = strval($_SESSION["login_error"]);
    $b = str_replace( '"', '\"', $b );
    $b = str_replace( "'", "\'", $b );
   
?> 
    <script>
        alert ('<?php echo($b); ?>');
    </script>
<?php unset($_SESSION["login_error"]); }  ?> 
