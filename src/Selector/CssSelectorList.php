<?php

namespace CodeAlfa\Css2Xpath\Selector;

use CodeAlfa\Css2Xpath\SelectorFactoryInterface;
use SplObjectStorage;

use function implode;
use function preg_split;

use const PREG_SPLIT_NO_EMPTY;

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

    public static function create(SelectorFactoryInterface $selectorFactory, string $css): static
    {
        $selectors = new SplObjectStorage();
        $selectorStrings = preg_split(
            '#(?:[^,(\s]++|(?<fn>\((?>[^()]++|(?&fn))*+\))|\s++)*?\K(?:\s*+,\s*+|$)+#',
            $css,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        foreach ($selectorStrings as $selectorString) {
            $selectors->attach($selectorFactory->createCssSelector($selectorFactory, $selectorString));
        }

        return new static($selectors);
    }

    public function getSelectors(): SplObjectStorage
    {
        return $this->selectors;
    }
}
