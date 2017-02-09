<?php

namespace Positibe\Bundle\MailingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmMediaBundle\Entity\Media;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * Template
 *
 * @ORM\Table(name="positibe_mailing_template")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\MailingBundle\Repository\TemplateRepository")
 */
class Template implements ResourceInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=TRUE)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean")
     */
    protected $public = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleteable", type="boolean")
     */
    protected $deleteable = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var array
     *
     * @ORM\Column(name="availableRoles", type="array")
     */
    protected $availableRoles;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Media", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id")
     */
    protected $image;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->description;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Template
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Template
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Template
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Template
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Template
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set deleteable
     *
     * @param boolean $deleteable
     * @return Template
     */
    public function setDeleteable($deleteable)
    {
        $this->deleteable = $deleteable;

        return $this;
    }

    /**
     * Get deleteable
     *
     * @return boolean
     */
    public function getDeleteable()
    {
        return $this->deleteable;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Template
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set availableRoles
     *
     * @param array $availableRoles
     * @return Template
     */
    public function setAvailableRoles($availableRoles)
    {
        $this->availableRoles = $availableRoles;

        return $this;
    }

    /**
     * Get availableRoles
     *
     * @return array
     */
    public function getAvailableRoles()
    {
        return $this->availableRoles;
    }

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Media $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
