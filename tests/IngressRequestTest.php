<?php

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\Example;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Models\Ingress;
use WebforceHQ\KubernetesApi\Models\IngressResources\IngressRule;
use WebforceHQ\KubernetesApi\Models\IngressResources\IngressTls;
use WebforceHQ\KubernetesApi\Requests\IngressRequest;

class IngressRequestTest extends BaseTestCase 
{     
    private $ingressName    = "ingress-jfrank";
    private $sslSecret      = "jfrank-ssl";

    private IngressRequest $api;

    function setUp(): void
    {
        parent::setUp();
        $request = new KubernetesApiRequest($this->kubernetesEndpoint, $this->token);
        $this->api = $request->ingressApi();                
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
        $ruleDomain = new IngressRule;
        $ruleDomain
            ->setHost("jfrank.chickenkiller.com")
            ->pushPath("/", "hello-v1-svc", 80);

        $ruleSubdomain = new IngressRule;
        $ruleSubdomain
                ->setHost("*.jfrank.chickenkiller.com")
                ->pushPath("/", "hello-v1-svc", 80);
        
        $tls = new IngressTls;
        $tls
            ->setHosts([
                $ruleDomain->getHost(), 
                "*.". $ruleDomain->getHost()
            ])
            ->setSecretName($this->sslSecret);

        $ingress = new Ingress($this->ingressName);
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => "letsencrypt-stage-cluster-issuer"
        ])        
        ->setRules([ $ruleDomain, $ruleSubdomain ])
        ->setTls([$tls]);                        
                     
        $response = $this->api->create($ingress);        
        $this->assertTrue( $response->success );
        
    }

    /** @test */
    function update()
    {
        $ruleDomain = new IngressRule;
        $ruleDomain
            ->setHost("jfrank.chickenkiller.com")
            ->pushPath("/", "hello-v1-svc", 80);
 
        $tls = new IngressTls;
            $tls->setHosts([
                $ruleDomain->getHost()                
            ])
            ->setSecretName($this->sslSecret);

        $ingress = new Ingress("ingress-jfrank");
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => "letsencrypt-stage-cluster-issuer"
        ])        
        ->setRules([ $ruleDomain ])
        ->setTls([$tls]);                        
                
        $response = $this->api->update($ingress);
        // echo json_encode($response->body);
        $this->assertTrue($response->success);        
    }

    /** @test */
    function show()
    {              
        $response = $this->api->show($this->ingressName);
        // echo json_encode($response->body);
        $this->assertTrue($response->success);
    }

    /** @test */
    function delete()
    {                            
        $response = $this->api->destroy($this->ingressName);
        $this->assertTrue($response->success);
    }
    
}