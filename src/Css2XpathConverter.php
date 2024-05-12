<?php

namespace CodeAlfa\Css2Xpath;

use CodeAlfa\Css2Xpath\Selector\CssSelectorList;

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
class Css2XpathConverter
{
    public function convert($css): string
    {
        $cssSelectorList = CssSelectorList::create($css);

        return $cssSelectorList->render();
    }
}
