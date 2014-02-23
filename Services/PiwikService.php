<?php
/**
 * @package Newscoop\PiwikBundle
 * @copyright 2014 Sourcefabric o.p.s.
 * @author Evelyn Graumann <evelyn.graumann@sourcefabric.org>
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PiwikBundle\Services;

use Symfony\Component\Yaml\Dumper;

/**
 * Piwik service
 */

class PiwikService
{
    /**
     * @param string $url used as preferred value
     * @param integer $id used as preferred value
     * @param string $type used as preferred value
     * @param string $token used as preferred value
     *
     * @return null
     **/

    public function saveConfigData($url, $id, $token, $type)
    {
            $file = __DIR__.'/../Resources/config/piwikconfig.yml';

            if (!is_readable($file) || !is_writable($file)) {
                return "Warning: The config file is not readable. Please change permissions.";
            } else {
                $data = array(
                    'url'=>$url, 
                    'id'=>$id,
                    'token'=>$token,
                    'type'=>$type,
                );
                $dumper = new Dumper();
                $yaml = $dumper->dump($data, 2);
                file_put_contents($file, $yaml);
            }     
    }

    public function getConfigData()
    {
            $yaml = new  \Symfony\Component\Yaml\Parser();
            $file = __DIR__.'/../Resources/config/piwikconfig.yml';

            if (!is_readable($file) || !is_writable($file)) {
                return "Warning: The config file is not readable. Please change permissions.";
            } else {         
                return $yaml->parse(file_get_contents($file));
            }
    } 

    public function getJavascriptTracker($url, $id) 
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

    public function getImageTracker($url, $id)
    {
            $imgtrack = '';
            $imgtrack .= '<!-- Piwik Image Tracker -->' . "\n";
            $imgtrack .= '<img src="http://' . $url . '/piwik.php?idsite=' . $id . '&amp;rec=1" style="border:0" alt="" />' . "\n";
            $imgtrack .= '<!-- End Piwik -->' ;

            return $imgtrack;
    }

    public function getSiteIds($url, $token)
    {
            $api = '';
            $api .= 'http://' . $url . '/';
            $api .= '?module=API&method=SitesManager.getAllSitesId';
            $api .= '&format=PHP&token_auth='. $token;

            $fetched = file_get_contents($api);
            $content = unserialize($fetched);

            foreach ($content as $row) {
                $choices[$row] = $row;
            }

            return $choices;
    }

    public function getSiteUrls($url, $token)
    {
            $api = '';
            $api .= 'http://' . $url . '/';
            $api .= '?module=API&method=SitesManager.getAllSites';
            $api .= '&format=PHP&token_auth='. $token;

            $fetched = file_get_contents($api);
            $content = unserialize($fetched);

            if (!$content) {
                $error = 'Error, no content';
                return $error;
            }

            $choices = array();

            foreach ($content as $row) {
                $choices[$row['idsite']] = $row['idsite'] . ' - ' . $row['main_url'];
            }
            return $choices;
    }

}