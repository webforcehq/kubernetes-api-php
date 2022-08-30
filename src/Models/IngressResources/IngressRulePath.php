<?php

namespace WebforceHQ\KubernetesApi\Models\IngressResources;

class IngressRulePath
{
    public $path;
    public $pathType;
    public $backend = [];


    /**
     * Set the value of path
     *
     * @return  self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function setPathType($type)
    {
        $this->pathType = $type;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */
    public function setBackend($serviceName, $servicePort)
    {
        $this->backend = [
            'service' => [
                'name' => $serviceName,
                'port' => []
            ]
        ];

        if(gettype($servicePort) === 'string') {
            $this->backend['service']['port']['name']   = $servicePort;
        } else {
            $this->backend['service']['port']['number'] = $servicePort;
        }
        return $this;
    }
}
