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
use Countable;
use IteratorAggregate;
use SplObjectStorage;
use Traversable;

/**
 * @implements IteratorAggregate<int, CssSelector>
 */
final class CssSelectorCollection implements Countable, IteratorAggregate
{
    private SplObjectStorage $storage;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    public function add(CssSelector $selector): void
    {
        $this->storage[$selector] = null;
    }

    public function count(): int
    {
        return $this->storage->count();
    }

    /**
     * @return Traversable<int, CssSelector>
     */
    public function getIterator(): Traversable
    {
        return $this->storage;
    }
}
