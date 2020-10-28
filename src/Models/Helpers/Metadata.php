<?php

namespace WebforceHQ\KubernetesApi\Models\Helpers;

class Metadata
{
    public string $name;
    public array $annotations;
    public array $labels;

    public function __construct(string $name)
    {
        $this->name         = $name;
    }

    /**
     * Get the value of labels
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * Set the value of labels
     *
     * @return  self
     */
    public function setLabels(array $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Get the value of annotations
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    /**
     * Set the value of annotations
     *
     * @return  self
     */
    public function setAnnotations(array $annotations)
    {
        $this->annotations = $annotations;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
