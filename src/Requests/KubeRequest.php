<?php

namespace WebforceHQ\KubernetesApi\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use WebforceHQ\KubernetesApi\Exceptions\UnsetRequestException;
use WebforceHQ\KubernetesApi\Requests\Helpers\KubeResponse;

class KubeRequest
{
    protected $baseEndpoint;
    protected $api;
    protected $apiVersion;
    protected $token;
    protected $namespace;

    protected $resourceEndpoint;

    private array $headers;
    protected Client $client;
    
    public function __construct($token, $baseEndpoint, $api, $apiVersion, $namespace, $resource)
    {
        $this->baseEndpoint = $baseEndpoint;
        $this->api          = $api;
        $this->apiVersion   = $apiVersion;
        $this->namespace    = $namespace;
        $this->resource     = $resource;
        $this->token        = $token;
        $this->setUpHttpClient();
        $this->generateResourceEndpoint();
    }

    private function setUpHttpClient()
    {
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

    public function isOnline()
    {
        $this->currentRequest = new Request('POST', $this->apiUrl."test", $this->headers);
        return $this;
    }

    public function post(array $payload)
    {
        $payload = json_encode($payload);
        $this->currentRequest = new Request('POST', $this->resourceEndpoint, $this->headers, $payload);
        return $this;
    }

    public function get($endpoint = "")
    {
        $this->currentRequest = new Request('GET', $this->resourceEndpoint.$endpoint, $this->headers);
        return $this;
    }

    public function delete($endpoint)
    {
        $this->currentRequest   = new Request("DELETE", $this->resourceEndpoint.$endpoint, $this->headers);
        return $this;
    }

    public function put($endpoint, $payload)
    {
        $payload = json_encode($payload);
        $this->currentRequest   = new Request('PUT', $this->resourceEndpoint.$endpoint, $this->headers, $payload);
        return $this;
    }

    public function patch($endpoint, $payload)
    {
        $payload = json_encode($payload);
        $this->currentRequest   = new Request('PATCH', $this->resourceEndpoint.$endpoint, $this->headers, $payload);
        return $this;
    }

    public function generateResourceEndpoint()
    {
        $this->resourceEndpoint = $this->baseEndpoint . $this->api . '/' . $this->apiVersion ."/namespaces/" . $this->namespace ."/". $this->resource;
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
}
