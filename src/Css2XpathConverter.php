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

namespace CodeAlfa\Css2Xpath;

class Css2XpathConverter
{
    private array $cache = [];

    private SelectorFactoryInterface $selectorFactory;

    public function __construct(SelectorFactoryInterface $selectorFactory)
    {
        $this->selectorFactory = $selectorFactory;
    }

    public function convert($css): string
    {
        return $this->cache[$css] ??= $this->selectorFactory->createCssSelectorList(
            $this->selectorFactory,
            $css
        )->render();
    }
}
