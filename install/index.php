<?php
error_reporting(0);
$db_config_path = '../app/Config/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST) {

    require_once('taskCoreClass.php');
    require_once('includes/databaseLibrary.php');

    $core = new Core();
    $database = new Database();

    if (!empty($_POST['hostname']) && !empty($_POST['username']) && !empty($_POST['database'])  && !empty($_POST['admin_email']) ) {
        if ($database->create_database($_POST) == false) {
            $message = $core->show_message('error', "The database could not be created, make sure your the host, username, password, database name is correct.");
        } else 
        if ($core->write_config($_POST) == false) {
            $message = $core->show_message('error', "The database configuration file could not be written, please chmod app/Config/Database.php file to 777");
        } else 
        if ($database->create_tables($_POST) == false) {
            $message = $core->show_message('error', "The database could not be created, make sure your the host, username, password, database name is correct.");
        } else if ($database->create_admin($_POST) == false) {
            $message = $core->show_message('error', "The admin could not be created.");
        } else if ($core->checkFile() == false) {
            $message = $core->show_message('error', "File app/Config/database.php is Empty");
        }        
        if (!isset($message)) {
            $urlWb = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
            $urlWb = str_replace("install/","",$urlWb);
            $core->delete_directory('../install/');
            $type = 'success';
            $message = $core->show_message('success', 'Congrats! Installation is successful. Please wait redirecting you to the main page in seconds.. .');
            header('Refresh:5; url=' . $urlWb);
        }
    } else {
        $message = $core->show_message('error', 'The host, username, password, database name required.');
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Espeech Installer</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <div class="col-md-4 col-md-offset-4">
            <h1>Espeech Installer</h1>
            <hr>
            <?php
            if (is_writable($db_config_path)) {
            ?>
                <?php if (isset($message)) {
                    if (isset($type) && $type == 'success') {
                        echo '
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        ' . $message . '
                        </div>';
                    } else {
                        echo '
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        ' . $message . '
                        </div>';
                    }
                }
                ?>

                <form id="install_form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class='py-5'>
                    <div class="form-group">
                        <label for="hostname">Database Hostname <small class="text-danger">*</small></label>
                        <input type="text" id="hostname" value="localhost" class="form-control" name="hostname" />
                        <p class="help-block">Your Hostname.</p>
                    </div>

                    <div class="form-group">
                        <label for="username">Database Username <small class="text-danger">*</small></label>
                        <input type="text" id="username" class="form-control" name="username" />
                        <p class="help-block">Your Username.</p>
                    </div>

                    <div class="form-group">
                        <label for="password">Database Password</label>
                        <input type="password" id="password" class="form-control" name="password" />
                        <p class="help-block">Your Password.</p>
                    </div>

                    <div class="form-group">
                        <label for="database">Database Name <small class="text-danger">*</small></label>
                        <input type="text" id="database" class="form-control" name="database" />
                        <p class="help-block">Your Database Name.</p>
                    </div>

                    <div class="form-group">
                        <label for="admin_mobile">Admin Mobile <small class="text-danger">*</small></label>
                        <input type="text" id="admin_mobile" class="form-control" name="admin_mobile" />
                        <p class="help-block">Your Admin Mobile</p>
                    </div>

                    <div class="form-group">
                        <label for="admin_email">Admin Email</label>
                        <input type="text" id="admin_email" class="form-control" name="admin_email" />
                        <p class="help-block">Your Admin Email.</p>
                    </div>
                    <div class="form-group">
                        <label for="admin_password">Admin Password <small class="text-danger">*</small></label>
                        <input type="text" id="admin_password" class="form-control" name="admin_password" />
                        <p class="help-block">Your Admin Password.</p>
                    </div>



                    <input type="submit" value="Install" class="btn btn-primary btn-block pb-4" id="submit" />
                </form>

            <?php
            } else {
            ?>
                <p class="alert alert-danger">
                    Please make the app/Config/Database.php file writable.<br>
                    <strong>Example</strong>:<br />
                    <code>chmod 777 app/config/database.php</code>
                </p>
            <?php
            }
            ?>
        </div>

    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>