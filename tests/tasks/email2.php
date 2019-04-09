<?php
use TaskQ\HttpTask;
return function(HttpTask $tsk){
    $tsk->name('Send mail 2')
        ->url('http://example.com/api/v1/send-mail?data=sata');
       ->data([
           'userID' => 199,
           'email' => 'email3@example.com'
       ]);
    return $tsk;
};
