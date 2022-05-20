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

use CORS\Bundle\VarnishBundle\Messenger\InvalidateMessage;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\DocumentEvents;
use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Model\Element\Service;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ElementTagClearerListener implements EventSubscriberInterface
{
    public function __construct(protected MessageBusInterface $bus)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            DataObjectEvents::POST_UPDATE => 'clearTags',
            DataObjectEvents::POST_DELETE => 'clearTags',
            DocumentEvents::POST_UPDATE => 'clearTags',
            DocumentEvents::POST_DELETE => 'clearTags',
        ];
    }

    public function clearTags(ElementEventInterface $event)
    {
        $this->bus->dispatch(new InvalidateMessage($event->getElement()->getId(), Service::getElementType($event->getElement())));
    }
}
