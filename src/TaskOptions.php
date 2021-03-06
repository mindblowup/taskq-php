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
    public function everyHour(int $num = 1) {
        $this->every = 3600 * $num;
        return $this;
    }
    public function everyDay(int $num = 1) {
        $this->every = 86400 * $num;
        return $this;
    }
    public function everyWeek(int $num = 1) {
        $this->every = 604800 * $num;
        return $this;
    }
    public function everyMonth(int $num = 1) {
        $this->every = 2592000 * $num;
        return $this;
    }
    public function everyYear(int $num = 1) {
        $this->every = 31536000 * $num;
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