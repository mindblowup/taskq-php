<?php
use TaskQ\HttpTask;
return function(HttpTask $tsk){
    $tsk->name('Send sms')
        ->url('http://example.com/api/v1/send-sms')
        ->data([
            'userID' => 10,
            'phone' => '+201234567891'
        ]);
    return $tsk;
};
