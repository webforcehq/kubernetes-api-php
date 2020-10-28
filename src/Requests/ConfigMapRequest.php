<?php

namespace WebforceHQ\KubernetesApi\Requests;

use WebforceHQ\KubernetesApi\Models\Certificate;
use WebforceHQ\KubernetesApi\Models\ConfigMap;
use WebforceHQ\KubernetesApi\Models\Ingress;
use WebforceHQ\KubernetesApi\Requests\Helpers\KubeResponse;

class ConfigMapRequest extends KubeRequest
{
    const resource      = "configmaps";
    const apiVersion    = "v1";
    const api           = "api";

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

    public function create(ConfigMap $ingress): KubeResponse
    {
        return $this->post($ingress->toArray())->sendRequest();
    }

    public function destroy(string $ingressName): KubeResponse
    {
        return $this->delete("/" . $ingressName)->sendRequest();
    }

    public function update(ConfigMap $ingress): KubeResponse
    {
        $name  = $ingress->getMetadata()->getName();
        return $this->put("/" . $name, $ingress->toArray())->sendRequest();
    }
}
