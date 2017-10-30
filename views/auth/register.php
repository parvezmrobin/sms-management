<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/20/2017
 * Time: 1:01 AM
 */

require __DIR__ . "/../../index.php";
?>

<?php

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

if ($request->request->has('email')) {
    $errors = \App\Auth::register($request);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= $appName = \App\Config::from('app')->get('name') ?></title>
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
                    <a class="navbar-brand" href="#"><?= $appName ?></a>
                </div>
            </div><!-- /.container-fluid -->
        </nav>
    </div><!--Top Nav Row-->
    <form action="register.php" method="post" class="row col-sm-4 col-sm-offset-4">
        <?php if (isset($errors)) : ?>
            <div class="alert alert-danger alert-dismissible">
                <?php foreach ($errors as $error): ?>
                    <p>
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <span class="glyphicon glyphicon-alert"></span>
                        <?= $error ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" required 
                   value="<?= $request->get('name') ?>">
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required
                value="<?= $request->get('email') ?>">
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="conf-password" class="control-label">Password</label>
            <input type="password" name="conf-password" id="conf-password" class="form-control" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Register" class="btn btn-success">
            <a href="login.php" class="pull-right" style="padding: 7px 0">Already registered?</a>
        </div>
    </form>
</div>
</body>
</html>

