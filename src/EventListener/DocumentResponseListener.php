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

namespace CORS\Bundle\VarnishBundle\EventListener;

use CORS\Bundle\VarnishBundle\ElementHelper;
use Pimcore\Bundle\CoreBundle\EventListener\Traits\PimcoreContextAwareTrait;
use Pimcore\Http\Request\Resolver\DocumentResolver as DocumentResolverService;
use Pimcore\Model\Document\Page;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DocumentResponseListener implements EventSubscriberInterface
{
    use PimcoreContextAwareTrait;

    public function __construct(
        protected DocumentResolverService $documentResolverService,
        protected ElementHelper $elementHelper
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'addTags',
        ];
    }

    public function addTags(ResponseEvent $responseEvent)
    {
        $request = $responseEvent->getRequest();

        if (!$responseEvent->isMainRequest()) {
            return;
        }

        $document = $this->documentResolverService->getDocument($request);

        if ($document instanceof Page && $request->get('_route') === 'document_'.$document->getId()) {
            $this->elementHelper->tagElement($document);
        }
    }
}
