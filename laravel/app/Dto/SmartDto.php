<?php
namespace App\Dto;

class SmartDto
{
    /**
     *	@description	
     *	@var	
     */
    public function __construct(array | object $array = null)
    {
        # Convert to array
        if(is_object($array)) {
            $array = $array->toArray();
        }
        # Assign all properties
        $this->generate($array);
    }
    /**
     *	@description	
     *	@var	
     */
    protected function beforeConstruct(array $array = null)
    {
        if(empty($array)) {
            return [];
        }
        return $array;
    }
    /**
     *	@description	
     *	@var	
     */
    protected function generate(array $array = null)
    {
        $thisObj = new \ReflectionClass($this);
        $properties = $thisObj->getProperties();
        $exists = [];
        $allowedNull = [];
        foreach ($properties as $property) {
            $name = $property->getName();
            $allowedNull[] = $property->getType()?->allowsNull()? $name : null;
            $exists[] = $property->getName();
        }
        $allowedNull = array_filter($allowedNull);
        $array = $this->beforeConstruct($array);
        foreach ($array as $key => $value) {
            if(in_array($key, $exists)) {
                if(empty($value) && in_array($key, $allowedNull))
                    $this->{$key} = $value;
                else {
                    if(empty($value) && is_numeric($value) || !empty($value)) {
                        $this->{$key} = $value;
                    }
                }
            }
        }
    }
    /**
     *	@description	
     *	@var	
     */
    public function getJson(): string
    {
        return json_encode($this);
    }
    /**
     *	@description	
     *	@var	
     */
    public function getArray(): array
    {
        return json_decode($this->getJson(), true);
    }
    /**
     *	@description	
     *	@var	
     */
    public function toArray(): array
    {
        return $this->getArray();
    }

    /**
     *	@description	
     *	@var	
     */
    public static function set($classPath, bool $single = false)
    {
        return $single? new self($classPath->toArray()) : $classPath->map(fn($item) => new self($item->toArray()));
    }
}