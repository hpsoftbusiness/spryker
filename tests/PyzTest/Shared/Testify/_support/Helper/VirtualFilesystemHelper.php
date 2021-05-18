<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Shared\Testify\Helper;

use SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper as SprykerTestVirtualFilesystemHelper;

class VirtualFilesystemHelper extends SprykerTestVirtualFilesystemHelper
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function getVirtualDirectoryUrl(string $name): string
    {
        return $this->getVirtualRootDirectory()->getChild($name)->url() . DIRECTORY_SEPARATOR;
    }
}
