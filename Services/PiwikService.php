<?php
/**
 * @package Newscoop\PiwikBundle
 * @copyright 2014 Sourcefabric o.p.s.
 * @author Evelyn Graumann <evelyn.graumann@sourcefabric.org>
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PiwikBundle\Services;

use Doctrine\ORM\EntityManager;
use Newscoop\PiwikBundle\Entity\PublicationSettings;

/**
 * Piwik service
 */

class PiwikService
{
    /** @var Doctrine\ORM\EntityManager */
    protected $em;
    private $publicationSetting;

    /**
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $request = \Zend_Registry::get('container')->getService('request');
        
        $requestPub = $request->attributes->get('_newscoop_publication_metadata');
        $pubId = $requestPub['alias']['publication_id'];
        $this->publicationSetting = $this->em->getRepository('Newscoop\PiwikBundle\Entity\PublicationSettings')->findOneByPublication($pubId);
    }

    public function getTracker()
    {
        $type = $this->publicationSetting->getType();
        if ($type == 1) {
            return $this->getJavascriptTracker();
        } else {
            return $this->getImageTracker();
        }
    }

    public function getJavascriptTracker()
    {
        $url = $this->publicationSetting->getPiwikUrl();
        $id = $this->publicationSetting->getPiwikId();

        return $this->getJavascriptTrackerCode($url, $id);
    }

    public function getJavascriptTrackerCode($url, $id) 
    {
            $html = '';
            $html .= '<!-- Piwik -->' . "\n" . '<script type="text/javascript">';
            $html .= 'var _paq = _paq || [];';
            $html .= '_paq.push(["trackPageView"]);';
            $html .= '_paq.push(["enableLinkTracking"]);(function() {';
            $html .= 'var u=(("https:" == document.location.protocol) ? "https" : "http") + "://'. $url .'/";';
            $html .= '_paq.push(["setTrackerUrl", u+"piwik.php"]);';
            $html .= '_paq.push(["setSiteId","'. $id .'"]);';
            $html .= 'var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";';
            $html .= 'g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);})();';
            $html .= '</script>' . "\n" . '<!-- End Piwik Code -->';

            return $html;
    }

    public function getImageTracker()
    {
        $url = $this->publicationSetting->getPiwikUrl();
        $id = $this->publicationSetting->getPiwikId();

        return $this->getImageTrackerCode($url, $id);
    }

    public function getImageTrackerCode($url, $id)
    {
            $imgtrack = '';
            $imgtrack .= '<!-- Piwik Image Tracker -->' . "\n";
            $imgtrack .= '<img src="http://' . $url . '/piwik.php?idsite=' . $id . '&amp;rec=1" style="border:0" alt="" />' . "\n";
            $imgtrack .= '<!-- End Piwik -->' ;

            return $imgtrack;
    }
}