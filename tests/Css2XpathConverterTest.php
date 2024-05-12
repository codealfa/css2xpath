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
use PHPUnit\Framework\TestCase;

include_once '../vendor/autoload.php';
class Css2XpathConverterTest extends TestCase
{
    public function converterData(): array
    {
        return [
          ['p', 'p', 'type']
        ];
    }
    /**
     * @dataProvider converterData
     */
    public function testConverter($css, $xpath, $message)
    {
        $prefix = 'descendant-or-self::';
        $converter = new Css2XpathConverter();

        $this->assertEquals($prefix . $xpath, $converter->convert($css), $message);
    }
}
