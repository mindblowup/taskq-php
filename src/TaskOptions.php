<?php
namespace TaskQ;
class TaskOptions {
    public $every;
    public $repeat;
    public $startAt;
    public $timeout;
    public $retry;
    public $retryDelay;
    public $failureCallback;

    public function forever() {
        $this->repeat = 0;
        return $this;
    }
    public function every(int $every) {
        $this->every = $every;
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
        $this->retryDelay = $retryDelay;
        return $this;
    }
    public function failureCallback($failureCallback) {
        $this->failureCallback = $failureCallback;
        return $this;
    }
}