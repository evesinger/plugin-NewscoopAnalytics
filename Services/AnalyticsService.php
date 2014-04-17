<?php
/**
 * @package Newscoop\AnalyticsBundle
 * @copyright 2014 Sourcefabric o.p.s.
 * @author Evelyn Graumann <evelyn.graumann@sourcefabric.org>
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\AnalyticsBundle\Services;

use Doctrine\ORM\EntityManager;
use Newscoop\AnalyticsBundle\Entity\PublicationSettings;

/**
 * Analytics service
 */

class AnalyticsService
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
        $this->publicationSetting = $this->em->getRepository('Newscoop\AnalyticsBundle\Entity\PublicationSettings')->findOneByPublication($pubId);
    }

    /**
    * Getter for Tracker
    * returns empty string if option active is false, returns trackingCode if true
    *
    * @return string
    */
    public function getTracker()
    {
        $type = $this->publicationSetting->getTrackingType();
        $active = $this->publicationSetting->getActive();

        if ($active == 1) {  
            switch ($type) {
                case 0:
                    return $this->getJavascriptTracker() .  "\n" . $this->getImageTracker();
                    break;        
                case 1:
                    return $this->getJavascriptTracker();
                    break;
                case 2:
                    return $this->getImageTracker();
                    break;
                case 3:
                    return $this->getGoogleUniversalTrackerCode($url, $id);
                    break;
                case 4:
                    return $this->getGoogleClassicTrackerCode($id);
                    break;
            }   

        } else {
            return '';
        }

    }

    /**
    * Getter for Piwik JavaScriptTracker
    *
    * @return string
    */
    public function getJavascriptTracker()
    {
        $url = $this->publicationSetting->getPiwikUrl();
        $id = $this->publicationSetting->getSiteId();
        $post = $this->publicationSetting->getPiwikPost();

        return $this->getJavascriptTrackerCode($url, $id, $post);
    }

    /**
    * Getter for Piwik JavaScriptTrackerCode
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
    * Getter for Piwik ImageTracker
    *
    * @return string
    */
    public function getImageTracker()
    {
        $url = $this->publicationSetting->getPiwikUrl();
        $id = $this->publicationSetting->getSiteId();

        return $this->getImageTrackerCode($url, $id);
    }

    /**
    * Getter for Piwik ImageTrackerCode
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

    /**
    * Getter for GoogleUniversalTrackerCode
    * @param string     $url
    * @param integer    $id
    *
    * @return string
    */

    public function getGoogleUniversalTrackerCode($url, $id)
    {
        $id = $this->publicationSetting->getSiteId();
        //url is publication alias url
        $url = $this->publicationSetting->getPiwikUrl();

        $html = "";
        $html .= "<!-- Google Universal Analytics-->". "\n";
        $html .= "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[])";
        $html .= ".push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1";
        $html .= ";a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');";
        $html .= "ga('create','" . $id . "', '" . $url . "'); ga('send', 'pageview');</script>" . "\n";
        $html .= "<!-- End Google Universal Analytics -->" . "\n";
       
        return $html;
    }

    /**
    * Getter for GoogleClassicTrackerCode
    * @param integer    $id
    *
    * @return string
    */
    public function getGoogleClassicTrackerCode($id)
    {
        $id = $this->publicationSetting->getSiteId();

        $html = "";
        $html .= '<!-- Google Classic Analytics-->'. "\n";
        $html .= '<script type="text/javascript">var _gaq = _gaq || [];';
        $html .= "_gaq.push(['_setAccount', '" . $id ."']);";
        $html .= "_gaq.push(['_trackPageview']);" . "\n";
        $html .= "(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;";
        $html .= "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
        $html .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();</script>" . "\n";
        $html .= "<!-- End Google Classic Analytics -->" . "\n";

        return $html;
    }
}