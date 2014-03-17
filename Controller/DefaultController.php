<?php

namespace Newscoop\PiwikBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Newscoop\PiwikBundle\Form\Type\PiwikPublicationSettingsType;
use Newscoop\PiwikBundle\Entity\PublicationSettings;

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
        $em = $this->container->get('em');
        
        // menu
        $publications = $em->getRepository('Newscoop\Entity\Publication')->findall();
        $settings = $em->getRepository('Newscoop\PiwikBundle\Entity\PublicationSettings')->findOneByPublication($id);

        if ($id === null) {
            $error = "No publication ID. Please select a publication.";
        } else {
            $publication = $em->getRepository('Newscoop\Entity\Publication')->findOneById($id);
            
            if ($publication === null) {
                $error = "Invalid publication ID. Please select a publication.";
            } 
        }
       
       // content
        $publicationsettings = new PublicationSettings();

        @$publicationsettings->setPublication($publication);

        if ($settings !== null) {
            @$publicationsettings->setPiwikUrl($settings->getPiwikUrl());
            @$publicationsettings->setPiwikId($settings->getPiwikId());
            @$publicationsettings->setPiwikPost($settings->getPiwikPost());
            @$publicationsettings->setIpAnonymise($settings->getIpAnonymise());
            @$publicationsettings->setType($settings->getType());
        }
    
        $form = $this->createForm(new PiwikPublicationSettingsType(), $publicationsettings);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                //set piwik url based on form
                if ($settings !== null) {
                    $em->remove($settings);
                    $em->flush();
                }          
                $em->persist($publicationsettings);
                $em->flush();

                $sent = "Form sent";
                
                return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
                    'publications'=> $publications,
                    'form'=> $form->createView(),
                    'error'=>isset($error) ? $error : '',
                    'sent'=>isset($sent) ? $sent : '',
                    'id'=>isset($id) ? $id : '',
                ));
            }
        }

        return array(
            'form' => $form,
            'error'=>isset($error) ? $error : '',
            'publications'=>isset($publications) ? $publications : '',
            'sent'=>isset($sent) ? $sent : '',
        );
    }
}
