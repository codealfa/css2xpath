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

namespace CodeAlfa\Css2Xpath\Collections;

use CodeAlfa\Css2Xpath\Selector\CssSelector;
use InvalidArgumentException;
use SplObjectStorage;

class CssSelectorCollection extends SplObjectStorage
{
    public function offsetSet(mixed $object, mixed $info = null): void
    {
        if (!($object instanceof CssSelector)) {
            throw new InvalidArgumentException('Only ClassSelector instances can be attached.');
        }
        parent::offsetSet($object, $info);
    }

    public function current(): CssSelector
    {
        return parent::current();
    }

    /**
     * @deprecated
     */
    public function attach(object $object, mixed $info = null): void
    {
        $this->offsetSet($object, $info);
    }
}
