<?php

namespace CodeAlfa\Css2Xpath\Selector;

class PseudoSelector extends AbstractSelector
{
    protected string $prefix;
    protected string $name;

    public function __construct(string $name, string $prefix)
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function render(): string
    {
        return match ($this->name) {
            'enabled' => "[@enabled]",
            'disabled' => "[@disabled]",
            'read-only' => "[@readonly]",
            'read-write' => "[@readwrite]",
            'checked' => "[@selected or @checked]",
            'required' => "[@required]",
            'root' => "/ancestor::*[last()]",
            'empty' => "[not(*) and not(normalize-space())]",
            'first-child' => "[not(preceding-sibling::*)]",
            'last-child' => "[not(following-sibling::*)]",
            'only-child' => "[not(preceding-sibling::*) and not(following-sibling::*)]",
            default => ''
        };
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
