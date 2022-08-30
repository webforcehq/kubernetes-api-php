<?php

namespace WebforceHQ\Tests;

// use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\Example;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Models\Ingress;
use WebforceHQ\KubernetesApi\Models\IngressResources\IngressRule;
use WebforceHQ\KubernetesApi\Models\IngressResources\IngressTls;
use WebforceHQ\KubernetesApi\Requests\IngressRequest;

class IngressRequestTest extends BaseTestCase
{
    private $ingressName    = "zk8library-api-php-ingress";
    private $host           = "testing-k8-library.webforcehq.dev";
    private $sslSecret      = "testing-k8-library-ssl";
    private $clusterIssuer  = "http-solver-staging";
    private $serviceName    = "bakery";
    private $servicePort    = "fastcgi";
    // private $servicePort    = 9000;

    private IngressRequest $api;

    function setUp(): void
    {
        parent::setUp();
        $request = new KubernetesApiRequest($this->kubernetesEndpoint, $this->token);
        $this->api = $request->ingressApi();
        sleep(2);
    }

    /** @test */
    function list()
    {
        $response = $this->api->list();
        $this->assertTrue($response->success);
    }

    /** @test */
    function create()
    {
        $ruleDomain = new IngressRule;
        $ruleDomain
            ->setHost($this->host)
            ->pushPath("/", $this->serviceName, $this->servicePort);


        $tls = new IngressTls;
        $tls
            ->setHosts([
                $ruleDomain->getHost(),
            ])
            ->setSecretName($this->sslSecret);

        $ingress = new Ingress($this->ingressName);
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => $this->clusterIssuer,
        ])
        ->setRules([ $ruleDomain ])
        ->setTls([$tls]);

        $response = $this->api->create($ingress);
        if(!$response->success) {
            var_dump($response);
        }
        $this->assertTrue( $response->success );

    }

    /** @test */
    function update()
    {
        $ruleDomain = new IngressRule;
        $ruleDomain
            ->setHost($this->host)
            ->pushPath("/", $this->serviceName, $this->servicePort);

        $tls = new IngressTls;
            $tls->setHosts([
                $ruleDomain->getHost()
            ])
            ->setSecretName($this->sslSecret);

        $ingress = new Ingress($this->ingressName);
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => $this->clusterIssuer
        ])
        ->setRules([ $ruleDomain ])
        ->setTls([$tls]);

        $response = $this->api->update($ingress);
        $this->assertTrue($response->success);
    }

    /** @test */
    function show()
    {
        $response = $this->api->show($this->ingressName);
        $this->assertTrue($response->success);
    }

    /** @test */
    function delete()
    {
        $response = $this->api->destroy($this->ingressName);
        $this->assertTrue($response->success);
    }

}