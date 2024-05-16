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
        $delim = $this->getDelimiter($this->name);

        return "[@id={$delim}{$this->name}{$delim}]";
    }

    public function getName(): string
    {
        return $this->name;
    }
}
