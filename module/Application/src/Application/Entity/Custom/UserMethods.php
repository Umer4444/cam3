<?php

namespace Application\Entity\Custom;

use Doctrine\Common\Collections\Criteria;
use ZfcUser\Entity\User as ZfcUser;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Application\Entity\Role;

abstract class UserMethods extends ZfcUser
{

    const LESS = 'less';
    const NORMAL = 'normal';
    const MORE = 'more';

    const RECORDER_STREAM = 100;

    const FOLDER_CHAT = 'chat';
    const FOLDER_BACKGROUNDS = 'backgrounds';
    const FOLDER_PROFILE = 'profile';
    const FOLDER_BLOGS = 'blogs';
    const FOLDER_SNAPSHOTS = 'snapshots';
    const FOLDER_VIDEOS = 'videos';
    const FOLDER_IMAGES = 'images';
    const FOLDER_PHOTOS = 'photos';

    protected $role = Role::GUEST;

    static $folders = [
        self::FOLDER_CHAT,
        self::FOLDER_BACKGROUNDS,
        self::FOLDER_PROFILE,
        self::FOLDER_BLOGS,
        self::FOLDER_SNAPSHOTS,
        self::FOLDER_VIDEOS,
        self::FOLDER_IMAGES,
        self::FOLDER_PHOTOS,
    ];

    /**
     * Use getter for values in extrafields
     *
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args)
    {

        if (substr($name, 0, 3) == "get" && strlen($name) > 3) {

            $filter = new CamelCaseToUnderscore();
            $fieldName = strtolower($filter->filter(substr($name, 3)));

            $setting = $this->getSettings()->filter(function($setting) use ($fieldName) {
                return $setting->getResource()->getName() === $fieldName;
            })->first();

            if ($setting) {

                if(count($args)) {
                    return $setting->{$args[0]}();
                }

                return $setting->getValue();

            }

        }

    }

    public function isPerformer()
    {

        foreach ($this->getRoles() as $role) {
            if ($role->getRoleId() == Role::PERFORMER) {
                return true;
            }
        }

        return false;

    }

    /**
     * @param $key
     * @param bool $returnEntity
     * @return mixed
     */
    public function getSetting($key, $returnEntity = false)
    {

        foreach ($this->getSettings() as $setting) {
            if ($setting->getResource()->getName() == $key) {
                return $returnEntity ? $setting : $setting->getValue();
            }
        }

        return false;
    }

    /**
     * @param int $camNumber
     * @param bool $hls
     *
     * @return string
     */
    function getStream($camNumber = 1, $hls = false)
    {
        return md5($this->getRole().$this->getId()) . $camNumber . (!$hls ? '' : '.m3u8');
    }

    /**
     * @return bool
     */
    function hasUserRole()
    {

        foreach ($this->getRoles() as $role) {
            if (in_array($role->getRoleId(), [Role::MEMBER, Role::USER, Role::VIP_USER])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    function hasAdminRole()
    {

        foreach ($this->getRoles() as $role) {
            if (in_array($role->getRoleId(), [Role::ADMIN, Role::SUPER_ADMIN])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    function hasModeratorRole()
    {

        foreach ($this->getRoles() as $role) {
            if (in_array($role->getRoleId(), [Role::MODERATOR, Role::SUPER_ADMIN, Role::STUDIO_MANAGER, Role::ACCOUNT_MANAGER, Role::ADMIN])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    function isVIP()
    {

        foreach ($this->getRoles() as $role) {
            if ($role->getRoleId() == Role::VIP_USER) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    function isBroadcasting()
    {
        return ($this->getBroadcastType() && $this->getBroadcastMode()) ? true : false;
    }

    /**
     * @return bool
     */
    function inPrivate()
    {
        // @todo implement this
        return false;
    }

    /**
     * @param $type
     * @param bool|false $absolute
     *
     * @return string
     * @throws \Exception
     */
    function getFolderPath($type, $absolute = false)
    {

        if (!in_array($type, self::$folders)) {
            throw new \Exception('The requested folder path does not belong to a user!');
        }

        return (!$absolute?'':getcwd().'/public').'/uploads/users/'.$this->getId().'/'.$type.'/';

    }

    /**
     * @return boolean
     */
    public function isOnline()
    {
        return !is_null($this->getSession());
    }

    public function getCurrentChatSession()
    {
        if (!$this->getWebchatSessions()->count()) {
            return false;
        }

        return $this->getWebchatSessions()->matching(Criteria::create()->orderBy(['startDate' => 'DESC']))->first();

    }

}