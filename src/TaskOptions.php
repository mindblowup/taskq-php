<?php
namespace TaskQ;
class TaskOptions {
    public $every;
    public $repeat;
    public $startAt;
    public $timeout;
    public $retry;
    public $retry_delay;
    public $failure_callback;

    public function forever() {
        $this->repeat = 0;
        return $this;
    }
    public function every(int $every) {
        $this->every = $every;
        return $this;
    }
    public function everyHour() {
        $this->every = 3600;
        return $this;
    }
    public function everyDay() {
        $this->every = 86400;
        return $this;
    }
    public function everyWeek() {
        $this->every = 604800;
        return $this;
    }
    public function everyMonth() {
        $this->every = 2592000;
        return $this;
    }
    public function everyYear() {
        $this->every = 31536000;
        return $this;
    }
    public function repeat(int $repeat) {
        $this->repeat = $repeat;
        return $this;
    }
    public function startAt(int $startAt) {
        $this->startAt = $startAt;
        return $this;
    }
    public function timeout(int $timeout) {
        $this->timeout = $timeout;
        return $this;
    }
    public function retry(int $retry) {
        $this->retry = $retry;
        return $this;
    }
    public function retryDelay(int $retryDelay) {
        $this->retry_delay = $retryDelay;
        return $this;
    }
    public function failureCallback($failureCallback) {
        $this->failure_callback = $failureCallback;
        return $this;
    }
}