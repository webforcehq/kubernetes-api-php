<?php

namespace WebforceHQ\KubernetesApi\Requests;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use WebforceHQ\KubernetesApi\Exceptions\UnsetRequestException;
use WebforceHQ\KubernetesApi\Requests\Helpers\KubeResponse;

class RawRequest
{
    private $token;
    private $baseEndpoint;
    private $headers;
    private $client;

    public function __construct($token, $baseEndpoint)
    {
        $this->baseEndpoint = $baseEndpoint;
        $this->token        = $token;
        $this->setDefaultHeaders();
        $this->getClient();
    }

    private function getClient($params = null)
    {
        $this->client = new Client([
            'base_uri'  => $this->baseEndpoint,
            'headers'   => $this->headers,
            'verify'    => false
        ]);
    }

    private function setDefaultHeaders()
    {
        $this->headers = [
            'Authorization'         => "Bearer " . $this->token,
            'content-type'          => "application/json",
        ];
    }

    public function get($endpoint = "", array $headers = []): KubeResponse
    {
        $this->checkEndpoint($endpoint);
        $this->currentRequest = new Request('GET', $this->baseEndpoint.$endpoint, $this->getFinalHeader($headers));
        return $this->sendRequest();
    }

    public function post($endpoint = "", array $payload = []): KubeResponse
    {
        $this->checkEndpoint($endpoint);
        $payload = json_encode($payload);
        $this->currentRequest = new Request('POST', $this->baseEndpoint.$endpoint, $this->headers, $payload);
        return $this->sendRequest();
    }

    public function put($endpoint = "", array $payload = [], array $headers = []): KubeResponse
    {
        $this->checkEndpoint($endpoint);
        $payload = json_encode($payload);
        $this->currentRequest = new Request('PUT', $this->baseEndpoint.$endpoint, $this->getFinalHeader($headers), $payload);
        return $this->sendRequest();
    }

    public function patch($endpoint = "", array $payload = [], array $headers = []): KubeResponse
    {
        $this->checkEndpoint($endpoint);
        $payload = json_encode($payload);
        $this->currentRequest = new Request('PATCH', $this->baseEndpoint.$endpoint, $this->getFinalHeader($headers), $payload);
        return $this->sendRequest();
    }

    public function delete($endpoint = ""): KubeResponse
    {
        $this->checkEndpoint($endpoint);
        $this->currentRequest = new Request('DELETE', $this->baseEndpoint.$endpoint, $this->headers);
        return $this->sendRequest();
    }

    private function getFinalHeader(array $extraHeader): array
    {
        $finalHeader = $this->headers;
        foreach ($extraHeader as $key => $value) {
            $finalHeader[$key] = $value;
        }
        return $finalHeader;
    }

    protected function sendRequest(): KubeResponse
    {
        if (! $this->currentRequest) {
            throw new UnsetRequestException();
        }
        $responseObj = new KubeResponse();
        try {
            // echo "aqui";
            $response               = $this->client->send($this->currentRequest);
            $responseObj->success   = true;
            $responseObj->code      = $response->getStatusCode();
            $responseObj->body      = json_decode($response->getBody());
        } catch (ClientException $e) {
            // echo "aqui";
            $response               = $e->getResponse();
            $responseObj->code      = $response->getStatusCode();
            $responseObj->body      = json_decode($response->getBody()->getContents());
            $responseObj->success   = false;
        }

        return $responseObj;
    }

    private function checkEndpoint($endpoint)
    {
        if ($endpoint == "") {
            throw new Exception('You must provide an endpoint for create RAW request');
        }
    }
}
