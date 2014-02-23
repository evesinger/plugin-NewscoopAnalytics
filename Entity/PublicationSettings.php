<?php
/**
 * @package Newscoop\PiwikBundle
 * @author Evelyn Graumann <evelyn.graumann@sourcefabric.org>
 * @copyright 2014 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\PiwikBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PublicationSettings entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="plugin_piwik_publicationsettings")
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
     * @ORM\ManyToOne(targetEntity="Newscoop\Entity\Publication")
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
     * @ORM\Column(type="integer", name="piwikId")
     * @var integer
     */
    private $piwikId;

    /**
     * @ORM\Column(type="boolean", name="ipAnonymize")
     * @var boolean
     */
    private $ipAnonymize;

    /**
     * @ORM\Column(type="datetime", name="piwikPost")
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
     * Getter for piwikId
     *
     * @return integer
     */
    public function getPiwikId()
    {
        return $this->piwikId;
    }
    
    /**
     * Setter for piwikId
     *
     * @param integer $piwikId Value to set
     *
     * @return self
     */
    public function setPiwikId($piwikId)
    {
        $this->piwikId = $piwikId;
    
        return $this;
    }
    
    /**
     * Getter for ipAnonymize
     *
     * @return boolean
     */
    public function getIpAnonymize()
    {
        return $this->ipAnonymize;
    }
    
    /**
     * Setter for ipAnonymize
     *
     * @param boolean $ipAnonymize Value to set
     *
     * @return self
     */
    public function setIpAnonymize($ipAnonymize)
    {
        $this->ipAnonymize = $ipAnonymize;
    
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