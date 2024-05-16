<?php

namespace CodeAlfa\Css2Xpath\Selector;

class IdSelector extends AbstractSelector
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $this->cssStripSlash($name);
    }

    public function render(): string
    {
        return "[@id='{$this->name}']";
    }

    public function getName(): string
    {
        return $this->name;
    }
}
