<?php

namespace CodeAlfa\Css2Xpath\Selector;

use SplObjectStorage;

use function implode;

class CssSelectorList extends AbstractSelector
{
    protected SplObjectStorage $selectors;

    public function __construct(SplObjectStorage $selectors)
    {
        $this->selectors = $selectors;
    }

    public function render(): string
    {
        $selectors = [];

        /** @var CssSelector $selector */
        foreach ($this->selectors as $selector) {
            $selectors[] = $selector->render();
        }

        return implode('|', $selectors);
    }

    public static function create(string $css): static
    {
        $selectors = new SplObjectStorage();
        $selectorStrings = explode(',', $css);

        foreach ($selectorStrings as $selectorString) {
            $selectors->attach(CssSelector::create($selectorString));
        }

        return new static($selectors);
    }
}
