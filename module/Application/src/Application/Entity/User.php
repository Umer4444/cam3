<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use BjyAuthorize\Provider\Role\ProviderInterface;
use Images\Entity\Albums;
use Images\Entity\Photo;
use Images\Entity\UserImage;
use PerfectWeb\Core\Utils\Status;
use ZfcUser\Entity\UserInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\Mapping\Annotation as Gedmo;
use Interactions\InteractionInterface;
use Interactions\Entity\InteractionTrait;
use PerfectWeb\Core\Entity\UserAccess;
use PerfectWeb\Core\Traits;
use Application\Entity\Custom\UserMethods;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 * @ORM\EntityListeners({"Application\Listener\UserListener"})
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends UserMethods implements UserInterface, ProviderInterface, InteractionInterface
{

    use InteractionTrait;

    const GENDER_MALE = 'm';
    const GENDER_FEMALE = 'f';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @var \Application\Entity\Logo
     *
     * @ORM\OneToMany(targetEntity="Logo", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $logos;

    /**
     * @var \Application\Entity\CallLog
     *
     * @ORM\OneToMany(targetEntity="CallLog", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $callLogs;

    /**
     * @var \Application\Entity\ChatBackground
     *
     * @ORM\OneToMany(targetEntity="ChatBackground", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $chatBackgrounds;

    /**
     * @var \Application\Entity\ScheduledMedia
     *
     * @ORM\OneToMany(targetEntity="ScheduledMedia", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $scheduledMedia;

    /**
     * @var \Application\Entity\Website
     *
     * @ORM\OneToMany(targetEntity="\Application\Entity\Website", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $websites;

    /**
     * @var \Application\Entity\BadWords
     *
     * @ORM\OneToMany(targetEntity="BadWords", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $badWords;

    /**
     * @var \Application\Entity\Autoresponders
     *
     * @ORM\OneToMany(targetEntity="Autoresponders", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     */
    protected $autoresponders;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="PerfectWeb\Payment\Entity\PurchasedContent", mappedBy="user")
     *
     */
    protected $purchasedContent;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Pledge", mappedBy="model")
     *
     */
    protected $pledges;

    /**
     * @ORM\OneToMany(targetEntity="Funder", mappedBy="user")
     */
    protected $pledgeFunder;

    /**
     * @var \Application\Entity\UserCategory
     *
     * @ORM\OneToMany(targetEntity="UserCategory", mappedBy="user", cascade={"all"})
     */
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Categories", mappedBy="user", cascade={"all"})
     */
    protected $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255,  nullable=true)
     */
    protected $username = 'anonymous';

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    protected $gender = self::GENDER_MALE;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=50, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128)
     */
    protected $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint", nullable=true)
     */
    protected $state;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PerfectWeb\Core\Entity\ResourceValue", mappedBy="user", cascade={"all"})
     */
    protected $settings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PerfectWeb\Core\Entity\UserAccess", mappedBy="user", cascade={"all"})
     */
    protected $resourceAccess;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\UserNewsletter", mappedBy="user", cascade={"all"})
     */
    protected $newsletter;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Newsletter", inversedBy="users")
     * @ORM\JoinTable(name="newsletter_websites",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="newsletter_id", referencedColumnName="id")}
     *      )
     *
     */
    protected $websiteNewsletters;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserNewsletter", mappedBy="performer", cascade={"all"})
     */
    protected $subscribedUsers;

    /**
     *
     * @ORM\OneToMany(targetEntity="Followers", mappedBy="followers", cascade={"all"})
     */
    protected $followers;

    /**     *
     * @ORM\OneToMany(targetEntity="Followers", mappedBy="followed",cascade={"all"})
     */
    protected $userFollower;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id")
     */
    protected $country;

    /**
     * @var mixed
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    protected $avatar;

    /**
     * // this field does not have setter and getter because
     * // the value is used from user_resource_value
     * // the only reason this field's existance is zf1 compatibility
     * // @todo deprecate it when zf1 is obsolete
     *
     * @var mixed
     *
     * @ORM\Column(name="about_me", type="string", nullable=true)
     */
    protected $aboutMe;

    /**
     * @var mixed
     *
     * @ORM\Column(name="joined", type="datetime", nullable=true)
     */
    protected $joined;

    /**
     * @var float
     *
     * @ORM\Column(name="credit", type="decimal", precision=6, scale=2, nullable=false)
     */
    protected $credit = 0.00;

    /**
     * @var mixed
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var mixed
     *
     * @ORM\Column(name="referral_code", type="string", nullable=true)
     */
    protected $referralCode;

    /**
     * @var mixed
     *
     * @ORM\Column(name="activation_code", type="string", nullable=true)
     */
    protected $activationCode;

    /**
     * @var mixed
     *
     * @ORM\Column(name="reset_code", type="string", nullable=true)
     */
    protected $resetCode;

    /**
     * @var mixed
     *
     * @ORM\Column(name="timezone", type="smallint", nullable=true)
     */
    protected $timezone;

    /**
     * @var mixed
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    protected $lastActivity;

    /**
     * @var mixed
     *
     * @ORM\Column(name="session_id", type="string", nullable=true)
     */
    protected $session_id;

    /**
     * @var mixed
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;

    /**
     * @var mixed
     *
     * @ORM\Column(name="broadcast_mode", type="string", nullable=true)
     */
    protected $broadcastMode;

    /**
     * @var mixed
     *
     * @ORM\Column(name="number_of_cameras", type="integer", nullable=false)
     */
    protected $numberOfCameras = 0;

    /**
     * @ORM\OneToMany(targetEntity="Videos\Entity\Video", mappedBy="user", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $videos;

    /**
     * @ORM\OneToMany(targetEntity="Videos\Entity\ProfileVideo", mappedBy="profileUser", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    protected $profileVideos;

    /**
     * @ORM\OneToMany(targetEntity="Application\Entity\WebchatHistory", mappedBy="userHistory", fetch="EXTRA_LAZY")
     */
    protected $webchatHistory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", cascade={"all"})
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="Friends", mappedBy="user", fetch="EXTRA_LAZY", cascade={"all"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $friends;

    /**
     * @ORM\Column(name="ip_address", type="string", nullable=true)
     *
     * @var mixed
     */
    protected $ipAddress;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Reviews", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     */
    protected $reviews;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Studios", inversedBy="users", cascade={"all"})
     * @ORM\JoinTable(name="user_studios",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="studios_id", referencedColumnName="id")}
     *      )
     **/
    protected $studios;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Studios", inversedBy="managers", cascade={"all"})
     * @ORM\JoinTable(name="managers_studios",
     *      joinColumns={@ORM\JoinColumn(name="manager_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="studios_id", referencedColumnName="id")}
     *      )
     **/
    protected $managers;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myHiddenModels", cascade={"all"}, fetch="EXTRA_LAZY")
     **/
    protected $hiddenFor;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="hiddenFor", cascade={"all"})
     * @ORM\JoinTable(name="hidden_models",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="model_id", referencedColumnName="id")}
     *      )
     **/
    protected $myHiddenModels;

    /**
     * @ORM\OneToMany(targetEntity="Images\Entity\Albums", mappedBy="user", cascade={"all"}, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"uploadedOn" = "DESC"})
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $albums;

    /**
     * @ORM\OneToMany(targetEntity="Images\Entity\Photo", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $photos;

    /**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="user", cascade={"persist"}, fetch="EXTRA_LAZY")
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $payments;

    /**
     * @ORM\OneToMany(targetEntity="ModelSchedule", mappedBy="user", cascade={"all"}, fetch="EXTRA_LAZY")
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $userSchedule;

    /**
     * @ORM\OneToMany(targetEntity="ModelNotes", mappedBy="model", cascade={"persist"}, fetch="EXTRA_LAZY")
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $modelNotes;

    /**
     * @ORM\OneToMany(targetEntity="WebchatSessions", mappedBy="user", cascade={"persist"})
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $webchatSessions;

    /**
     * @ORM\OneToMany(targetEntity="BlogPosts", mappedBy="user", cascade={"persist"})
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $blogPosts;

    /**
     * @ORM\OneToMany(targetEntity="TransferWall", mappedBy="sender")
     * @ORM\OrderBy({"date" = "DESC"})
     **/
    protected $contributionsHistory;

    /**
     * @ORM\OneToMany(targetEntity="TransferWall", mappedBy="receiver")
     * @ORM\OrderBy({"date" = "DESC"})
     **/
    protected $contributorsHistory;

    /**
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", nullable=true)
     */
    protected $lastName;

    /**
     * From model table
     */
    /**
     * @ORM\Column(name="screen_name", type="string", nullable=true)
     */
    protected $screenName;
    /**
     * @ORM\Column(name="new_screen_name", type="string", nullable=true)
     */
    protected $newScreenName;

    /**
     * @ORM\OneToMany(targetEntity="Images\Entity\UserImage", mappedBy="user", cascade="all")
     */
    protected $covers;

    /**
     * @ORM\Column(name="region", type="string", nullable=true)
     */
    protected $region;
    /**
     * @ORM\Column(name="city", type="string", nullable=true)
     */

    protected $city;

    /**
     * @ORM\Column(name="region_id", type="integer", nullable=true)
     */
    protected $regionId;
    /**
     * @ORM\Column(name="zip_code", type="string", nullable=true)
     */
    protected $zipCode;
    /**
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    protected $address;
    /**
     * @ORM\Column(name="address_real", type="string", nullable=true)
     */
    protected $addressReal;


    /**
     * @ORM\Column(name="birthday_real", type="string", nullable=true)
     */
    protected $birthdayReal;
    /**
     * @ORM\Column(name="ssn", type="string", nullable=true)
     */
    protected $ssn;
    /**
     * @ORM\Column(name="status", type="string", nullable=true)
     */
    protected $status;
    /**
     * @ORM\Column(name="status_profile", type="string", nullable=true)
     */
    protected $statusProfile;
        /**
     * @ORM\Column(name="next_performance", type="string", nullable=true)
     */
    protected $nextPerformance;

    /**
     * @todo rename to broadcast_type after we remove zf1 completly
     * @ORM\Column(name="chat_type", type="string", nullable=true)
     */
    protected $broadcastType;

    /**
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    protected $active = Status::ACTIVE;

    /**
     * @ORM\Column(name="last_notification", type="string", nullable=true)
     */
    protected $lastNotification;
    /**
     * @ORM\Column(name="notification_email", type="string", nullable=true)
     */
    protected $notificationEmail;
    /**
     * @ORM\Column(name="terms_agreed", type="string", nullable=true)
     */
    protected $termsAgreed;
    /**
     * @ORM\Column(name="terms_signature", type="string", nullable=true)
     */
    protected $termsSignature;
    /**
     * @ORM\Column(name="display_order", type="string", nullable=true)
     */
    protected $displayOrder;

    /**
     * @ORM\Column(name="auto_approve", type="string", nullable=true)
     */
    protected $autoApprove;
    /**
     * @ORM\Column(name="guestbook", type="string", nullable=true)
     */
    protected $guestbook;
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="receiver")
     **/
    protected $receivedMessages;
    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="sender")
     **/
    protected $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity="Show", mappedBy="user")
     **/
    protected $shows;

    /**
     * @ORM\OneToMany(targetEntity="Videos\Entity\PremiereVideo", mappedBy="user")
     **/
    protected $premiereVideos;

    /**
     *
     * construct function for array collection
     */
    public function __construct()
    {
        $this->contributionsHistory = new ArrayCollection();
        $this->contributorsHistory = new ArrayCollection();
        $this->studios = new ArrayCollection();
        $this->blogPosts = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->userSchedule = new ArrayCollection();
        $this->settings = new ArrayCollection();
        $this->resourceAccess = new ArrayCollection();
        $this->newsletter = new ArrayCollection();
        $this->performer = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->shows = new ArrayCollection();
        $this->websites = new ArrayCollection();
        $this->badWords = new ArrayCollection();
        $this->autoresponders = new ArrayCollection();
        $this->callLogs = new ArrayCollection();
        $this->webchatSessions = new ArrayCollection();
        $this->covers= new ArrayCollection();

        // videos
        $this->videos = new ArrayCollection();
        $this->profileVideos = new ArrayCollection();
        $this->premiereVideos = new ArrayCollection();
        // /videos
        $this->albums = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->lastActivity = $this->joined = new \DateTime();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPremiereVideos()
    {
        return $this->premiereVideos;
    }

    /**
     * @param mixed $premiereVideos
     */
    public function setPremiereVideos($premiereVideos)
    {
        $this->premiereVideos = $premiereVideos;
    }

    /**
     * @return mixed
     */
    public function getProfileVideos()
    {
        return $this->profileVideos;
    }

    /**
     * @param mixed $profileVideos
     */
    public function setProfileVideos($profileVideos)
    {
        $this->profileVideos = $profileVideos;
    }

    /**
     * @return mixed
     */
    public function getAboutMe()
    {

        return $this->aboutMe;
    }

    /**
     * @param mixed $aboutMe
     */
    public function setAboutMe($aboutMe)
    {

        $this->aboutMe = $aboutMe;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {

        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getScreenName()
    {

        return $this->screenName;
    }

    /**
     * @return mixed
     */
    public function getShows()
    {
        return $this->shows;
    }

    /**
     * @param mixed $shows
     */
    public function setShows($shows)
    {
        $this->shows = $shows;
    }

    /**
     * @param mixed $screenName
     */
    public function setScreenName($screenName)
    {

        $this->screenName = $screenName;
    }

    /**
     * @return mixed
     */
    public function getNewScreenName()
    {

        return $this->newScreenName;
    }

    /**
     * @param mixed $newScreenName
     */
    public function setNewScreenName($newScreenName)
    {

        $this->newScreenName = $newScreenName;
    }

    /**
     * @return mixed
     */
    public function getCover($type = UserImage::COVER)
    {
        return $this->covers->filter(function($cover) use ($type){
            return $cover->getType() == $type;
        })->first();
    }

    /**
     * @param mixed $cover
     */
    public function addCover($cover)
    {
        if (!$this->covers->contains($cover)) {
            $this->covers->add($cover);
        }
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {

        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {

        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {

        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getRegionId()
    {

        return $this->regionId;
    }

    /**
     * @param mixed $regionId
     */
    public function setRegionId($regionId)
    {

        $this->regionId = $regionId;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {

        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode)
    {

        $this->zipCode = $zipCode;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {

        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {

        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddressReal()
    {

        return $this->addressReal;
    }

    /**
     * @param mixed $addressReal
     */
    public function setAddressReal($addressReal)
    {

        $this->addressReal = $addressReal;
    }

    /**
     * @return mixed
     */
    public function getBirthdayReal()
    {

        return $this->birthdayReal;
    }

    /**
     * @param mixed $birthdayReal
     */
    public function setBirthdayReal($birthdayReal)
    {

        $this->birthdayReal = $birthdayReal;
    }

    /**
     * @return mixed
     */
    public function getSsn()
    {

        return $this->ssn;
    }

    /**
     * @param mixed $ssn
     */
    public function setSsn($ssn)
    {

        $this->ssn = $ssn;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {

        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {

        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatusProfile()
    {

        return $this->statusProfile;
    }

    /**
     * @param mixed $statusProfile
     */
    public function setStatusProfile($statusProfile)
    {

        $this->statusProfile = $statusProfile;
    }

    /**
     * @return mixed
     */
    public function getNextPerformance()
    {

        return $this->nextPerformance;
    }

    /**
     * @param mixed $nextPerformance
     */
    public function setNextPerformance($nextPerformance)
    {

        $this->nextPerformance = $nextPerformance;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {

        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {

        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getLastNotification()
    {

        return $this->lastNotification;
    }

    /**
     * @param mixed $lastNotification
     */
    public function setLastNotification($lastNotification)
    {

        $this->lastNotification = $lastNotification;
    }

    /**
     * @return mixed
     */
    public function getNotificationEmail()
    {

        return $this->notificationEmail;
    }

    /**
     * @param mixed $notificationEmail
     */
    public function setNotificationEmail($notificationEmail)
    {

        $this->notificationEmail = $notificationEmail;
    }

    /**
     * @return mixed
     */
    public function getTermsAgreed()
    {

        return $this->termsAgreed;
    }

    /**
     * @param mixed $termsAgreed
     */
    public function setTermsAgreed($termsAgreed)
    {

        $this->termsAgreed = $termsAgreed;
    }

    /**
     * @return mixed
     */
    public function getTermsSignature()
    {

        return $this->termsSignature;
    }

    /**
     * @param mixed $termsSignature
     */
    public function setTermsSignature($termsSignature)
    {

        $this->termsSignature = $termsSignature;
    }

    /**
     * @return mixed
     */
    public function getDisplayOrder()
    {

        return $this->displayOrder;
    }

    /**
     * @param mixed $displayOrder
     */
    public function setDisplayOrder($displayOrder)
    {

        $this->displayOrder = $displayOrder;
    }

    /**
     * @return mixed
     */
    public function getAutoApprove()
    {

        return $this->autoApprove;
    }

    /**
     * @param mixed $autoApprove
     */
    public function setAutoApprove($autoApprove)
    {

        $this->autoApprove = $autoApprove;
    }

    /**
     * @return mixed
     */
    public function getGuestbook()
    {

        return $this->guestbook;
    }

    /**
     * @param mixed $guestbook
     */
    public function setGuestbook($guestbook)
    {

        $this->guestbook = $guestbook;
    }

   /**
    * end model table
    */

    public function __toString()
    {
        return $this->getDisplayName();
    }

    /**
     * @param $video
     */
    public function addVideo($video)
    {
        if(!$this->videos->contains($video)) {
            $this->videos->add($video);
        }
    }

    /**
     * @param $video
     */
    public function removeVideo($video)
    {
        if($this->videos->contains($video)) {
            $this->videos->remove($video);
        }
    }

    /**
     * @return mixed
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param mixed $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * @return mixed
     */
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * @param mixed $sentMessages
     */
    public function setSentMessages($sentMessages)
    {
        $this->sentMessages = $sentMessages;
    }

    /**
     * @return mixed
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * @param mixed $receivedMessages
     */
    public function setReceivedMessages($receivedMessages)
    {
        $this->receivedMessages = $receivedMessages;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param mixed $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return mixed
     */
    public function getContributorsHistory()
    {
        return $this->contributorsHistory;
    }

    /**
     * @param mixed $contributorsHistory
     */
    public function setContributorsHistory($contributorsHistory)
    {
        $this->contributorsHistory = $contributorsHistory;
    }

    /**
     * @return mixed
     */
    public function getContributionsHistory()
    {
        return $this->contributionsHistory;
    }

    /**
     * @param mixed $contributionsHistory
     */
    public function setContributionsHistory($contributionsHistory)
    {
        $this->contributionsHistory = $contributionsHistory;
    }

    /**
     * @param  User $user
     * @return void
     */
    public function addHiddenModel(User $user)
    {
        if (!$this->myHiddenModels->contains($user)) {
            $this->myHiddenModels->add($user);
            $user->addHiddenModel($this);
        }
    }

    /**
     * @param  User $user
     * @return void
     */
    public function removeFriend(User $user)
    {
        if ($this->myHiddenModels->contains($user)) {
            $this->myHiddenModels->removeElement($user);
            $user->removeFriend($this);
        }
    }

    /**
     * @return mixed
     */
    public function getHiddenFor()
    {
        return $this->hiddenFor;
    }

    /**
     * @param mixed $hiddenFor
     */
    public function setHiddenFor($hiddenFor)
    {
        $this->hiddenFor = $hiddenFor;
    }

    /**
     * @return mixed
     */
    public function getMyHiddenModels()
    {
        return $this->myHiddenModels;
    }

    /**
     * @param mixed $myHiddenModels
     */
    public function setMyHiddenModels($myHiddenModels)
    {
        $this->myHiddenModels = $myHiddenModels;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubscribedUsers()
    {
        return $this->subscribedUsers;
    }

    /**
     * @param ArrayCollection $subscribedUsers
     */
    public function setSubscribedUsers($subscribedUsers)
    {
        $this->subscribedUsers = $subscribedUsers;
    }

    /**
     * @return ArrayCollection
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * @param ArrayCollection $newsletter
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;
    }


    /**
     * @return mixed
     */
    public function getResetCode()
    {
        return $this->resetCode;
    }

    /**
     * @param mixed $resetCode
     */
    public function setResetCode($resetCode)
    {
        $this->resetCode = $resetCode;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param mixed $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return mixed
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * @param mixed $lastActivity
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param mixed
     *
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }

    /**
     * @return mixed
     */
    public function getReferralCode()
    {
        return $this->referralCode;
    }

    /**
     * @param mixed $referralCode
     */
    public function setReferralCode($referralCode)
    {
        $this->referralCode = $referralCode;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return mixed
     */
    public function getJoined()
    {
        return $this->joined;
    }

    /**
     * @param mixed $joined
     */
    public function setJoined($joined)
    {
        $this->joined = $joined;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return !empty($this->avatar) ? $this->avatar : (
            $this->getGender() == self::GENDER_FEMALE
                ? '/assets/defaults/users/avatar-default-female.png'
                : '/assets/defaults/users/avatar-default-male.png'
            );
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return int
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param int $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param mixed $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param $settings
     */
    public function addSettings($settings)
    {
        $this->settings[] = $settings;
    }

    /**
     * @return mixed
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param mixed $friends
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
    }

    /**
     * @return mixed
     */
    public function getResourcesAccess()
    {
        return $this->resourceAccess;
    }

    public function setResourcesAccess($resourceAccess)
    {
        $this->resourceAccess = $resourceAccess;
    }

    public function addResourceAccess(UserAccess $resourceAccess)
    {
        $resourceAccess->setUser($this);
        $this->resourceAccess->add($resourceAccess);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param $broadcastType
     *
     * @return $this
     */
    public function setBroadcastType($broadcastType)
    {
        $this->broadcastType = $broadcastType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBroadcastType()
    {
        return $this->broadcastType;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $this->role = $role->getRoleId();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPurchasedContent()
    {
        return $this->purchasedContent;
    }

    /**
     * @param string $purchasedContent
     */
    public function setPurchasedContent($purchasedContent)
    {
        $this->purchasedContent = $purchasedContent;
    }

    /**
     * @ORM\PostLoad
     */
    public function getWebsite(LifecycleEventArgs $event)
    {

        $entityManager = $event->getEntityManager();
        $repository = $entityManager->getRepository(get_class($this));

        $domainSettings = $repository->getDomainSettings($this->getId());

        return $domainSettings;

    }

    /**
     * @return mixed
     */
    public function getVideoReference()
    {
        return $this->videoReference;
    }

    /**
     * @param $tags
     */
    public function setVideoReferencee($videoReference)
    {
        $this->videoReference = $videoReference;
    }

    /**
     * @return mixed
     */
    public function getWebchatHistory()
    {
        return $this->webchatHistory;
    }

    /**
     * @param  $webchatHistory
     */
    public function setWebchatHistory($webchatHistory)
    {
        $this->webchatHistory = $webchatHistory;
    }

    /**
     * @param \Application\Entity\WebchatSessions $session
     *
     * @return $this
     */
    public function addChatSession(WebchatSessions $session)
    {

        if (!$this->webchatSessions->contains($session)) {

            $session->setUser($this);
            $session->getNumberOfCameras($this->getNumberOfCameras());
            $session->setBroadcastType($this->getBroadcastType());
            $session->setBroadcastMode($this->getBroadcastMode());

            if (!$session->getSession()) {
                $session->setSession($this->getSession().date('His'));
            }

            $this->webchatSessions->add($session);

        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebsiteNewsletters()
    {
        return $this->websiteNewsletters;
    }

    /**
     * @param mixed $websiteNewsletters
     */
    public function setWebsiteNewsletters($websiteNewsletters)
    {
        $this->websiteNewsletters = $websiteNewsletters;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getStudios()
    {
        return $this->studios;
    }

    /**
     * @param mixed $studios
     */
    public function setStudios($studios)
    {
        $this->studios = $studios;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session_id;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session_id = $session;
    }

    /**
     * @return ArrayCollection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param ArrayCollection $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
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
     * @return string
     */
    public function getDisplayName()
    {
        return !empty($this->displayName) ? $this->displayName : $this->getUsername();
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getContributions()
    {
        return $this->contributions;
    }

    /**
     * @param string $contributions
     */
    public function setContributions($contributions)
    {
        $this->contributions = $contributions;
    }

    /**
     * @return mixed
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * @param mixed $managers
     */
    public function setManagers($managers)
    {
        $this->managers = $managers;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * @param mixed $followers
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
    }

    /**
     * @return mixed
     */
    public function getUserFollower()
    {
        return $this->userFollower;
    }

    /**
     * @param mixed $userFollower
     */
    public function setUserFollower($userFollower)
    {
        $this->userFollower = $userFollower;
    }

    /**
     * @return string
     */
    public function getPledges()
    {

        return $this->pledges;
    }

    /**
     * @param string $pledges
     */
    public function setPledges($pledges)
    {

        $this->pledges = $pledges;
    }

    /**
     * @return mixed
     */
    public function getAlbums()
    {

        return $this->albums;
    }

    /**
     * @param mixed $albums
     */
    public function setAlbums($albums)
    {

        $this->albums = $albums;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserSchedule()
    {
        return $this->userSchedule;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $userSchedule
     */
    public function setUserSchedule($userSchedule)
    {
        $this->userSchedule = $userSchedule;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModelNotes()
    {

        return $this->modelNotes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $modelNotes
     */
    public function setModelNotes($modelNotes)
    {

        $this->modelNotes = $modelNotes;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWebchatSessions($session = null)
    {

        if (!is_null($session)) {

            if (is_bool($session)) {
                $session = $this->getSession();
            }

            return $this->webchatSessions->filter(function($entry) use ($session) {
               return $entry->getSession() == $session;
            })->first();
        }

        return $this->webchatSessions;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $webchatSessions
     */
    public function setWebchatSessions($webchatSessions)
    {

        $this->webchatSessions = $webchatSessions;
    }

    /**
     * @return mixed
     */
    public function getPledgeFunder()
    {

        return $this->pledgeFunder;
    }

    /**
     * @param mixed $pledgeFunder
     */
    public function setPledgeFunder($pledgeFunder)
    {

        $this->pledgeFunder = $pledgeFunder;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return mixed
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }

    /**
     * @param mixed $extraFields
     */
    public function setExtraFields($extraFields)
    {
        $this->extraFields = $extraFields;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    public function addCategory(Categories $category)
    {
        $category->setUser($this);
        $this->categories->add($category);
    }

    public function addReview(Reviews $review)
    {
        $this->reviews->add($review);
        $review->setUser($this);
    }

    public function addUserSchedule(ModelSchedule $userSchedule)
    {
        $this->userSchedule->add($userSchedule);
        $userSchedule->setUser($this);
    }

    public function addBlogPost(BlogPosts $blogPost)
    {
        $this->blogPosts->add($blogPost);
        $blogPost->setUser($this);
    }

    public function addFriend(Friends $friend)
    {
        if (!$this->friends->contains($friend)) {
            $friend->setUser($this);
            $this->friends->add($friend);
        }
    }

    /**
     * @param \PerfectWeb\Core\Entity\ResourceValue $userResourceValue
     *
     * @return $this
     */
    public function addSetting(\PerfectWeb\Core\Entity\ResourceValue $userResourceValue)
    {

        if (!$this->settings->contains($userResourceValue)) {
            $userResourceValue->setUser($this);
            $this->settings->add($userResourceValue);
        }

        return $this;

    }

    public function addPhoto(Photo $photo)
    {
        $photo->setUser($this);
        $this->photos->add($photo);
    }

    public function addAlbum(Albums $album)
    {
        $album->setUser($this);
        $this->albums->add($album);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $blogPosts
     */
    public function setBlogPosts($blogPosts)
    {
        $this->blogPosts = $blogPosts;
    }

    public function addFollower(Followers $follower)
    {
        $follower->setFollowed($this);
        $this->followers->add($follower);
    }

    public function removeFollower(Followers $follower)
    {
        $this->followers->remove($follower);
    }

    function isFollowed(User $user)
    {
        //return $this->followers->filter([$user->getId());
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return float
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * @param float $credit
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $payments
     */
    public function setPayments($payments)
    {
        $this->payments = $payments;
    }

    /**
     * @return Website
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * @param Website $websites
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;
    }

    /**
     * @param \Application\Entity\Website $website
     */
    public function addWebsite(Website $website)
    {
        $website->setUser($this);
        $this->websites->add($website);
    }

    /**
     * @param Website $websites
     */
    public function removeWebsite(Website $website)
    {
        if($this->websites->contains($website)) {
            $this->websites->remove($website);
        }
    }

    /**
     * @return BadWords
     */
    public function getBadWords()
    {
        return $this->badWords;
    }

    /**
     * @param BadWords $badWords
     */
    public function setBadWords($badWords)
    {
        $this->badWords = $badWords;
    }

    /**
     * @param \Application\Entity\BadWords $badWord
     */
    public function addBadWord(BadWords $badWord)
    {
        $badWord->setUser($this);
        $this->badWords->add($badWord);
    }

    /**
     * @param \Application\Entity\BadWords $badWord
     */
    public function removeBadWord(BadWords $badWord)
    {
        if($this->badWords->contains($badWord)) {
            $this->badWords->remove($badWord);
        }
    }

    /**
     * @return CallLog
     */
    public function getCallLogs()
    {
        return $this->callLogs;
    }

    /**
     * @param $callLogs
     *
     * @return $this
     */
    public function setCallLogs($callLogs)
    {
        $this->callLogs = $callLogs;
        return $this;
    }

    /**
     * @param \Application\Entity\CallLog $callLog
     *
     * @return $this
     */
    public function addCallLog(CallLog $callLog)
    {
        if (!$this->callLogs->contains($callLog)) {
            $callLog->setUser($this);
            $this->callLogs->add($callLog);
        }
        return $this;
    }

    /**
     * @return Autoresponders
     */
    public function getAutoresponders()
    {
        return $this->autoresponders;
    }

    /**
     * @param Autoresponders $autoresponders
     */
    public function setAutoresponders($autoresponders)
    {
        $this->autoresponders = $autoresponders;
    }

    /**
     * @param \Application\Entity\Autoresponders $autoresponder
     *
     * @return $this
     */
    public function addAutoresponder(Autoresponders $autoresponder)
    {
        if (!$this->autoresponders->contains($autoresponder)) {
            $autoresponder->setUser($this);
            $this->autoresponders->add($autoresponder);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBroadcastMode()
    {
        return $this->broadcastMode;
    }

    /**
     * @param $broadcastMode
     *
     * @return $this
     */
    public function setBroadcastMode($broadcastMode)
    {
        $this->broadcastMode = $broadcastMode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumberOfCameras()
    {
        return $this->numberOfCameras;
    }

    /**
     * @param $numberOfCameras
     *
     * @return $this
     */
    public function setNumberOfCameras($numberOfCameras)
    {
        $this->numberOfCameras = $numberOfCameras;
        return $this;
    }

    /**
     * @return \Application\Entity\UserCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }


}