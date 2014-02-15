<?php

namespace Newscoop\PiwikBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Dumper;

class DefaultController extends Controller
{
    /**
     * @Route("/testpiwik")
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
      return $this->render('NewscoopPiwikBundle:Default:reporting.html.twig');

    }

    /**
     * @Route("/admin/piwik_plugin")
     * @Template()
     */
    
    public function adminAction(Request $request)
    {
    	
        $form = $this->createFormBuilder()
        ->add('url', 'text')
        ->add('id', 'integer')
        ->add('send', 'submit')
        ->getForm();


    $form->handleRequest($request);

    if ($form->isValid()) {
      
        $data = $form->getData();

        //print ladybug_dump($data);

        $file = __DIR__.'/../config.yml';
        
        
        $dumper = new Dumper(); 
        $yaml = $dumper->dump($data, 2);

        file_put_contents($file, $yaml);


    }

    $yaml = new  \Symfony\Component\Yaml\Parser();
    $file = __DIR__.'/../config.yml';
    
    
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

    return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
          'form'=> $form->createView(),
          'snippet'=>$html,
        ));
        
    }

}
