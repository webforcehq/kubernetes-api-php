<?php

namespace WebforceHQ\KubernetesApi\Models;

use WebforceHQ\KubernetesApi\Models\Helpers\Validations;
use WebforceHQ\KubernetesApi\Models\ResourceModel;

class Certificate extends ResourceModel
{
    protected $kind = "Certificate";
    protected $apiVersion = "extensions/v1beta1";

    public function __construct($resourceName)
    {
        parent::__construct($resourceName);
    }
    
}
