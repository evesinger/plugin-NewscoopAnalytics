<?php

namespace Newscoop\PiwikBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/piwik_plugin/testpiwik")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return $this->render('NewscoopPiwikBundle:Default:index.html.smarty');
    }

    /**
     * @Route("/admin/piwik_plugin/reporting")
     * @Template()
     */
    public function reportingAction(Request $request)
    {
        //example reporting start
        
        $url = '';
        $url = 'http://demo.piwik.org/';
        $url .= '?module=API&method=Referrers.getKeywords';
        $url .= '&idSite=7&period=month&date=yesterday';
        $url .= '&format=PHP&filter_limit=10';

        $fetched = file_get_contents($url);
        $content = unserialize($fetched);

        $title ='Keywords for the last month for http://demo.piwik.org/ using API Referrers.getKeywords';

        $string = '';
        
        foreach ($content as $row) {

            $keyword = htmlspecialchars(html_entity_decode(urldecode($row['label']), ENT_QUOTES), ENT_QUOTES);
        
            $hits = $row['nb_visits'];

            $string .= '<tr>';
            $string .= '<td>' . $keyword . '</td>';
            $string .= '<td>' . $hits . '</td></tr>';
        
        }

        return $this->render('NewscoopPiwikBundle:Default:reporting.html.twig', array(
            'title'=>$title,
            'keyword'=>$keyword,
            'hits'=>$hits,
            'string'=>$string,));
    }

    /**
     * @Route("/admin/piwik_plugin")
     * @Template()
     */
    
    public function adminAction(Request $request)
    {

            $piwikService = $this->get('newscoop_piwik_plugin.piwikservice');

            if(isset($_REQUEST['form']['send'])) {
                $url = $_REQUEST['form']['url'];
                $id = $_REQUEST['form']['sitename'];
                $token = $_REQUEST['form']['token'];
                $type = $_REQUEST['form']['type'];
            } else {
                $confdata = $piwikService->getConfigData();
                $url = $confdata['url'];
                $id = $confdata['id'];
                $token = $confdata['token'];
                $type = $confdata['type'];
            }
            
            $choices = $piwikService->getSiteUrls($url, $token);

            $form = $this->createFormBuilder()
            ->add('url', 'text', array('data'=>$url,))
            ->add('sitename', 'choice', array(
                'choices'=>$choices, 'data'=>$id))
            ->add('token', 'text', array('data'=>$token))
            ->add('type', 'choice', array(
                'choices'=>array('JavaScript'=>'JavaScript', 'ImageTracker'=>'ImageTracker'), 'data'=>$type))
            ->add('send', 'submit')
            ->getForm();

            if (isset($_REQUEST['form']['send'])) {

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData(); 
                    $error = $piwikService->saveConfigData($data['url'], $data['sitename'], $data['token'], $data['type']);
                    $url = $data['url'];
                    $id = $data['sitename'];
                }
            }

        return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
            'form'=> $form->createView(),
            'snippet'=>$piwikService->getJavascriptTracker($url, $id),
            'imgtrack'=>$piwikService->getImageTracker($url, $id),
            'error'=>isset($error) ? $error : '',
        ));
    }
}
