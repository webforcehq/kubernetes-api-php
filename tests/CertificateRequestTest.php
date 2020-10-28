<?php

use PHPUnit\Framework\TestCase;
use WebforceHQ\KubernetesApi\KubernetesApiRequest;
use WebforceHQ\KubernetesApi\Requests\CertificateRequest;

class CertificateRequestTest extends TestCase 
{ 

    private $kubernetesEndpoint = "https://35.236.49.26/";
    private $token = "eyJhbGciOiJSUzI1NiIsImtpZCI6InNuQllGTlk1Z1lCb0NNWDlXM1otZGRhbGlLdXdzOFRnVE5nUkk5NFRPZncifQ.eyJpc3MiOiJrdWJlcm5ldGVzL3NlcnZpY2VhY2NvdW50Iiwia3ViZXJuZXRlcy5pby9zZXJ2aWNlYWNjb3VudC9uYW1lc3BhY2UiOiJkZWZhdWx0Iiwia3ViZXJuZXRlcy5pby9zZXJ2aWNlYWNjb3VudC9zZWNyZXQubmFtZSI6InRlc3Qtc3ZjLWFjY291bnQtdG9rZW4tczRzazgiLCJrdWJlcm5ldGVzLmlvL3NlcnZpY2VhY2NvdW50L3NlcnZpY2UtYWNjb3VudC5uYW1lIjoidGVzdC1zdmMtYWNjb3VudCIsImt1YmVybmV0ZXMuaW8vc2VydmljZWFjY291bnQvc2VydmljZS1hY2NvdW50LnVpZCI6Ijk1YTQ3NTc4LWQxN2YtNDY2YS1hZjA4LTY3MjFlMmViMTI3MCIsInN1YiI6InN5c3RlbTpzZXJ2aWNlYWNjb3VudDpkZWZhdWx0OnRlc3Qtc3ZjLWFjY291bnQifQ.TxKVG96EhQBDf9ghIvTpWZD4HCGK36cyih4lIuaBcrOg4amR5iNIEZPrIl5f01xs_fDbQBP9m_TsSIP6aJYYOuLe8mzOktfCBOvmzG917goZErfmiQN9EJ-EWwSE-DVdKGhzFVHZPVLc72RMgjdWJ9R74gNR9fUVhmiwu6Ohc3LFpzpKtTIoQvidx8ZczpyDEjMHOW6z9Ab5hiNBDZ0BSu1vC6hVVkdura3Lh5OR3eslDeJ7-bIkULF0pIG2VVUq7eCLVOr6ty-S5NeeitL161L_dRVfq3KaE_7Ro8mTpR4IyH6IIF64YLlxFXtRvh8A8Z4sFC14j8ukE1Jza2xgCA";

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