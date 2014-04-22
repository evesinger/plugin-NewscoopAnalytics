<?php
/**
 * @package Analytics plugin
 * @author Evelyn Graumann
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Newscoop analytics_block block plugin
 *
 * Type:     block
 * Name:     analytics_block
 * Purpose:  Generates the analytics tracking data for a page
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

function smarty_block_analytics_block($params, $content, &$smarty, &$repeat)
{
    if (!isset($content)) {
        return '';
    }
	$tracker = $params['tracker'];

    $analyticsService = \Zend_Registry::get('container')->getService('newscoop_analytics_plugin.analyticsservice');

    if(isset($tracker)) {
    	switch ($tracker) {
                case 0:
                    return $analyticsService->getJavascriptTracker() .  "\n" . $analyticsService->getImageTracker();
                    break;        
                case 1:
                    return $analyticsService->getJavascriptTracker();
                    break;
                case 2:
                    return $analyticsService->getImageTracker();
                    break;
                case 3:
                    return $analyticsService->getGoogleUniversalTrackerCode($url, $id);
                    break;
                case 4:
                    return $analyticsService->getGoogleClassicTrackerCode($id);
                    break;
        }
    } else {
    	 $html = $analyticsService->getTracker();
    }

    return $html;
}
