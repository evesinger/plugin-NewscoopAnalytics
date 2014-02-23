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
     * @Route("/admin/piwik_plugin/")
     * @Route("/admin/piwik_plugin/{id}/", name="setting_id")
     * @Template()
     */
    
    public function adminAction(Request $request, $id=null)
    {
        // menu
        $em = $this->container->get('em');
        $publications = $em->getRepository('Newscoop\Entity\Publication')->findall();

        if ($id === null) {
                $error = "No publication ID. Please select a publication.";
        } else {
            $publication = $em->getRepository('Newscoop\Entity\Publication')->findOneById($id);

            if($publication === null) {
                $error = "Invalid publication ID. Please select a publication.";
            }
        }

        // content
        $publicationsettings = new PublicationSettings();
        $form = $this->createForm(new PiwikPublicationSettingsType(), $publicationsettings);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $em->persist($publicationsettings);
                $em->flush();
            
                return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
                    'publications'=>$publications,
                    'form'=> $form->createView(),
                    'error'=>isset($error) ? $error : '',
                ));
            } 
        }

        return array(
            'form' => $form
        );
    }
}
