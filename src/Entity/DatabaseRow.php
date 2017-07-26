<?php

namespace MakinaCorpus\Drupal\Calista\Entity;

/**
 * Arbitrary entity
 */
class DatabaseRow
{
    private $entityType;
    private $values;

    /**
     * Default constructor
     */
    public function __construct(array $values, $entityType = null)
    {
        $this->values = $values;
        $this->entityType = $entityType;
    }

    /**
     * Get entity type if any
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }
}
