<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 03/07/14
 * Time: 17:03
 */

namespace Cib\Bundle\UserBundle\EventListener;


use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationConfirmListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm'
        );
    }

    public function onRegistrationConfirm(GetResponseUserEvent $event)
    {
        $url = $this->router->generate('index');

        $event->setResponse(new RedirectResponse($url));
    }
}
