<?php

namespace WebforceHQ\KubernetesApi\Exceptions;

use Exception;

class UnsetRequestException extends Exception
{
    protected $message = "Current request object has not been set or is null";

    public function __construct($msg = null)
    {
        if ($msg) {
            $this->message = $msg;
        }
    }
}
