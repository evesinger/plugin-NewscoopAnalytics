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

    /**
    * Getter for Tracker
    * returns empty string if option active is false, returns trackingCode if true
    *
    * @return string
    */
    public function getTracker()
    {
        $type = $this->publicationSetting->getType();
        $active = $this->publicationSetting->getActive();

        if ($active == 1) {  
            if ($type == 0) {
                return $this->getJavascriptTracker() .  "\n" . $this->getImageTracker();
            } elseif ($type == 1) {     
                return $this->getJavascriptTracker();
            } else {
                return $this->getImageTracker();
            }
        } else {
            return '';
        }
    }

    /**
    * Getter for JavaScriptTracker
    *
    * @return string
    */
    public function getJavascriptTracker()
    {
        $url = $this->publicationSetting->getPiwikUrl();
        $id = $this->publicationSetting->getPiwikId();
        $post = $this->publicationSetting->getPiwikPost();

        return $this->getJavascriptTrackerCode($url, $id, $post);
    }

    /**
    * Getter for JavaScriptTrackerCode
    * @param string     $url
    * @param integer    $id
    * @param boolean    $post
    *
    * @return string
    */
    public function getJavascriptTrackerCode($url, $id, $post) 
    {
        if ($post =='1') {
            $method = '_paq.push(["setRequestMethod", "POST"]);';
        } else {
            $method = '';
        }
        $html = '';
        $html .= '<!-- Piwik -->' . "\n" . '<script type="text/javascript">';
        $html .= 'var _paq = _paq || [];';
        $html .= '_paq.push(["trackPageView"]);' . $method;
        $html .= '_paq.push(["enableLinkTracking"]);(function() {';
        $html .= 'var u=(("https:" == document.location.protocol) ? "https" : "http") + "://'. $url .'/";';
        $html .= '_paq.push(["setTrackerUrl", u+"piwik.php"]);';
        $html .= '_paq.push(["setSiteId","'. $id .'"]);';
        $html .= 'var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";';
        $html .= 'g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);})();';
        $html .= '</script>' . "\n" . '<!-- End Piwik Code -->';

        return $html;
    }

    /**
    * Getter for ImageTracker
    *
    * @return string
    */
    public function getImageTracker()
    {
        $url = $this->publicationSetting->getPiwikUrl();
        $id = $this->publicationSetting->getPiwikId();

        return $this->getImageTrackerCode($url, $id);
    }

    /**
    * Getter for ImageTrackerCode
    * @param string     $url
    * @param integer    $id
    *
    * @return string
    */
    public function getImageTrackerCode($url, $id)
    {
        $imgtrack = '';
        $imgtrack .= '<noscript><!-- Piwik Image Tracker -->' . "\n";
        $imgtrack .= '<img src="http://' . $url . '/piwik.php?idsite=' . $id . '&amp;rec=1" style="border:0" alt="" />' . "\n";
        $imgtrack .= '<!-- End Piwik --></noscript>';

        return $imgtrack;
    }
}