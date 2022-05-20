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

use FOS\HttpCacheBundle\CacheManager;
use FOS\HttpCacheBundle\Http\SymfonyResponseTagger;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Service;

class ElementHelper
{
    public function __construct(
        protected SymfonyResponseTagger $responseTagger,
        protected CacheManager $cacheManager
    ) {
    }

    public function tagElement(ElementInterface $element)
    {
        $this->responseTagger->addTags($this->getCacheTags($element));
    }

    public function invalidate(ElementInterface $element)
    {
        $this->cacheManager->invalidateTags($this->getCacheTags($element));
        $this->cacheManager->flush();
    }

    protected function getCacheTags(ElementInterface $element)
    {
        $type = Service::getElementType($element);

        return $element->getCacheTags([$type]);
    }
}