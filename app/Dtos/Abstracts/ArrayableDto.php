<?php

namespace App\Dtos\Abstracts;

use Carbon\Carbon;
use Illuminate\Support\Str;
use ReflectionObject;

class ArrayableDto
{
    public function toArray(): array
    {
        $asArray = [];

        $reflectedInstance = new ReflectionObject($this);

        foreach ($reflectedInstance->getProperties() as $property) {
            if ($property->isInitialized($this)) {
                $value = $property->getValue($this);

                if (!is_null($value)) {
                    if ($value instanceof ArrayableDto) {
                        $value = $value->toArray();
                    }

                    if ($value instanceof Carbon) {
                        $value = $value->format('Y-m-d H:i:s');
                    }

                    $asArray[Str::snake($property->getName())] = $value;

                    continue;
                }
            }

            $asArray[Str::snake($property->getName())] = null;
        }

        return $asArray;
    }

    public function fillFromArray(array $data): ArrayableDto
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $propertyType = new \ReflectionProperty($this, $key);
                $type = $propertyType->getType()?->getName();

                if ($type && enum_exists($type)) {
                    $this->{$key} = $type::tryFrom($value);
                } else {
                    $this->{$key} = $value;
                }
            }
        }

        return $this;
    }
}