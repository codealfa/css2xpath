<?php

namespace CodeAlfa\Css2Xpath\Selector;

class TypeSelector extends AbstractSelector
{
    protected ?string $namespace;
    protected string $name;

    public function __construct(string $name, ?string $namespace = null)
    {
        $this->name = $name;
        $this->namespace = $namespace;
    }

    public function render(): string
    {
        $namespace = $this->namespace !== null ? "{$this->namespace}:" : '';

        return "{$namespace}{$this->name}";
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
