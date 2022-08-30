<?php

namespace WebforceHQ\Tests;

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Requests\CertificateRequest;
use Symfony\Component\Dotenv\Dotenv;

class BaseTestCase extends TestCase
{
    protected $kubernetesEndpoint;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        (new Dotenv())->usePutenv()->bootEnv(dirname(__DIR__).'\.env');
        $this->kubernetesEndpoint = getenv('K8_ENDPOINT');
        $this->token = getenv('K8_TOKEN');

    }
}