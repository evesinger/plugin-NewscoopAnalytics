<?php
/**
 * @package Newscoop\AnalyticsBundle
 * @author Evelyn Graumann <evelyn.graumann@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\AnalyticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PublicationSettings entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="plugin_analytics_publicationsettings")
 */
class PublicationSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     * @var integer
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Newscoop\Entity\Publication")
     * @ORM\JoinColumn(name="publicationId", referencedColumnName="Id")
     * @var Newscoop\Entity\Publication
     */
    private $publication;

    /**
     * @ORM\Column(type="string", name="piwikUrl")
     * @var string
     */
    private $piwikUrl;

    /**
     * @ORM\Column(type="string", name="authToken")
     * @var string
     */
    private $authToken;

    /**
     * @ORM\Column(type="integer", name="siteId")
     * @var integer
     */
    private $siteId;

    /**
     * @ORM\Column(type="boolean", name="active")
     * @var boolean
     */
    private $active;

    /**
     * @ORM\Column(type="boolean", name="ipAnonymise")
     * @var boolean
     */
    private $ipAnonymise;

    /**
     * @ORM\Column(type="integer", name="trackingType")
     * @var integer
     */
    private $trackingType;

    /**
     * @ORM\Column(type="boolean", name="piwikPost")
     * @var boolean
     */
    private $piwikPost;

    /**
     * Getter for piwikUrl
     *
     * @return string
     */
    public function getPiwikUrl()
    {
        return $this->piwikUrl;
    }

    /**
     * Setter for piwikUrl
     *
     * @param string $piwikUrl Value to set
     *
     * @return self
     */
    public function setPiwikUrl($piwikUrl)
    {
        $this->piwikUrl = $piwikUrl;
    
        return $this;
    }

    /**
     * Getter for trackingType
     *
     * @return integer
     */
    public function getTrackingType()
    {
        return $this->trackingType;
    }
    
    /**
     * Setter for trackingType
     *
     * @param integer $trackingType Value to set
     *
     * @return self
     */
    public function setTrackingType($trackingType)
    {
        $this->trackingType = $trackingType;
    
        return $this;
    }
    
    /**
     * Getter for authToken
     *
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }
    
    /**
     * Setter for authToken
     *
     * @param string $authToken Value to set
     *
     * @return self
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
    
        return $this;
    }

    /**
     * Getter for piwikPost
     *
     * @return boolean
     */
    public function getPiwikPost()
    {
        return $this->piwikPost;
    }

    /**
     * Setter for piwikPost
     *
     * @param boolean $piwikPost Value to set
     *
     * @return self
     */
    public function setPiwikPost($piwikPost)
    {
        $this->piwikPost = $piwikPost;
    
        return $this;
    }

    /**
     * Getter for siteId
     *
     * @return integer
     */
    public function getSiteId()
    {
        return $this->siteId;
    }
    
    /**
     * Setter for siteId
     *
     * @param integer $siteId Value to set
     *
     * @return self
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    
        return $this;
    }

     /**
     * Getter for active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * Setter for active
     *
     * @param boolean $active Value to set
     *
     * @return self
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }
    
    /**
     * Getter for ipAnonymise
     *
     * @return boolean
     */
    public function getIpAnonymise()
    {
        return $this->ipAnonymise;
    }
    
    /**
     * Setter for ipAnonymise
     *
     * @param boolean $ipAnonymise Value to set
     *
     * @return self
     */
    public function setIpAnonymise($ipAnonymise)
    {
        $this->ipAnonymise = $ipAnonymise;
    
        return $this;
    }
    
    /**
     * Getter for publication
     *
     * @return Newscoop\Entity\Publication
     */
    public function getPublication()
    {
        return $this->publication;
    }
    
    /**
     * Setter for publication
     *
     * @param Newscoop\Entity\Publication $publication Value to set
     *
     * @return self
     */
    public function setPublication(\Newscoop\Entity\Publication $publication)
    {
        $this->publication = $publication;
    
        return $this;
    }  
}