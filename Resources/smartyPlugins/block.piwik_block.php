<?php
/**
 * @package Piwik plugin
 * @author Evelyn Graumann
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Newscoop piwik_block block plugin
 *
 * Type:     block
 * Name:     piwik_block
 * Purpose:  Generates the piwik tracking data for a page
 *
 * @param string
 *     $params
 * @param string
 *     $p_smarty
 * @param string
 *     $content
 *
 * @return
 *
 */

function smarty_block_piwik_block($params, $content, &$smarty, &$repeat)
{
    if (!isset($content)) {
        return '';
    }

    $smarty->smarty->loadPlugin('smarty_shared_escape_special_chars');
    $context = $smarty->getTemplateVars('gimme');

    $yaml = new  \Symfony\Component\Yaml\Parser();
    $file = __DIR__.'/../../config.yml';
    
    
    $value = $yaml->parse(file_get_contents($file));
    
    $piwik_url = $value['url'];
    $idsite = $value['id'];


    //tracking has to be included on every page before <body> tag

    //$token_auth = 'anonymous';

    $html = '';
    $html .= '<!-- Piwik -->' . "\n" . '<script type="text/javascript">';
    $html .= 'var _paq = _paq || [];';
    $html .= '_paq.push(["trackPageView"]);';
    $html .= '_paq.push(["enableLinkTracking"]);(function() {';
    $html .= 'var u=(("https:" == document.location.protocol) ? "https" : "http") + "://'. $piwik_url .'/";';
    $html .= '_paq.push(["setTrackerUrl", u+"piwik.php"]);';
    $html .= '_paq.push(["setSiteId","'. $idsite .'"]);';
    $html .= 'var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";';
    $html .= 'g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);})();';
    $html .= '</script>' . "\n" . '<!-- End Piwik Code -->';

    return $html;


}
