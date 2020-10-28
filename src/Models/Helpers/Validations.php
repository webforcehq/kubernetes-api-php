<?php

namespace WebforceHQ\KubernetesApi\Models\Helpers;

use Exception;

trait Validations
{
    public function allObjectsAreValidClass(array $validClasses, array $objects)
    {
        foreach ($objects as $object) {
            $currClazz = get_class($object);
            if (! in_array($currClazz, $validClasses)) {
                $allowedClasses = join(",", $validClasses);
                throw new Exception("Object of class {$currClazz} not allowed, expecting objects of classes: {$allowedClasses}");
            }
        }
    }
}
