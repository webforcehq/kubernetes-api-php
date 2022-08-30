<?php

namespace WebforceHQ\Tests;

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Requests\CertificateRequest;
use WebforceHQ\Tests\BaseTestCase;

class CertificateRequestTest extends BaseTestCase
{
    private CertificateRequest $api;

    function setUp(): void
    {
        parent::setUp();
        $request = new KubernetesApiRequest($this->kubernetesEndpoint, $this->token);
        $this->api = $request->certificatesApi();
    }

    /** @test */
    function list()
    {
        $response = $this->api->list();
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /** @test */
    function show()
    {
        $response = $this->api->show("jfrank-ssl");
        echo json_encode($response->body);
        $this->assertTrue($response->success);
    }




}