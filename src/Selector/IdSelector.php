<?php

namespace CodeAlfa\Css2Xpath\Selector;

class IdSelector extends AbstractSelector
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function render(): string
    {
        return "[@id='{$this->name}']";
    }
}
