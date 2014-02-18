<?php

namespace Newscoop\PiwikBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Validator\Constraints\Range;

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
        return $this->render('NewscoopPiwikBundle:Default:reporting.html.twig');
    }

    /**
     * @Route("/admin/piwik_plugin")
     * @Template()
     */
    
    public function adminAction(Request $request)
    {
        //reads yml file
        $yaml = new  \Symfony\Component\Yaml\Parser();
        $file = __DIR__.'/../config.yml';
    
        if (!is_readable($file) || !is_writable($file)) {
            $error = "Warning: The config file is not readable. Please change permissions.";
            $html = $error;
            $imgtrack = $error;
        } else {          
            file_get_contents($file);
            $value = $yaml->parse(file_get_contents($file));
            $piwik_url = $value['url'];
            $idsite = $value['id'];

            //generates code preview for textarea
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

            //generates code preview for Piwik Image tracking
            $imgtrack = '';
            $imgtrack .= '<!-- Piwik Image Tracker -->' . "\n";
            $imgtrack .= '<img src="http://' . $piwik_url . '/piwik.php?idsite=' . $idsite . '&amp;rec=1" style="border:0" alt="" />' . "\n";
            $imgtrack .= '<!-- End Piwik -->' ;
        

            //creates form
            $form = $this->createFormBuilder()
            ->add('url', 'text', array('data'=>$piwik_url))
            ->add('id', 'integer', array('data'=>$idsite, 'constraints'=>new Range(array('min'=>1, 'minMessage'=>'Error: Site Id must be 1 or more') )))
            ->add('send', 'submit')
            ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $file = __DIR__.'/../config.yml';

                $error='';
                //permission check
                if (!is_readable($file) || !is_writable($file)) {
                    $error = "Warning: The config file is not readable. Please change permissions.";
                } else {
                    $dumper = new Dumper();
                    $yaml = $dumper->dump($data, 2);
                    file_put_contents($file, $yaml);
                }
            }
        }

        //renders form and code preview into template

        return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
            'form'=> $form->createView(),
            'snippet'=>$html,
            'imgtrack'=>$imgtrack,
            'error'=>$error,
        ));
    }
}
