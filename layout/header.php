<?php
/**
 * Created by PhpStorm.
 * User: Parvez
 * Date: 9/15/2017
 * Time: 4:50 AM
 */

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

require __DIR__ . "/../index.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get Symfony to interface with this existing session
$session = new Session(new PhpBridgeSessionStorage());

// symfony will now interface with the existing PHP session
$session->start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
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

                <ul class="nav navbar-nav navbar-right">
                    <?php if ($session->has('username')) : ?>
                        <li>
                            <a href="#">
                                Hello, <?= \DbModel\Model::find(
                                    'users',
                                    $session->get('username'),
                                    'email')->name ?>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <?php $smsCost = \App\Config::from('app')->get('smsCost') ?>
                                Balance: <?=
                                \DbModel\Model::raw(
                                    'SELECT (
    (SELECT SUM(recharges.amount) FROM recharges WHERE recharges.user_id = 1) - 
    (SELECT SUM(campaign.sms_count) * '. $smsCost .' FROM campaign WHERE campaign.user_id = 1) - 
    (SELECT COUNT(*) * ' . $smsCost . ' FROM single_sms INNER JOIN single_sms_contact ON single_sms.id = single_sms_contact.single_sms_id WHERE single_sms.user_id = 1)
) as balance'
                                )->getIterator()->current()->balance
                                ?>
                            </a>
                        </li>
                        <li>
                            <a href="/views/auth/login.php?logout=logout">
                                <span class="glyphicon glyphicon-log-out"></span>
                                Logout
                            </a>
                        </li>
                    <?php else : ?>
                        <?php (new RedirectResponse("/views/auth/login.php")) ?>
                    <?php endif; ?>
                </ul>

            </div><!-- /.container-fluid -->
        </nav>
    </div><!--Top Nav Row-->
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar" style="border-right: 3px ridge;">
                <ul class="nav nav-pills nav-stacked">
                    <li class="dropdown"> <!--class="active" -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            Send SMS <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/views/send/number-sms.php">To Numbers</a></li>
                            <li><a href="/views/send/group-sms.php">To Groups</a></li>
                            <li><a href="/views/send/excel-sms.php">From Excel</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            Report <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/views/report/campaign.php">Campaign Report</a></li>
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
                            <li><a href="/views/group/index.php">Manage Group</a></li>
                            <li><a href="/views/contact/contact.php">Add Contact</a></li>
                            <li><a href="/views/contact/index.php">Manage Contact</a></li>
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