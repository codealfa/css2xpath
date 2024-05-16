<?php

namespace CodeAlfa\Css2Xpath\Selector;

class ClassSelector extends AbstractSelector
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $this->cssStripSlash($name);
    }

    public function render(): string
    {
        $delim = $this->getDelimiter($this->name);

        return "[@class and contains(concat(\" \", normalize-space(@class), \" \"), {$delim} {$this->name} {$delim})]";
    }

    public function getName(): string
    {
        return $this->name;
    }
}
