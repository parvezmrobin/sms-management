<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/15/2017
 * Time: 4:50 AM
 */

require __DIR__ . "/../index.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?= \App\Config::get('app', 'name') ?></title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <script src="/js/jquery-3.2.1.js"></script>
    <script src="/js/bootstrap.js"></script>
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
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar" style="border-right: 3px ridge;">
                <ul class="nav nav-pills nav-stacked">
                    <li> <!--class="active" -->
                        <a href="#">Send SMS</span></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            Report <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Campaign Report</a></li>
                            <li><a href="#">SMS Report</a></li>
                            <li><a href="#">Transactional Report</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            Contact <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Manage Group</a></li>
                            <li><a href="#">Add Contact</a></li>
                            <li><a href="#">Manage Contact</a></li>
                            <li><a href="#">Black List</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            Account Recharge <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">BKash Recharge</a></li>
                            <li><a href="#">How to</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            API Documentation <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">API Key Change</a></li>
                            <li><a href="#">API Documentation</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Account Info</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            User Manual <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Send SMS</a></li>
                            <li><a href="#">Report</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div>
        <div class="col-md-9 col-sm-8">
            <div class="container-fluid">