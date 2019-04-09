<?php
namespace TaskQ;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class TaskQ {
    private $port;
    private $secret;
    private $channel = 'default';
    private $headers = [];
    private $http_tasks = [];
    private $taskQ_url;
    private $task_api = [];
    private $http_client;
    public const TASK_HTTP = 'http';
    public const TASK_COMMAND = 'command';
    public function __construct($port, $secret) {
        $this->port = $port;
        $this->secret = $secret;
        $this->headers = $this->defaultHeader();
        $this->taskQ_url = $port[0] == ':' ? 'http://localhost' . $port : $port;
        $this->task_api['list'] = '/list';
        $this->task_api['clear'] = '/clear';
        $this->task_api['add_http_task'] = '/add-http-task';
        $this->task_api['remove_http_task'] = '/remove-http-task';
        $this->task_api['add_command_task'] = '/add-command-task';
        $this->task_api['remove_command_task'] = '/remove-command-task';
        $this->http_client = new Client(['base_uri' => $this->taskQ_url, 'timeout' => 2]);
    }

    private function defaultHeader(){
        return ['Content-Type' => 'application/json'];
    }

    public function use($channel){
        $this->channel = $channel;
        return $this;
    }

    public function setHeaders(array $headers) {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function addHttpTask(callable $task){
        if(!array_key_exists($this->channel, $this->http_tasks)){
            $this->http_tasks[$this->channel] = [];
        }

        $this->http_tasks[$this->channel][] =  (array) $task(new HttpTask($this->headers));
        return $this;
    }

    public function send(){
//        echo "<pre>";
        foreach ($this->http_tasks as $channel => $tasks) {
            $this->sendRequest(self::TASK_HTTP, $channel, $tasks);
            unset($this->http_tasks[$channel]);
        }
//        print_r($this->http_tasks);
        return $this;
    }

    private function sendRequest($type ,$channel, array $task) {
        echo "<pre>";
        print_r($task);
        try {
            $response = $this->http_client->post($this->taskQ_url . '/add-http-task', [
//            $response = $this->http_client->post('http://instadiet.route/api/v1/test-taskq/post-success', [
                'query' => [
                    'secret' => $this->secret,
                    'channel' => $channel
                ],
                'json' => $task
            ]);

//            print_r((string)$response->getBody());
        } catch (ClientException $e) {
            echo Psr7\str($e->getRequest());
            echo Psr7\str($e->getResponse());
        } catch (RequestException $e) {
            echo Psr7\str($e->getRequest());
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
            }
        }
    }
}