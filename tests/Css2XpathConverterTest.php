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

use CodeAlfa\Css2Xpath\Css2XpathConverter;
use CodeAlfa\Css2Xpath\SelectorFactory;
use PHPUnit\Framework\TestCase;

class Css2XpathConverterTest extends TestCase
{
    public static function converterData(): array
    {
        return [
            ['p', 'descendant-or-self::p'],
            ['ul li', 'descendant-or-self::ul/descendant::li'],
            ['ul > li', 'descendant-or-self::ul/child::li'],
            [
                'div + span.green',
                'descendant-or-self::div/following-sibling::*[1]/self::span'
                . '[@class and contains(concat(" ", normalize-space(@class), " "), " green ")]'
            ],
            ['#main ~ article', 'descendant-or-self::*[@id="main"]/following-sibling::article'],
            ['p a', "descendant-or-self::p/descendant::a"],
            ['svg|href', 'descendant-or-self::svg:href'],
            [
                '.jl-margin-auto-left\@m',
                'descendant-or-self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " jl-margin-auto-left@m ")'
                . ']'
            ],
            ['[href]', "descendant-or-self::*[@href]"],
            ['[svg|href]', "descendant-or-self::*[@svg:href]"],
            ['a[href*=\.png]', 'descendant-or-self::a[contains(@href, ".png")]'],
            ["tool[section^='dev']", 'descendant-or-self::tool[starts-with(@section, "dev")]'],
            [
                '[section$="ter"]',
                'descendant-or-self::*[substring(@section,string-length(@section)-(string-length("ter")-1))="ter"]'
            ],
            ['[id|=jl]', 'descendant-or-self::*[@id="jl" or starts-with(@id,concat("jl","-"))]'],
            ['a[width="50"]', 'descendant-or-self::a[@width="50"]'],
            ['[href^=https]', 'descendant-or-self::*[starts-with(@href, "https")]'],
            [':root:first-child', "descendant-or-self::*[not(parent::*) and not(preceding-sibling::*)]"],
            ['input:checked', "descendant-or-self::input[@selected or @checked]"],
            ['a:not([href])', "descendant-or-self::a[not(self::*[@href])]"],
            ['a:has([href])', "descendant-or-self::a[count(descendant-or-self::*[@href]) > 0]"],
            [
                'p#main, div.container',
                'descendant-or-self::p[@id="main"]'
                . '|'
                . 'descendant-or-self::div['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " container ")'
                . ']'
            ],

            // =====================================================================
            // :root alone and refined form-state pseudos
            // =====================================================================
            [':root', 'descendant-or-self::*[not(parent::*)]'],
            ['input:enabled', 'descendant-or-self::input[not(@disabled)]'],
            ['input:disabled', 'descendant-or-self::input[@disabled]'],
            ['input:read-only', 'descendant-or-self::input[@readonly or @disabled]'],
            ['input:read-write', 'descendant-or-self::input[not(@readonly) and not(@disabled)]'],

            // =====================================================================
            // of-type structural pseudos
            // =====================================================================
            [
                'li:first-of-type',
                'descendant-or-self::li[not(preceding-sibling::li)]'
            ],
            [
                'li:last-of-type',
                'descendant-or-self::li[not(following-sibling::li)]'
            ],
            [
                'li:only-of-type',
                'descendant-or-self::li['
                . 'not(preceding-sibling::li)'
                . ' and not(following-sibling::li)'
                . ']'
            ],

            // =====================================================================
            // nth-child / nth-last-child
            // =====================================================================
            // Pure number → a = 0, b = 2 → count(preceding-sibling::*) + 1 = 2
            ['li:nth-child(2)', 'descendant-or-self::li[count(preceding-sibling::*) + 1 = 2]'],

            // odd → a = 2, b = 1
            [
                'li:nth-child(odd)',
                'descendant-or-self::li[(count(preceding-sibling::*) + 1 >= 1) and ((count(preceding-sibling::*) + 1 - 1) mod 2 = 0)]'
            ],

            // nth-last-child with a pure number
            [
                'li:nth-last-child(2)',
                'descendant-or-self::li[count(following-sibling::*) + 1 = 2]'
            ],

            // Invalid formula → base library ignores pseudo, behaves as plain li
            ['li:nth-child(foo)', 'descendant-or-self::li'],

            // =====================================================================
            // nth-of-type / nth-last-of-type
            // =====================================================================
            // nth-of-type(3) → a = 0, b = 3
            [
                'li:nth-of-type(3)',
                'descendant-or-self::li[count(preceding-sibling::li) + 1 = 3]'
            ],

            // nth-last-of-type(2) → a = 0, b = 2
            [
                'li:nth-last-of-type(2)',
                'descendant-or-self::li[count(following-sibling::li) + 1 = 2]'
            ],

            // =====================================================================
            // :is() / :where() – simple cases on a type selector
            // =====================================================================
            // a:is(.nav-link) → a[ self::*[ has .nav-link class ] ]
            [
                'a:is(.nav-link)',
                'descendant-or-self::a['
                . 'self::*[@class and contains(concat(" ", normalize-space(@class), " "), " nav-link ")'
                . ']'
                . ']',
            ],
            [
                'a:where(.nav-link)',
                'descendant-or-self::a['
                . 'self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " nav-link ")'
                . ']'
                . ']',
            ],
            [
                '.nav :is(a.nav-link, button.nav-link)',
                'descendant-or-self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " nav ")'
                . ']'
                . '/descendant::*['
                . 'self::a['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " nav-link ")'
                . ']'
                . '|'
                . 'self::button['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " nav-link ")'
                . ']'
                . ']'
            ],
            [
                '.menu :is(.menu-item:nth-child(2))',
                'descendant-or-self::*[@class and contains(concat(" ", normalize-space(@class), " "), " menu ")]'
                . '/descendant::*['
                . 'self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu-item ")'
                . ' and (count(preceding-sibling::*) + 1 = 2)'
                . ']'
                . ']'
            ],
            [
                '.menu :where(nav.menu-item:nth-of-type(odd), .menu-item:last-child)',
                'descendant-or-self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu ")'
                . ']'
                . '/descendant::*['
                . 'self::nav['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu-item ")'
                . ' and ((count(preceding-sibling::nav) + 1 >= 1)'
                . ' and ((count(preceding-sibling::nav) + 1 - 1) mod 2 = 0))'
                . ']'
                . '|'
                . 'self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu-item ")'
                . ' and not(following-sibling::*)'
                . ']'
                . ']'
            ],
            [
                '.menu :is(.menu-item:nth-last-child(2))',
                'descendant-or-self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu ")'
                . ']'
                . '/descendant::*['
                . 'self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu-item ")'
                . ' and (count(following-sibling::*) + 1 = 2)'
                . ']'
                . ']'
            ],
            [
                '.menu :is(a, .x, .y:nth-child(3))',
                'descendant-or-self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " menu ")'
                . ']'
                . '/descendant::*['
                . 'self::a'
                . '|'
                . 'self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " x ")'
                . ']'
                . '|'
                . 'self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " y ")'
                . ' and (count(preceding-sibling::*) + 1 = 3)'
                . ']'
                . ']'
            ],
            [
                '.uk-navbar-center:not(:only-child) ',
                'descendant-or-self::*['
                . '@class and contains(concat(" ", normalize-space(@class), " "), " uk-navbar-center ")'
                . ' and not(self::*[not(preceding-sibling::*) and not(following-sibling::*)])'
                . ']'
            ]
        ];
    }

    /**
     * @dataProvider converterData
     */
    public function testConverter(string $css, string $xpath): void
    {
        $converter = new Css2XpathConverter(new SelectorFactory());

        $this->assertEquals($xpath, $converter->convert($css));
    }
}
