<?php

namespace WebforceHQ\KubernetesApi\Models;

use WebforceHQ\KubernetesApi\Models\Helpers\Validations;
use WebforceHQ\KubernetesApi\Models\ResourceModel;

class Secret extends ResourceModel
{
    protected $kind = "Secret";
    protected $apiVersion = "v1";
    protected array $data;
    protected array $stringData;
    protected $type = "Opaque";

    public function __construct($resourceName)
    {
        parent::__construct($resourceName);
    }
    

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @return  self
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the value of stringData
     */
    public function getStringData()
    {
        return $this->stringData;
    }

    /**
     * Set the value of stringData
     *
     * @return  self
     */
    public function setStringData($stringData)
    {
        $this->stringData = $stringData;
        return $this;
    }
}
