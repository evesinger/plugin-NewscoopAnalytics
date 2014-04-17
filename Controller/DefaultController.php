<?php

namespace Newscoop\AnalyticsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Newscoop\AnalyticsBundle\Form\Type\AnalyticsPublicationSettingsType;
use Newscoop\AnalyticsBundle\Entity\PublicationSettings;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/analytics/")
     * @Route("/admin/analytics/{id}/", name="setting_id")
     * @Template()
     */ 
    public function adminAction(Request $request, $id=null)
    {
        $em = $this->container->get('em');

        // menu
        $publications = $em->getRepository('Newscoop\Entity\Publication')->findall();
        $settings = $em->getRepository('Newscoop\AnalyticsBundle\Entity\PublicationSettings')->findOneByPublication($id);

        if ($id === null) {
            $error = $this->get('translator')->trans('Please select a publication from the list.');
        } else {
            $publication = $em->getRepository('Newscoop\Entity\Publication')->findOneById($id);
        }
        
        if (isset($publication)) {
            $aliasid = $publication->getDefaultAliasId();
            $alias = $em->getRepository('Newscoop\Entity\Aliases')->findOneById($aliasid);

            if (isset($alias)) {
                $aliasUrl = $alias->getName();
                $pattern = '/^(http|ftp|https)/';
                $test = preg_match($pattern, $aliasUrl);
            
                if ($test =='0') {
                    $testAlias = 'http://' . $aliasUrl;
                } else {
                    $testAlias = $aliasUrl;
                }        
                if (!filter_var($testAlias, FILTER_VALIDATE_URL)) {
                    $valid = $this->get('translator')->trans('URL is not valid.');
                }
            }
        }

        // content
        $publicationsettings = new PublicationSettings();

        if (isset($publication)) {
            $publicationsettings->setPublication($publication);
            if ($settings !== null) {
                $publicationsettings->setPiwikUrl($settings->getPiwikUrl());
                $publicationsettings->setSiteId($settings->getSiteId());
                $publicationsettings->setPiwikPost($settings->getPiwikPost());
                $publicationsettings->setIpAnonymise($settings->getIpAnonymise());
                $publicationsettings->setTrackingType($settings->getTrackingType());
                $publicationsettings->setActive($settings->getActive());
                $publicationsettings->setAuthToken($settings->getAuthToken());
            } else {
                $publicationsettings->setActive($active = true);
                $publicationsettings->setIpAnonymise($ipAnonymise = false);
            }
        }

        $form = $this->createForm(new AnalyticsPublicationSettingsType(), $publicationsettings);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($settings !== null) {
                    $em->remove($settings);
                    $em->flush();
                }            
                $em->persist($publicationsettings);
                $em->flush();

                $sent = $this->get('translator')->trans('Settings saved. You can now use analytics in your templates.');
            }
        }

        return $this->render('NewscoopAnalyticsBundle:Default:admin.html.twig', array(
            'form' => $form->createView(),
            'publications' => $publications,
            'id' => isset($id) ? $id : '',
            'error' => isset($error) ? $error : '',
            'sent' => isset($sent) ? $sent : '',
            'alias' => isset($alias) ? $alias : '',
            'valid' => isset($valid) ? $valid : '',
        ));
    }
}
