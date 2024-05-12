<?php

namespace CodeAlfa\Css2Xpath\Selector;

class PseudoSelector extends AbstractSelector
{
    protected string $type;
    protected string $selector;

    public function __construct(string $selector, string $type)
    {
        $this->selector = $selector;
        $this->type = $type;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function printXpath(): string
    {
        return match ($this->selector) {
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

    public function render(): string
    {
    }
}
