<?php

namespace CodeAlfa\Css2Xpath\Selector;

use Stringable;

abstract class AbstractSelector implements SelectorInterface, Stringable
{
    public function __toString(): string
    {
        return $this->render();
    }

    protected function cssStripSlash($identifier): string
    {
        return preg_replace("#\\\\([^0-9a-fA-F\n])#", '\1', $identifier);
    }

    protected function getDelimiter($identifier): string
    {
        return str_contains($identifier, '"') ? "'" : '"';
    }
}
