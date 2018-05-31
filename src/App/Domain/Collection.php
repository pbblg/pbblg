<?php

namespace App\Domain;

class Collection extends \ArrayObject
{
    /**
     * @return array
     */
    public function getIds()
    {
        $result = [];

        foreach ($this as $entity) {
            $result[] = $entity->getId();
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }

    /**
     * @param $attribute
     * @return Collection
     */
    public function rebuildByKey($attribute)
    {
        $collection = new self();

        if ($this->isEmpty()) {
            return $collection;
        }

        $methodName = 'get' . ucfirst($attribute);

        if (!method_exists(reset($this), $methodName)) {
            throw new \RuntimeException("Entity " . get_class(reset($this)) . " does not have a method $methodName");
        }

        foreach ($this as $entity) {
            $collection[$entity->{$methodName}()] = $entity;
        }

        return $collection;
    }

    /**
     * @param $attribute
     * @param bool $unique
     * @return array
     */
    public function getValueByAttribute($attribute, $unique = true)
    {
        $methodName = 'get' . ucfirst($attribute);

        if (!method_exists(reset($this), $methodName)) {
            throw new \RuntimeException("Entity " . get_class(reset($this)) . " does not have a method $methodName");
        }

        $result = [];

        foreach ($this as $entity) {
            $result[] = $entity->{$methodName}();
        }

        if ($unique) {
            $result = array_unique($result);
        }

        return $result;
    }

    /**
     * @param int $excludeId
     * @return Collection
     */
    public function getExclude($excludeId)
    {
        $collection = new self();

        foreach ($this as $entity) {
            if ($entity->getId() != $excludeId) {
                $collection[$entity->getId()] = $entity;
            }
        }

        return $collection;
    }
}
