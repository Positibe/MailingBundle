<?php

namespace Positibe\Bundle\MailingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Positibe\Bundle\OrmMediaBundle\Entity\Media;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Mail
 *
 * @ORM\Table(name="positibe_mailing_mail")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\MailingBundle\Repository\MailRepository")
 */
class Mail implements SwiftMailerMessageInterface
{
    const STATE_DRAFT = 'draft';
    const STATE_IN_QUEUE = 'in_queue';
    const STATE_SENDING = 'sending';
    const STATE_ERROR = 'error';
    const STATE_SENT = 'sent';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=TRUE)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="subjectHtml", type="string", length=255, nullable=TRUE)
     */
    private $subjectHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=TRUE)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="bodyHtml", type="text", nullable=TRUE)
     */
    private $bodyHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="fromName", type="string", length=255, nullable=TRUE)
     */
    private $fromName;

    /**
     * @var string
     *
     * @ORM\Column(name="responseTo", type="string", length=255, nullable=TRUE)
     */
    private $responseTo;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"code"}, updatable=false)
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="swift_mailer_message", type="string", length=255, nullable=TRUE)
     */
    private $swiftMailerMessage;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendAt", type="datetime", nullable=TRUE)
     */
    private $sendAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sentAt", type="datetime", nullable=TRUE)
     */
    private $sentAt;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state = self::STATE_DRAFT;

    /**
     * @var array
     *
     * @ORM\Column(name="receivers", type="array")
     */
    private $receivers;

    /**
     * @var integer
     *
     * @ORM\Column(name="sentCount", type="integer", nullable=TRUE)
     */
    private $sentCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="failuresCount", type="integer", nullable=TRUE)
     */
    private $failuresCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="receivedCount", type="integer", nullable=TRUE)
     */
    private $receivedCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="openedCount", type="integer", nullable=TRUE)
     */
    private $openedCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="repliedCount", type="integer", nullable=TRUE)
     */
    private $repliedCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicksCount", type="integer", nullable=TRUE)
     */
    private $clicksCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="bouncedCount", type="integer", nullable=TRUE)
     */
    private $bouncedCount = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tracking", type="boolean")
     */
    private $tracking = false;

    /**
     * List of email that failed
     * @var array
     *
     * @ORM\Column(name="failures", type="array")
     */
    private $failures;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=TRUE)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="messageError", type="string", length=255, nullable=TRUE)
     */
    private $messageError;

    /**
     * @var array
     *
     * @ORM\Column(name="variables", type="array")
     */
    private $variables;

    /**
     * @var Statistics[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Statistics", mappedBy="mail", cascade={"persist", "remove"})
     */
    private $statistics;

    /**
     * @var Media[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Positibe\Bundle\OrmMediaBundle\Entity\Media", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="positibe_mailing_mail_attachments")
     */
    private $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->variables = array();
    }

    /**
     * @return $this
     */
    public function countOpened()
    {
        $this->openedCount++;

        return $this;
    }

    /**
     * @return $this
     */
    public function countClicked()
    {
        $this->clicksCount++;

        return $this;
    }

    /**
     * @return $this
     */
    public function countReplied()
    {
        $this->repliedCount++;

        return $this;
    }

    /**
     * @param $state
     * @return bool
     */
    public function isState($state)
    {
        return $this->state === $state;
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
     * Set subject
     *
     * @param string $subject
     * @return Mail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        if ($this->code === null) {
            $this->code = $subject;
        }

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
     * @return Mail
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
     * Set fromName
     *
     * @param string $fromName
     * @return Mail
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get fromName
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Set responseTo
     *
     * @param string $responseTo
     * @return Mail
     */
    public function setResponseTo($responseTo)
    {
        $this->responseTo = $responseTo;

        return $this;
    }

    /**
     * Get responseTo
     *
     * @return string
     */
    public function getResponseTo()
    {
        return $this->responseTo;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Mail
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
     * Set sentAt
     *
     * @param string $sentAt
     * @return Mail
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return string
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Mail
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
     * Set sendAt
     *
     * @param \DateTime $sendAt
     * @return Mail
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * Get sendAt
     *
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Mail
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set receivers
     *
     * @param array $receivers
     * @return Mail
     */
    public function setReceivers($receivers)
    {
        $this->receivers = $receivers;

        return $this;
    }

    /**
     * Get receivers
     *
     * @return array
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Set sentCount
     *
     * @param integer $sentCount
     * @return Mail
     */
    public function setSentCount($sentCount)
    {
        $this->sentCount = $sentCount;

        return $this;
    }

    /**
     * Get sentCount
     *
     * @return integer
     */
    public function getSentCount()
    {
        return $this->sentCount;
    }

    /**
     * @return int
     */
    public function getBouncedCount()
    {
        return $this->bouncedCount;
    }

    /**
     * @param int $bouncedCount
     */
    public function setBouncedCount($bouncedCount)
    {
        $this->bouncedCount = $bouncedCount;
    }

    /**
     * @return int
     */
    public function getClicksCount()
    {
        return $this->clicksCount;
    }

    /**
     * @param int $clicksCount
     */
    public function setClicksCount($clicksCount)
    {
        $this->clicksCount = $clicksCount;
    }

    /**
     * @return int
     */
    public function getFailuresCount()
    {
        return $this->failuresCount;
    }

    /**
     * @param int $failuresCount
     */
    public function setFailuresCount($failuresCount)
    {
        $this->failuresCount = $failuresCount;
    }

    /**
     * @return int
     */
    public function getOpenedCount()
    {
        return $this->openedCount;
    }

    /**
     * @param int $openedCount
     */
    public function setOpenedCount($openedCount)
    {
        $this->openedCount = $openedCount;
    }

    /**
     * @return int
     */
    public function getReceivedCount()
    {
        return $this->receivedCount;
    }

    /**
     * @param int $receivedCount
     */
    public function setReceivedCount($receivedCount)
    {
        $this->receivedCount = $receivedCount;
    }

    /**
     * @return int
     */
    public function getRepliedCount()
    {
        return $this->repliedCount;
    }

    /**
     * @param int $repliedCount
     */
    public function setRepliedCount($repliedCount)
    {
        $this->repliedCount = $repliedCount;
    }

    /**
     * @return boolean
     */
    public function isTracking()
    {
        return $this->tracking;
    }

    /**
     * @param boolean $tracking
     */
    public function setTracking($tracking)
    {
        $this->tracking = $tracking;
    }

    /**
     * Set failures
     *
     * @param array $failures
     * @return Mail
     */
    public function setFailures($failures)
    {
        $this->failures = $failures;

        return $this;
    }

    /**
     * Get failures
     *
     * @return array
     */
    public function getFailures()
    {
        return $this->failures;
    }

    public function addFailure($email, $name)
    {
        $this->failures[$email] = $name;
        $this->failuresCount++;

        return $this;
    }

    public function addStatistics($statistic)
    {
        $this->statistics[] = $statistic;
        $this->sentCount++;

        return $this;

    }

    /**
     * Set token
     *
     * @param string $token
     * @return Mail
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set variables
     *
     * @param array $variables
     * @return Mail
     */
    public function setVariables($variables)
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Get variables
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;


        return $this;

    }

    /**
     * @return ArrayCollection|\Positibe\Bundle\OrmMediaBundle\Entity\Media[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param ArrayCollection|\Positibe\Bundle\OrmMediaBundle\Entity\Media[] $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @param Media $attachment
     * @return $this
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param Media $attachment
     * @return $this
     */
    public function removeAttachment($attachment)
    {
        $this->attachments->removeElement($attachment);

        return $this;
    }

    /**
     * @return ArrayCollection|Statistics[]
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param ArrayCollection|Statistics[] $statistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
    }

    /**
     * @return string
     */
    public function getMessageError()
    {
        return $this->messageError;
    }

    /**
     * @param string $messageError
     */
    public function setMessageError($messageError)
    {
        $this->messageError = $messageError;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @param string $bodyHtml
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;
    }

    /**
     * @return string
     */
    public function getSwiftMailerMessage()
    {
        return $this->swiftMailerMessage;
    }

    /**
     * @param string $swiftMailerMessage
     */
    public function setSwiftMailerMessage($swiftMailerMessage)
    {
        $this->swiftMailerMessage = $swiftMailerMessage;
    }

    /**
     * @return string
     */
    public function getSubjectHtml()
    {
        return $this->subjectHtml;
    }

    /**
     * @param string $subjectHtml
     */
    public function setSubjectHtml($subjectHtml)
    {
        $this->subjectHtml = $subjectHtml;
    }
}
