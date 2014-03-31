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

        // in 4.3 use getDefaultAliasId
        $aliasid = $em->getRepository('Newscoop\Entity\Publication')
        ->createQueryBuilder('a')
        ->select('a.defaultAliasId')
        ->where('a.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();

        $alias = $em->getRepository('Newscoop\Entity\Aliases')->findOneById($aliasid);

        if ($id === null) {
            $error = "Please select a publication from the list.";
        } else {
            $publication = $em->getRepository('Newscoop\Entity\Publication')->findOneById($id);
        }

        if (isset($alias)) {
            $aliasUrl = $alias->getName();
            $testAlias = 'http://' . $aliasUrl;
            if (!filter_var($testAlias, FILTER_VALIDATE_URL)) {
                $valid = "Url is not valid";
            }
        }

        // content
        $publicationsettings = new PublicationSettings();

        if (isset($publication)) {
            $publicationsettings->setPublication($publication);

            if ($settings !== null) {
                $publicationsettings->setPiwikUrl($settings->getPiwikUrl());
                $publicationsettings->setPiwikId($settings->getPiwikId());
                $publicationsettings->setPiwikPost($settings->getPiwikPost());
                $publicationsettings->setIpAnonymise($settings->getIpAnonymise());
                $publicationsettings->setType($settings->getType());
                $publicationsettings->setActive($settings->getActive());
            }
        }   
        $form = $this->createForm(new PiwikPublicationSettingsType(), $publicationsettings);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

               if ($settings !== null) {
                    $em->remove($settings);
                    $em->flush();
                }          
                $em->persist($publicationsettings);
                $em->flush();

                $sent = "Settings saved. You can now use Piwik in your templates.";
                
                return $this->render('NewscoopPiwikBundle:Default:admin.html.twig', array(
                    'publications' => $publications,
                    'form' => $form->createView(),
                    'error' => isset($error) ? $error : '',
                    'sent' => isset($sent) ? $sent : '',
                    'id' => isset($id) ? $id : '',
                    'alias' => isset($alias) ? $alias : '',
                    'valid' => isset($valid) ? $valid : '',
                ));
            }
        }

        return array(
            'form' => isset($form) ? $form : '',
            'error' => isset($error) ? $error : '',
            'sent' => isset($sent) ? $sent : '',
            'publications' => isset($publications) ? $publications : '',
            'alias' => isset($alias) ? $alias : '',
            'valid' => isset($valid) ? $valid : '',
        );
    }
}
