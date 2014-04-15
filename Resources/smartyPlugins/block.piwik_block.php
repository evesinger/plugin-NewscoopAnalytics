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
	$tracker = $params['tracker'];

    $piwikService = \Zend_Registry::get('container')->getService('newscoop_piwik_plugin.piwikservice');

    if (isset($tracker)) {
    	if ($tracker == 1){
    		$html = $piwikService->getJavascriptTracker();
    	} elseif ($tracker == 2) {
    		$html = $piwikService->getImageTracker();
    	}
    } else {
    	 $html = $piwikService->getTracker();
    }

    return $html;
}
