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

namespace CORS\Bundle\VarnishBundle\Controller;

use CORS\Bundle\VarnishBundle\ElementHelper;
use FOS\HttpCache\CacheInvalidator;
use FOS\HttpCacheBundle\CacheManager;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends \Pimcore\Bundle\AdminBundle\Controller\AdminController
{
    public function clearElementCache(Request $request, ElementHelper $elementHelper)
    {
        $this->checkPermissionsHasOneOf(['clear_cache']);

        $element = Service::getElementById($request->get('type'), (int)$request->get('id'));

        if ($element instanceof ElementInterface) {
            $elementHelper->invalidate($element);
        }

        return new JsonResponse(['success' => true]);
    }

    public function purgeCache(CacheManager $cacheManager)
    {
        $this->checkPermissionsHasOneOf(['clear_cache']);

        if ($cacheManager->supports(CacheInvalidator::CLEAR)) {
            $cacheManager->clearCache();
        }

        if ($cacheManager->supports(CacheInvalidator::INVALIDATE)) {
            $cacheManager->invalidateRegex('.*');
        }

        return new JsonResponse(['success' => true]);
    }
}
