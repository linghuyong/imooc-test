<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Post;

class EntityPostLoadListener
{
    private $container;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     * @param string $locales Supported locales separated by '|'
     * @param string|null $defaultLocale
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        if (empty($request) || !($entity instanceof Post)) {
            return;
        }
        $entity->setLocale($request->getLocale());
    }
}
