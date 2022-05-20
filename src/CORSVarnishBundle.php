<?php

declare(strict_types=1);

/**
 * CORS GmbH.
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and PCL
 */

namespace CORS\Bundle\VarnishBundle;

use FOS\HttpCacheBundle\FOSHttpCacheBundle;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\HttpKernel\Bundle\DependentBundleInterface;
use Pimcore\HttpKernel\BundleCollection\BundleCollection;

class CORSVarnishBundle extends AbstractPimcoreBundle implements DependentBundleInterface
{
    public static function registerDependentBundles(BundleCollection $collection)
    {
        $collection->addBundle(new FOSHttpCacheBundle());
    }

    public function getNiceName(): string
    {
        return 'CORS - Varnish Bundle';
    }

    public function getDescription(): string
    {
        return 'CORS Varnish Bundle';
    }
}
