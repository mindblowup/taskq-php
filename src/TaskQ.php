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
    private $errors = [];
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
        $return = [];
        foreach ($this->http_tasks as $channel => $tasks) {
            $response = $this->sendRequest(self::TASK_HTTP, $channel, $tasks);
            unset($this->http_tasks[$channel]);
            yield $channel => $response;
            $return[$channel] = json_decode($response, true)['data'];
        }
        return $return;
    }

    private function sendRequest($type ,$channel, array $task) {
        switch ($type){
            case self::TASK_COMMAND:
                $api_uri = $this->task_api['add_command_task']; break;
            case self::TASK_HTTP:
            default:
                $api_uri = $this->task_api['add_http_task']; break;
        }
        try {
            $response = $this->http_client->post($this->taskQ_url . $api_uri, [
                'query' => [
                    'secret' => $this->secret,
                    'channel' => $channel
                ],
                'json' => $task
            ]);
            return (string)$response->getBody();
        } catch (ClientException $e) {
            $this->errors[$channel] = [
                'request' => Psr7\str($e->getRequest()),
                'response' => Psr7\str($e->getResponse()),
            ];
            return false;
        } catch (RequestException $e) {
            $this->errors[$channel] = [
                'request' => Psr7\str($e->getRequest()),
                'response' => $e->hasResponse() ? Psr7\str($e->getResponse()) : '',
            ];
            return false;
        }
    }

    public function hasErrors(){
        return count($this->errors) != 0;
    }

    public function errors(){
        return $this->errors;
    }

    public function getError($channel) : array {
        return $this->errors[$channel] ?? [];
    }
}