<?php

namespace App\Entity\Traits;

use http\Exception\BadMethodCallException;

trait AccessorTrait
{
    public function __call($method, $args): mixed
    {
        if (($isSetter = str_starts_with($method, "set")) ||
            str_starts_with($method, "get")) {

            $property = lcfirst(substr($method, 3));

            if ($isSetter) {
                if ($this->hasSetterAttribute($property)) {
                    $this->applyFromSetter($property, $args[0]);
                    return $this;
                }
            } else  {
                if ($this->hasGetterAttribute($property)) {
                    return $this->$property;
                }
            }
        }

        throw new BadMethodCallException("$method not found.");
    }
}
