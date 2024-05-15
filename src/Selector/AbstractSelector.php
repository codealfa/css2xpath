<?php

namespace CodeAlfa\Css2Xpath\Selector;

use Stringable;

abstract class AbstractSelector implements SelectorInterface, Stringable
{
    public function __toString(): string
    {
        return $this->render();
    }
}
