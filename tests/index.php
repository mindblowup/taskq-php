<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';
require_once '../src/TaskQ.php';
require_once '../src/HttpTask.php';
require_once '../src/TaskOptions.php';
include 'tasks/email.php';
//--------------------------
$taskq = new TaskQ\TaskQ(':8001', '1f79ff70f7d2a26d4e1199b59ab8013d167298c02e5f2feb9910d21422a13e4a6ce86146df2b1968fc35542bac801469f66e');
$taskq->setHeaders([
    'global-header' => 'global value'
]);

$taskq->use('email_channel')->addHttpTask((new Email())->welcome());
$taskq->addHttpTask(include 'tasks/email2.php');
$taskq->use('sms')->addHttpTask(include 'tasks/sms.php');
$taskq->send();
