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

class TypeSelector extends AbstractSelector
{
    public function __construct(protected string $name, protected ?string $namespace = null)
    {
    }

    public function render(): string
    {
        $namespace = $this->getNamespace() !== null ? "{$this->getNamespace()}:" : '';

        return "{$namespace}{$this->getName()}";
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
