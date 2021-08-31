<?php

// namespace WebforceHQ\KubernetesApiTest;

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Requests\RawRequest;

class RawRequestTest extends BaseTestCase
{
    private RawRequest $api;

    function setUp(): void
    {
        parent::setUp();

        echo(getenv("KUBERNETES_ENDPOINT"));
        var_dump(getenv("KUBERNETES_ENDPOINT"));
        $request = new KubernetesApiRequest($this->kubernetesEndpoint, $this->token);
        $this->api = $request->rawAPi();
    }
    /**  @test */
    function get()
    {
        $response = $this->api->get("api/v1/namespaces/default/secrets");
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /**  @test */
    function post()
    {
        $payload = [

            'apiVersion' => 'v1',
            'kind' => 'ConfigMap',
            'metadata' => [
                'name' => 'test',
            ],

            'data' => [
                'user' => "jfcr@live.com"
            ]

        ];
        $response = $this->api->post("api/v1/namespaces/default/configmaps", $payload);
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /**  @test */
    function update()
    {
        $payload = [

            'apiVersion' => 'v1',
            'kind' => 'ConfigMap',
            'metadata' => [
                'name' => 'test',
            ],

            'data' => [
                'user' => "jfcr@live.com",
                'password' => "something",
            ]

        ];
        $response = $this->api->put("api/v1/namespaces/default/configmaps/test", $payload);
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /**  @test */
    function delete()
    {
        $response = $this->api->delete("api/v1/namespaces/default/configmaps/test");
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }
}