<?php

namespace WebforceHQ\KubernetesApi\Models;

use Exception;
use JsonSerializable;
use WebforceHQ\KubernetesApi\Models\Helpers\Metadata;

abstract class ResourceModel implements JsonSerializable
{
    protected Metadata $metadata;
    protected array $spec;
    protected $apiVersion;

    public function __construct($name)
    {
        $this->metadata = new Metadata($name);
    }

    public function setLabels(array $labels)
    {
        $this->metadata->setLabels($labels);
        return $this;
    }

    public function setAnnotations(array $annotations)
    {
        $this->metadata->setAnnotations($annotations);
        return $this;
    }

    // ---- FOR REQUESTING --------
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    
    public function toArray()
    {
        return array_filter(json_decode(json_encode($this), true));
    }

    // --------- SETTER   ----------

    /**
     * Set the value of apiVersion
     *
     * @return  self
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }


 

    /**
     * Get the value of metadata
     */
    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    /**
     * Get the value of spec
     */
    public function getSpec(): array
    {
        return $this->spec;
    }

    /**
     * Set the value of spec
     *
     * @return  self
     */
    public function setSpec(array $spec)
    {
        $this->spec = $spec;

        return $this;
    }
}
