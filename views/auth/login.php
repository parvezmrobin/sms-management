<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/15/2017
 * Time: 4:50 AM
 */

require __DIR__ . "/../../index.php";
?>

<?php
// Start a session
$session = \App\Utility::startSession();

// Create a request object from $_Request[]
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$error = App\Auth::login($session, $request);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?= \App\Config::get('app', 'name') ?></title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/site.css">
    <script src="/js/jquery-3.2.1.js" type="text/javascript"></script>
    <script src="/js/bootstrap.js" type="text/javascript"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed"
                            data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?= \App\Config::get('app', 'name') ?></a>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
    </div><!--Top Nav Row-->
    <form action="login.php" method="post" class="row col-sm-4 col-sm-offset-4">
        <?php if (!$error) : ?>
            <div class="alert alert-danger alert-dismissible">
                <span class="glyphicon glyphicon-alert"></span>
                This credentials do not match our record
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username" class="control-label">Email</label>
            <input type="text" name="username" id="username" class="form-control">
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Password</label>
            <input type="text" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" value="Login" class="btn btn-primary">
        </div>
    </form>
</div>
</body>
</html>
