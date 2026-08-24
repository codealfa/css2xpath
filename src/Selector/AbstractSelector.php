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

use Stringable;

abstract class AbstractSelector implements SelectorInterface, Stringable
{
    public function __toString(): string
    {
        return $this->render();
    }

    protected function cssStripSlash(string $identifier): string
    {
        return preg_replace("#\\\\([^0-9a-fA-F\r\n])#", '\1', $identifier);
    }

    protected function getDelimiter(string $identifier): string
    {
        return str_contains($identifier, '"') ? "'" : '"';
    }
}
