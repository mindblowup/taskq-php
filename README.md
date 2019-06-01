# TaskQ-PHP
TaskQ client library for PHP

##Requirement 
- php: >= v7
- composer
- [taskq = v1](https://github.com/mindblowup/taskq)

## How it works
The [TaskQ Server](https://github.com/mindblowup/taskq) should be running and listen on port 8001 for example.
Now you can use TaskQ-PHP client to send [ **tasks / queues / jobs / workers / cronjobs** ] (name it as you want) to [TaskQ Server](https://github.com/mindblowup/taskq) over HTTP API.
##Install
```bash
composer require mindblowup/taskq-php
```
##Get Started
Send task to execute one time.
```php
<?php
use TaskQ\TaskQ;
use TaskQ\HttpTask;
require './vendor/autoload.php';
$taskq = new TaskQ(':8001', '1f79ff70f7d2a26d4e1199b59ab8013d167298c02e5f2feb9910d21422a13e4a6ce86146df2b1968fc35542bac801469f66e');
$taskq->addHttpTask(function (HttpTask $tsk){
    $tsk->name('send_mail_to_user.10')
        ->method('POST')
        ->url('http://example.com/api/v1/send-mail')
        ->data([
            'userID' => 10,
            'email' => 'email@example.com'
        ]);
    return $tsk;
});
$response = $taskq->send();
if($taskq->hasErrors()){
    http_response_code(400);
    print_r($taskq->errors());
}
```

### Send More to TaskQ
```php
<?php
use TaskQ\TaskQ;
use TaskQ\HttpTask;
use TaskQ\TaskOptions;
require './vendor/autoload.php';
$taskq = new TaskQ(':8001', '1f79ff70f7d2a26d4e1199b59ab8013d167298c02e5f2feb9910d21422a13e4a6ce86146df2b1968fc35542bac801469f66e');
// one time job
$taskq->use('email_channel')->addHttpTask(function (HttpTask $tsk){
    $tsk->name('send_mail_to_user.10')
        ->method('POST')
        ->url('http://example.com/api/v1/send-mail')
        ->data([
            'userID' => 10,
            'email' => 'email@example.com'
        ]);
    return $tsk;
});
// one time job
$taskq->use('sms_channel')->addHttpTask(function (HttpTask $tsk){
    $tsk->name('send_sms_to_user.10')
        ->method('POST')
        ->url('http://example.com/api/v1/send-sms')
        ->data([
            'userID' => 10,
            'phone' => '+201234567891'
        ])->headers([
            // maybe you need to send headers to API you use.
            'Authorization' => 'Basic ZnJlZDpmcmVk'
        ]);
    return $tsk;
});
// two times job
$taskq->use('sms_channel')->addHttpTask(function (HttpTask $tsk){
    $tsk->name('reminder_sms_user.10')
        ->method('POST')
        ->url('http://example.com/api/v1/reminder-sms-if-user-not-respond')
        ->data([
            'userID' => 10,
            'phone' => '+201234567891'
        ])->headers([
            'Authorization' => 'Basic ZnJlZDpmcmVk'
        ])->options(function (TaskOptions $opt){
            $opt->every(60*60*2) // execute every 2hr
                ->startAt(strtotime('tomorrow 2pm'))
                ->repeat(2); // execute it 2 times
            return $opt;
        });
    return $tsk;
});

$response = $taskq->send();
if($taskq->hasErrors()){
    http_response_code(400);
    print_r($taskq->errors());
    exit;
}
echo json_encode($response, JSON_PRETTY_PRINT);
```
If everything ok. The response should be like this
```json
{
    "email_channel": [
        {
            "id": "bjb24kr2runadvu23gsg",
            "name": "send_mail_to_user.10",
            "url": "http:\/\/example.com\/api\/v1\/send-mail"
        }
    ],
    "sms_channel": [
        {
            "id": "bjb24kr2runadvu23gt0",
            "name": "send_sms_to_user.10",
            "url": "http:\/\/example.com\/api\/v1\/send-sms"
        },
        {
            "id": "bjb24kr2runadvu23gtg",
            "name": "reminder_sms_user.10",
            "url": "http:\/\/example.com\/api\/v1\/reminder-sms-if-user-not-respond"
        }
    ]
}
```