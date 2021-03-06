<?php

namespace Isics\Bundle\OpenMiamMiamUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Association;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity(repositoryClass="Isics\Bundle\OpenMiamMiamUserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=128, nullable=false)
     * @Expose
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=128, nullable=false)
     * @Expose
     */
    private $lastname;

    /**
     * @var string $address1
     *
     * @ORM\Column(name="address1", type="string", length=64, nullable=false)
     * @Expose
     */
    private $address1;

    /**
     * @var string $address2
     *
     * @ORM\Column(name="address2", type="string", length=64, nullable=true)
     * @Expose
     */
    private $address2;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(name="zipcode", type="string", length=8, nullable=false)
     * @Expose
     */
    private $zipcode;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="string", length=64, nullable=false)
     * @Expose
     */
    private $city;

    /**
     * @var string $phoneNumber
     *
     * @ORM\Column(name="phone_number", type="string", length=16, nullable=true)
     * @Expose
     */
    private $phoneNumber;

    /**
     * @var Doctrine\Common\Collections\Collection $salesOrders
     *
     * @ORM\OneToMany(targetEntity="Isics\Bundle\OpenMiamMiamBundle\Entity\SalesOrder", mappedBy="user")
     */
    private $salesOrders;

    /**
     * @var Doctrine\Common\Collections\Collection $subscriptions
     *
     * @ORM\OneToMany(targetEntity="Isics\Bundle\OpenMiamMiamBundle\Entity\Subscription", mappedBy="user")
     */
    private $subscriptions;

    /**
     * @var boolean $isOrdersOpenNotificationSubscriber
     *
     * @ORM\Column(name="is_orders_open_notification_subscriber", type="boolean", nullable=false, options={"default":1})
     * @Expose
     */
    private $isOrdersOpenNotificationSubscriber;

    /**
     * @var boolean $isNewsletterSubscriber
     *
     * @ORM\Column(name="is_newsletter_subscriber", type="boolean", nullable=false, options={"default":1})
     * @Expose
     */
    private $isNewsletterSubscriber;

    /**
     * @var \Doctrine\Common\Collections\Collection $payments
     *
     * @ORM\OneToMany(targetEntity="Isics\Bundle\OpenMiamMiamBundle\Entity\Payment", mappedBy="user")
     */
    private $payments;

    /**
     * @var \Doctrine\Common\Collections\Collection $activityLogs
     *
     * @ORM\OneToMany(targetEntity="Isics\Bundle\OpenMiamMiamBundle\Entity\Activity", mappedBy="user")
     */
    private $activityLogs;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->isOrdersOpenNotificationSubscriber = true;
        $this->isNewsletterSubscriber = true;
    }

    /**
     * Sets address line 1
     *
     * @param string $address1
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
    }

    /**
     * Returns address line 1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Sets address line 2
     *
     * @param string $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * Returns address line 2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Sets city
     *
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Sets firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Returns firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Sets lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Returns lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Sets zip code
     *
     * @param string $zipcode
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * Returns zip code
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Sets isOrdersOpenNotificationSubscriber
     *
     * @param boolean $isOrdersOpenNotificationSubscriber
     */
    public function setIsOrdersOpenNotificationSubscriber($isOrdersOpenNotificationSubscriber)
    {
        $this->isOrdersOpenNotificationSubscriber = $isOrdersOpenNotificationSubscriber;
    }

    /**
     * Returns isOrdersOpenNotificationSubscriber
     *
     * @return boolean
     */
    public function getIsOrdersOpenNotificationSubscriber()
    {
        return $this->isOrdersOpenNotificationSubscriber;
    }

    /**
     * Sets isNewsletterSubscriber
     *
     * @param boolean $isNewsletterSubscriber
     */
    public function setIsNewsletterSubscriber($isNewsletterSubscriber)
    {
        $this->isNewsletterSubscriber = $isNewsletterSubscriber;
    }

    /**
     * Returns isNewsletterSubscriber
     *
     * @return boolean
     */
    public function getIsNewsletterSubscriber()
    {
        return $this->isNewsletterSubscriber;
    }

    /**
     * Return subscription for association
     *
     * @param Association $association
     *
     * @return Subscription
     */
    public function getSubscriptionForAssociation(Association $association)
    {
        foreach ($this->subscriptions as $subcription) {
            if ($subcription->getAssociation()->getId() == $association->getId()) {
                return $subcription;
            }
        }

        return null;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->username = $email;
        $this->usernameCanonical = $email;
    }

    /**
     * @param \Isics\Bundle\OpenMiamMiamUserBundle\Entity\Doctrine\Common\Collections\Collection $salesOrders
     */
    public function setSalesOrders($salesOrders)
    {
        $this->salesOrders = $salesOrders;
    }

    /**
     * @return \Isics\Bundle\OpenMiamMiamUserBundle\Entity\Doctrine\Common\Collections\Collection
     */
    public function getSalesOrders()
    {
        return $this->salesOrders;
    }
}
