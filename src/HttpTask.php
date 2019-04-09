<?php
namespace TaskQ;
class HttpTask {
    public $name;
    public $url;
    public $method = 'POST';
    public $data;
    public $headers;
    public $options;
    public function __construct($headers) {
        $this->headers($headers);
    }

    public function name($name) {
        $this->name = $name;
        return $this;
    }

    public function url($url) {
        $this->url = $url;
        return $this;
    }

    public function data(array $data) {
        $this->data = $data;
        return $this;
    }

    public function headers(array $headers) {
        $this->headers = array_merge((array)$this->headers, $headers);
        return $this;
    }

    public function options(callable $options) {
        $this->options = array_filter((array) $options(new TaskOptions()), 'strlen');
        return $this;
    }

    public function method($method) {
        $this->method = strtoupper($method);
        return $this;
    }
}