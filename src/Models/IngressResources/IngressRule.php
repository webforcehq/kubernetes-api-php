<?php

namespace WebforceHQ\KubernetesApi\Models\IngressResources;

use WebforceHQ\KubernetesApi\Models\Helpers\Validations;

class IngressRule
{
    use Validations;

    public $host;
    public $http = [ 'paths' => []];

    /**
     * Set the value of paths
     *
     * @return  self
     */
    public function setPaths(array $paths)
    {
        foreach ($paths as &$path) {
            if(!isset($path["pathType"])) {
                $path["pathType"] = 'Prefix';
            }
        }
        $this->allObjectsAreValidClass([IngressRulePath::class], $paths);
        $this->http['paths'] = $paths;
    }

    /**
     * Set the value of host
     *
     * @return  self
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function pushPath($path, $serviceName, $servicePort, $pathType = "Prefix")
    {
        $rulePath = new IngressRulePath;
        $rulePath->setPath($path);
        $rulePath->setPathType($pathType);
        $rulePath->setBackend($serviceName, $servicePort);
        $this->http['paths'][] = $rulePath;
    }


    /**
     * Get the value of host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the value of http
     */
    public function getHttp()
    {
        return $this->http;
    }
}
