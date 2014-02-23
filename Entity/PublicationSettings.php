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
    * @ORM\Column(type="integer", name="publication_id")
     * @var integer
     */
    private $publication_id;

    /**
     * @ORM\Column(type="string", name="piwik_url")
     * @var string
     */
    private $piwik_url;

    /**
     * @ORM\Column(type="integer", name="piwik_id")
     * @var integer
     */
    private $piwik_id;

    /**
     * @ORM\Column(type="boolean", name="ip_obfuscate")
     * @var boolean
     */
    private $ip_obfuscate;

    /**
     * @ORM\Column(type="datetime", name="piwik_post")
     * @var boolean
     */
    private $piwik_post;

    /**
     * Getter for piwik_url
     *
     * @return string
     */
    public function getPiwikUrl()
    {
        return $this->piwik_url;
    }
    
    /**
     * Setter for piwik_url
     *
     * @param string $piwikUrl Value to set
     *
     * @return self
     */
    public function setPiwikUrl($piwikUrl)
    {
        $this->piwik_url = $piwikUrl;
    
        return $this;
    }
    /**
     * Getter for piwik_post
     *
     * @return boolean
     */
    public function getPiwikPost()
    {
        return $this->piwik_post;
    }
    
    /**
     * Setter for piwik_post
     *
     * @param boolean $piwikPost Value to set
     *
     * @return self
     */
    public function setPiwikPost($piwikPost)
    {
        $this->piwik_post = $piwikPost;
    
        return $this;
    }
    
    /**
     * Getter for piwik_id
     *
     * @return integer
     */
    public function getPiwikId()
    {
        return $this->piwik_id;
    }
    
    /**
     * Setter for piwik_id
     *
     * @param integer $piwikId Value to set
     *
     * @return self
     */
    public function setPiwikId($piwikId)
    {
        $this->piwik_id = $piwikId;
    
        return $this;
    }
    
}