<?php

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\Example;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Models\Ingress;
use WebforceHQ\KubernetesApi\Models\IngressResources\IngressRule;
use WebforceHQ\KubernetesApi\Models\IngressResources\IngressTls;
use WebforceHQ\KubernetesApi\Requests\CertificateRequest;

class FlowIngressTest extends BaseTestCase 
{     

    private $ingressName    = "ingress-jfrank";
    private $testDomain     = "jfrank.chickenkiller.com";
    private $testDomain2    = "jfrank2.chickenkiller.com";
    private $sslSecret      = "jfrank-ssl"; 
    private $sslSecret2     = "jfrank-ssl2"; 

    private $ruleDomain;
    private $ruleDomain2;
    private $ruleSubdomain;

    private $tls;
    private $tls2;

    private KubernetesApiRequest $kubeApiRequest;


    // Create service
    // Create pod

    function setUp(): void
    {
        parent::setUp();
        $this->kubeApiRequest = new KubernetesApiRequest($this->kubernetesEndpoint, $this->token);        

        $this->ruleDomain2 = new IngressRule;
        $this->ruleDomain2
            ->setHost($this->testDomain2)
            ->pushPath("/", "hello-v1-svc", 80);

        $this->ruleDomain = new IngressRule;
        $this->ruleDomain
            ->setHost($this->testDomain)
            ->pushPath("/", "hello-v1-svc", 80);

        $this->ruleSubdomain = new IngressRule;
        $this->ruleSubdomain
                ->setHost("*." . $this->testDomain)
                ->pushPath("/", "hello-v1-svc", 80);
        
        $this->tls = new IngressTls;
        $this->tls->setHosts([
                $this->testDomain, 
                "blog.".$this->testDomain
                // "*.".$this->ruleDomain->getHost()
            ])
            ->setSecretName($this->sslSecret);

        $this->tls2 = new IngressTls;
        $this->tls2->setHosts([ $this->ruleDomain2->getHost() ])
            ->setSecretName($this->sslSecret2 );
    }

    /** @test2 */
    function createIngress()
    {                                         

        $ingress = new Ingress($this->ingressName);
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => "letsencrypt-stage-cluster-issuer"
        ])        
        ->setRules([ $this->ruleDomain, $this->ruleSubdomain ]);                              
                     
        $ingressRequest = $this->kubeApiRequest->ingressApi();
        $response = $ingressRequest->create($ingress);  
        $this->assertTrue($response->success);
    }

    /** @test2 */
    public function addCertificateToIngress()
    {

        $client = new GuzzleHttp\Client(['base_uri' => 'http://'.$this->testDomain]);
        $res = $client->request('GET', "/");

        if($res->getStatusCode() == 200)
        {
            $ingress = new Ingress($this->ingressName);
            $ingress->setAnnotations([
                'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
                'kubernetes.io/ingress.class'                   => "nginx",
                "cert-manager.io/cluster-issuer"                => "letsencrypt-stage-cluster-issuer"
            ])        
            ->setRules([ $this->ruleDomain, $this->ruleSubdomain ])
            ->setTls([$this->tls]);                        
                        
            $ingressRequest = $this->kubeApiRequest->ingressApi();
            $response = $ingressRequest->update($ingress);  
            $this->assertTrue($response->success);
        }
        
    }

    /** @test2 */
    public function addDomainToIngress()
    {
        
        $ingress = new Ingress($this->ingressName);
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => "letsencrypt-stage-cluster-issuer"
        ])        
        ->setRules([ $this->ruleDomain, $this->ruleSubdomain, $this->ruleDomain2 ])
        ->setTls([$this->tls]);
                    
        $ingressRequest = $this->kubeApiRequest->ingressApi();
        $response = $ingressRequest->update($ingress);  
        $this->assertTrue($response->success);
        
        
    }

    /** @test2 */
    public function addCertificateToNewDomain()
    {
        
        $ingress = new Ingress($this->ingressName);
        $ingress->setAnnotations([
            'nginx.ingress.kubernetes.io/rewrite-target'    => "/",
            'kubernetes.io/ingress.class'                   => "nginx",
            "cert-manager.io/cluster-issuer"                => "letsencrypt-stage-cluster-issuer"
        ])        
        ->setRules([ $this->ruleDomain, $this->ruleSubdomain, $this->ruleDomain2 ])
        ->setTls([$this->tls, $this->tls2]);                        
                    
        $ingressRequest = $this->kubeApiRequest->ingressApi();
        $response = $ingressRequest->update($ingress);  
        $this->assertTrue($response->success);        
        
    }

    /** @test2 */
    public function confirmCertificateReady()
    { 
        $certificateRequest = $this->kubeApiRequest->certificatesApi();
        $response2 = $certificateRequest->show($this->sslSecret2);

        $response = $certificateRequest->show($this->sslSecret);

        foreach($response->body->status->conditions as $condition)
        {
            if($condition->status != 'True'){
                $this->assertTrue(false);
            }
        }

        foreach($response2->body->status->conditions as $condition)
        {
            if($condition->status != 'True'){
                $this->assertTrue(false);
            }
        }

        $this->assertTrue(true);

        
        // echo json_encode($response->body->spec->secretName);
    }

    /** @test2 */
    public function deleteIngress()
    { 
        $certificateRequest = $this->kubeApiRequest->certificatesApi();
        $ingressRequest = $this->kubeApiRequest->ingressApi();
        $secretRequest = $this->kubeApiRequest->secretsApi();

        $certResponse1 = $certificateRequest->show($this->sslSecret);
        $secretName = $certResponse1->body->spec->secretName;                        

        $certResponse2 = $certificateRequest->show($this->sslSecret2);
        $secretName2 = $certResponse2->body->spec->secretName;                        

        $response = $ingressRequest->destroy($this->ingressName);
        $this->assertTrue($response->success);
    
        $secretRequest->destroy($secretName);
        $secretRequest->destroy($secretName2);
                            
    }
   
    
}