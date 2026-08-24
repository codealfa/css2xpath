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

namespace CodeAlfa\Css2Xpath\Tests;

use CodeAlfa\Css2Xpath\Collections\AttributeCollection;
use CodeAlfa\Css2Xpath\Collections\ClassCollection;
use CodeAlfa\Css2Xpath\Collections\CssSelectorCollection;
use CodeAlfa\Css2Xpath\Collections\PseudoClassCollection;
use CodeAlfa\Css2Xpath\Selector\AttributeSelector;
use CodeAlfa\Css2Xpath\Selector\ClassSelector;
use CodeAlfa\Css2Xpath\Selector\CssSelector;
use CodeAlfa\Css2Xpath\Selector\PseudoClassSelector;
use CodeAlfa\Css2Xpath\SelectorFactory;
use PHPUnit\Framework\TestCase;

final class CollectionsTest extends TestCase
{
    public function testCollectionsStoreUniqueObjectsAndRemainIterable(): void
    {
        $factory = new SelectorFactory();
        $collections = [
            [new AttributeCollection(), new AttributeSelector('href')],
            [new ClassCollection(), new ClassSelector('example')],
            [new CssSelectorCollection(), new CssSelector($factory)],
            [new PseudoClassCollection(), new PseudoClassSelector($factory, 'first-child')],
        ];

        foreach ($collections as [$collection, $selector]) {
            $collection->add($selector);
            $collection->add($selector);

            self::assertCount(1, $collection);
            self::assertSame([$selector], iterator_to_array($collection));
        }
    }
}
