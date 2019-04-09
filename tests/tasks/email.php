<?php

use TaskQ\HttpTask;
use TaskQ\TaskOptions;

class Email{

    public function welcome(){
        return function (HttpTask $tsk){
            $tsk->name('Send mail 1')
                ->method('POST')
                ->url('http://example.com/api/v1/send-mail')
                ->data([
                    'userID' => 10,
                    'email' => 'email@example.com'
                ])->headers([
                    'custom-header' => 'some value',
                    'Content-Type' => 'text/html'
                ])->options(function (TaskOptions $opt){
                    $opt->every(60*60*24*7)
                        ->startAt(strtotime('next friday'))
                        ->forever();
                    return $opt;
                });
            return $tsk;
        };
    }
}
