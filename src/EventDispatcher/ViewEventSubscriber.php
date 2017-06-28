<?php

namespace MakinaCorpus\Drupal\Calista\EventDispatcher;

use MakinaCorpus\Calista\Event\ViewEvent;
use MakinaCorpus\Calista\View\Html\TwigView;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Plugs additionnal JavaScript and CSS when using an HTML view
 */
class ViewEventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ViewEvent::EVENT_VIEW => [
                ['onTwigView', 0],
            ],
        ];
    }

    /**
     * Add JS libraries
     *
     * @param ViewEvent $event
     */
    public function onTwigView(ViewEvent $event)
    {
        $view = $event->getView();

        if (function_exists('drupal_add_library') && $view instanceof TwigView) {
            drupal_add_library('calista', 'calista_page');

            $seven = variable_get('calista.seven_force');
            if (null === $seven && 'seven' === $GLOBALS['theme']) {
                drupal_add_library('calista', 'calista_seven');
            } elseif (true === $seven) {
                drupal_add_library('calista', 'calista_seven');
            }

            /*
            if ($event->getView()->visualSearchIsEnabled()) {
                drupal_add_library('calista', 'calista_search');
            }
             */
        }
    }
}
