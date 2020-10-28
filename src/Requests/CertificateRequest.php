<?php

namespace WebforceHQ\KubernetesApi\Requests;

use WebforceHQ\KubernetesApi\Models\Certificate;
use WebforceHQ\KubernetesApi\Models\Ingress;
use WebforceHQ\KubernetesApi\Requests\Helpers\KubeResponse;

class CertificateRequest extends KubeRequest
{
    const resource      = "certificates";
    const apiVersion    = "cert-manager.io/v1";
    const api           = "apis";

    public function __construct($token, $baseEndpoint, $namespace)
    {
        parent::__construct($token, $baseEndpoint, self::api, self::apiVersion, $namespace, self::resource);
    }

    public function show($ingressName): KubeResponse
    {
        return $this->get("/" . $ingressName)->sendRequest();
    }

    public function list(): KubeResponse
    {
        return $this->get()->sendRequest();
    }    

    public function destroy(string $ingressName): KubeResponse
    {
        return $this->delete("/" . $ingressName)->sendRequest();
    }

    // public function create(Certificate $ingress): KubeResponse
    // {
    //     return $this->post($ingress->toArray())->sendRequest();
    // }

    // public function update(Certificate $ingress): KubeResponse
    // {
    //     $name  = $ingress->getMetadata()->getName();
    //     return $this->patch("/" . $name, $ingress->toArray())->sendRequest();
    // }
}
