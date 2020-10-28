<?php

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Models\Secret;
use WebforceHQ\KubernetesApi\Requests\SecretRequest;

class SecretRequestTest extends BaseTestCase 
{     
    private SecretRequest $api;

    function setUp(): void
    {
        parent::setUp();
        $request = new KubernetesApiRequest($this->kubernetesEndpoint, $this->token);
        $this->api = $request->secretsApi();                
    }

    /** @test */
    function list()
    {                
        $response = $this->api->list();
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /** @test */
    function create()
    {                     
        $secret = new Secret("test");
        $secret->setStringData([
            'password' => "yeah yeah baby!"
        ]);
        $response = $this->api->create($secret);
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }
        
    /** @test */
    function update()
    {                    
        $secret = new Secret("test");
        $secret->setStringData([
            'password'  => "yeah 2!",
            'account'   => "LGSUS",
        ]);   
        $response = $this->api->update($secret);
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /** @test */
    function show()
    {                       
        $response = $this->api->show("test");
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /** @test */
    function destroy()
    {                    
        $response = $this->api->destroy("test");
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }
    
}