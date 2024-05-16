<?php

namespace CodeAlfa\Css2Xpath\Selector;

class PseudoSelector extends AbstractSelector
{
    protected string $prefix;
    protected string $name;
    protected ?CssSelectorList $selectorList;
    protected string $modifier;

    public function __construct(
        string $name,
        string $prefix,
        ?CssSelectorList $selectorList = null,
        string $modifier = ''
    ) {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->selectorList = $selectorList;
        $this->modifier = $modifier;
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
            'first-of-type' => "[1]",
            'last-of-type' => "[last()]",
            'only-of-type' => "[not(preceding-sibling::*[name()=name(self::node())])"
                . " and not(following-sibling::*[name()=name(self::node())])]",
            'not' => "[not({$this->transformNotSelectorList($this->renderSelectorList())})]",
            'has' => "[count({$this->renderSelectorList()}) > 0]",
            default => ''
        };
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getSelectorList(): ?CssSelectorList
    {
        return $this->selectorList;
    }

    protected function transformNotSelectorList(string $xpath): string
    {
        return preg_replace(
            ['#^descendant-or-self::\*#', '#^descendant-or-self::#'],
            ['self::node()', ''],
            $xpath
        );
    }

    protected function renderSelectorList(): string
    {
        return (string) $this->selectorList?->render();
    }

    public function getModifier(): string
    {
        return $this->modifier;
    }
}
