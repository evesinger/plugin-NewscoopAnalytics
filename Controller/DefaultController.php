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
     * @Route("/testnewscoop")
     */
    public function indexAction(Request $request)
    {
      return $this->render('NewscoopPiwikBundle:Default:index.html.smarty');

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

        //print ladybug_dump($yaml);
        
        file_put_contents($file, $yaml);


    }
    return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
          'form'=> $form->createView(),
        ));
        
    }

}
