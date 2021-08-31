<?php

// namespace WebforceHQ\KubernetesApiTest;

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Requests\CertificateRequest;

class BaseTestCase extends TestCase
{
    private $kubernetesEndpoint;
    private $token;
}