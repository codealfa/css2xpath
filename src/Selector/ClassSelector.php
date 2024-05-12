<?php

namespace CodeAlfa\Css2Xpath\Selector;

class ClassSelector extends AbstractSelector
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function render(): string
    {
        return "[@class and contains(concat(' ', normalize-space(@class), ' '), ' {$this->name} ')]";
    }
}
