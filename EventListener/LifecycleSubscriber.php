<?php
/**
 * @package Newscoop\PiwikBundle
 * @author Evelyn Graumann <evelyn.graumann@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PiwikBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;

/**
 * Event lifecycle management
 */
class LifecycleSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function install(GenericEvent $event)
    {
        
    }

    public function update(GenericEvent $event)
    {
       
    }

    public function remove(GenericEvent $event)
    {
        
    }

    public static function getSubscribedEvents()
    {
        return array(
            'plugin.install.newscoop_example_plugin' => array('install', 1),
            'plugin.update.newscoop_example_plugin' => array('update', 1),
            'plugin.remove.newscoop_example_plugin' => array('remove', 1),
        );
    }

    private function getClasses(){
        return array(
        );
    }
}