<?php

namespace WebforceHQ\KubernetesApi\Models\IngressResources;

class IngressRulePath
{
    public $path;
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
    
    /**
     * Set the value of path
     *
     * @return  self
     */
    public function setBackend($serviceName, $servicePort)
    {
        $this->backend['serviceName'] = $serviceName;
        $this->backend['servicePort'] = $servicePort;

        return $this;
    }
}
