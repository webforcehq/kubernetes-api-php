<?php

namespace WebforceHQ\KubernetesApi\Models\IngressResources;

class IngressTls
{
    public array $hosts = [];
    public string $secretName;
    

    /**
     * Get the value of host
     */
    public function getHosts(): array
    {
        return $this->host;
    }

    /**
     * Set the value of host
     *
     * @return  self
     */
    public function setHosts(array $host)
    {
        $this->hosts = $host;

        return $this;
    }

    /**
     * Get the value of secretName
     */
    public function getSecretName()
    {
        return $this->secretName;
    }

    /**
     * Set the value of secretName
     *
     * @return  self
     */
    public function setSecretName($secretName)
    {
        $this->secretName = $secretName;

        return $this;
    }
}
