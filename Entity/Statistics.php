<?php

namespace Positibe\Bundle\MailingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistics
 *
 * @ORM\Table(name="positibe_mailing_statistics")
 * @ORM\Entity(repositoryClass="Positibe\Bundle\MailingBundle\Repository\StatisticsRepository")
 */
class Statistics implements SwiftMailerMessageInterface
{
    const STATE_SENT = 'sent';
    const STATE_RECEIVED = 'received';
    const STATE_FAILED = 'failed';
    const STATE_OPENED = 'opened';
    const STATE_CLICKED = 'clicked';
    const STATE_REPLIED = 'replied';
    const STATE_BOUNCED = 'bounced';

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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=TRUE)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sentAt", type="datetime")
     */
    private $sentAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="openedAt", type="datetime", nullable=TRUE)
     */
    private $openedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="swift_mailer_message", type="string", length=255, nullable=TRUE)
     */
    private $swiftMailerMessage;

    /**
     * @var integer
     *
     * @ORM\Column(name="clicksCount", type="integer")
     */
    private $clicksCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="openedCount", type="integer")
     */
    private $openedCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="repliedCount", type="integer")
     */
    private $repliedCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var Mail
     *
     * @ORM\ManyToOne(targetEntity="Mail", inversedBy="statistics", cascade={"persist"})
     */
    private $mail;

    public function __construct()
    {
        $this->openedCount = 0;
        $this->clicksCount = 0;
        $this->repliedCount = 0;
        $this->createdAt = new \DateTime();
    }

    public function received()
    {
        if ($this->isState(Statistics::STATE_SENT)) {
            $this->state = Statistics::STATE_RECEIVED;
            $this->mail->setReceivedCount($this->mail->getReceivedCount() + 1);
        }
    }

    public function opened()
    {
        if ($this->isState(Statistics::STATE_RECEIVED)) {
            $this->state = Statistics::STATE_OPENED;
            $this->openedAt = new \DateTime();

        }
        $this->openedCount++;
        $this->mail->countOpened();
    }

    public function clicked()
    {
        if ($this->isState(Statistics::STATE_OPENED)) {
            $this->state = Statistics::STATE_CLICKED;
        }
        $this->clicksCount++;
        $this->mail->countClicked();
    }

    /**
     * @return $this
     */
    public function countReplied()
    {
        $this->repliedCount++;
        $this->mail->countReplied();

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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Statistics
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
     * Set sentAt
     *
     * @param \DateTime $sentAt
     * @return Statistics
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    /**
     * Get sentAt
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * Set openedAt
     *
     * @param \DateTime $openedAt
     * @return Statistics
     */
    public function setOpenedAt($openedAt)
    {
        $this->openedAt = $openedAt;

        return $this;
    }

    /**
     * Get openedAt
     *
     * @return \DateTime
     */
    public function getOpenedAt()
    {
        return $this->openedAt;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Statistics
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
     * Set clicksCount
     *
     * @param integer $clicksCount
     * @return Statistics
     */
    public function setClicksCount($clicksCount)
    {
        $this->clicksCount = $clicksCount;

        return $this;
    }

    /**
     * Get clicksCount
     *
     * @return integer
     */
    public function getClicksCount()
    {
        return $this->clicksCount;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Statistics
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
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
