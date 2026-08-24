<?php

/**
 * CSS to XPath Converter
 *
 * @package   codealfa\css2xpath
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2026 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace CodeAlfa\Css2Xpath\Selector;

class ClassSelector extends AbstractSelector
{
    public function __construct(protected string $name)
    {
        $this->name = $this->cssStripSlash($name);
    }

    public function render(): string
    {
        $delimiter = $this->getDelimiter($this->getName());

        return "@class and contains(concat(\" \", normalize-space(@class), \" \"), "
            . "{$delimiter} {$this->getName()} {$delimiter})";
    }

    public function getName(): string
    {
        return $this->name;
    }
}
