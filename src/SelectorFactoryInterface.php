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

use CodeAlfa\Css2Xpath\Selector\AttributeSelector;
use CodeAlfa\Css2Xpath\Selector\ClassSelector;
use CodeAlfa\Css2Xpath\Selector\CssSelector;
use CodeAlfa\Css2Xpath\Selector\CssSelectorList;
use CodeAlfa\Css2Xpath\Selector\IdSelector;
use CodeAlfa\Css2Xpath\Selector\PseudoClassSelector;
use CodeAlfa\Css2Xpath\Selector\PseudoElementSelector;
use CodeAlfa\Css2Xpath\Selector\TypeSelector;

interface SelectorFactoryInterface
{
    public function createCssSelectorList(
        SelectorFactoryInterface $selectorFactory,
        string $cssSelectorList
    ): CssSelectorList;

    public function createCssSelector(SelectorFactoryInterface $selectorFactory, string $cssSelector): CssSelector;

    public function createTypeSelector(string $name, ?string $namespace = null): TypeSelector;

    public function createIdSelector(string $name): IdSelector;

    public function createClassSelector(string $name): ClassSelector;

    public function createAttributeSelector(
        string $name,
        string $value = '',
        string $operator = '',
        ?string $namespace = null
    ): AttributeSelector;

    public function createPseudoClassSelector(
        SelectorFactoryInterface $selectorFactory,
        string $name,
        ?string $selectorList = null,
        string $modifier = '',
        ?string $elementName = null
    ): PseudoClassSelector;

    public function createPseudoElementSelector(
        SelectorFactoryInterface $selectorFactory,
        string $name,
    ): PseudoElementSelector;
}
