<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/joomla-platform
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2024 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace CodeAlfa\Css2Xpath\Tests;

use CodeAlfa\Css2Xpath\Css2XpathConverter;
use CodeAlfa\Css2Xpath\SelectorFactory;
use PHPUnit\Framework\TestCase;

class Css2XpathConverterTest extends TestCase
{
    public function converterData(): array
    {
        return [
            ['p', 'p'],
            ['ul li', 'ul/descendant::li'],
            ['ul > li', 'ul/child::li'],
            [
                'div + span.green',
                "div/following-sibling::*[1]/self::span"
                . "[@class and contains(concat(' ', normalize-space(@class), ' '), ' green ')]"
            ],
            ['#main ~ article', "*[@id='main']/following-sibling::article"],
            ['p a', "p/descendant::a"],
            ['svg|href', 'svg:href'],
            [
                '.jl-margin-auto-left\@m',
                "*[@class and contains(concat(' ', normalize-space(@class), ' '), ' jl-margin-auto-left@m ')]"
            ],
            ['[href]', "*[@href]"],
            ['[svg|href]', "*[@svg:href]"],
            ['a[href*=.png]', "a[contains(@href, '.png')]"],
            ["tool[section^='dev']", "tool[starts-with(@section, 'dev')]"],
            ['[section$="ter"]', "*[substring(@section,string-length(@section)-(string-length('ter')-1))='ter']"],
            ['[id|=jl]', "*[@id='jl' or starts-with(@id,concat('jl','-'))]"],
            ['a[width="50"]', "a[@width='50']"],
            ['[href^=https]', "*[starts-with(@href, 'https')]"],
            [':root:first-child', "*/ancestor::*[last()][not(preceding-sibling::*)]"],
            ['input:checked', "input[@selected or @checked]"],
            ['a:not([href])', "a[not(self::node()[@href])]"],
            ['a:has([href])', "a[count(descendant-or-self::*[@href]) > 0]"],
            [
                'p#main, div.container',
                "p[@id='main']|div[@class and contains(concat(' ', normalize-space(@class), ' '), ' container ')]"
            ],

        ];
    }
    /**
     * @dataProvider converterData
     */
    public function testConverter(string $css, string $xpath): void
    {
        $prefix = 'descendant-or-self::';
        $converter = new Css2XpathConverter(new SelectorFactory());
        $xpath = implode('|', array_map(
            fn($a) => $prefix . $a,
            explode('|', $xpath)
        ));

        $this->assertEquals($xpath, $converter->convert($css));
    }
}
