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

namespace CORS\Bundle\VarnishBundle\Messenger;

use CORS\Bundle\VarnishBundle\ElementHelper;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\Dependency;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Service;

class InvalidateMessageHandler
{
    public function __construct(protected ElementHelper $elementHelper)
    {
    }

    public function __invoke(InvalidateMessage $message)
    {
        $element = Service::getElementById($message->getElementType(), $message->getId());

        if (!$element instanceof ElementInterface) {
            return;
        }

        $this->elementHelper->invalidate($element);

        if ($element instanceof Concrete) {
            $dependencies = $element->getDependencies();

            if (!$dependencies instanceof Dependency) {
                return;
            }

            foreach ($dependencies->getRequiredBy() as $required) {
                $this->elementHelper->invalidate($required);
            }
        }
    }
}