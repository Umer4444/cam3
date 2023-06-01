<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Zend\Filter\Word\CamelCaseToUnderscore;

/**
 * Class UserRepository
 * @package Application\Repository
 */
class UserRepository extends EntityRepository
{

    const DEFAULT_BIO_VIDEO = 'default_bio_video';
    const DEFAULT_INTRO_VIDEO = 'default_intro_video';

    protected $defaultAssets = array(
        'default_bio_video' => '/assets/videos/default_bio.flv',
    );

    /**
     * @return array
     */
    public function getDefaultAssets()
    {
        return $this->defaultAssets;
    }

    public function getAutoCompleteResults($searchString = null) {
        if (!$searchString) {
            return false;
        }

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder
            ->select(array('u.id', 'u.username'))
            ->andWhere("u.username LIKE :like")
            ->setParameters(
                array(
                    'like' => '%' . $searchString . '%'
                )
            )
            ->groupBy('u.id');

        return $queryBuilder->getQuery()->getResult();


    }

    /**
     * @param null $user
     * @return array
     */
    public function getProfileSettings($user = null)
    {

        if (!$user) return false;

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder
            ->select(array('u.id user', 's.value value', 's.id id', 'r.label label', 'r.context context'))
            ->join(\PerfectWeb\Core\Entity\UserAccess::class, 'a', 'WITH', 'a.user = u.id')
            ->join(\PerfectWeb\Core\Entity\Resource::class, 'r', 'WITH', 'r.id = a.resource')
            ->leftJoin(\PerfectWeb\Core\Entity\ResourceValue::class, 's', 'WITH', 's.user = u.id AND s.resource = r.id')
            ->where("u.id = :user")
            ->andWhere("r.group = :group")
            ->setParameters(
                array(
                    'user' => $user,
                    'group' => 'profile'
                )
            )
            ->groupBy('r.id');

        $result = $queryBuilder->getQuery()->getResult();
        $result = $this->hydrateProfile($user, $result);


        return $result;

    }

    /**
     * @param null $userId
     * @return array
     */
    public function getPerformerProfile($userId = null)
    {

        if (!$userId) return false;

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder
            ->select(array('u.id user', 's.value value', 's.id id', 'r.label label', 'r.context context'))
            ->join(\PerfectWeb\Core\Entity\UserAccess::class, 'a', 'WITH', 'a.user = u.id')
            ->join(\PerfectWeb\Core\Entity\Resource::class, 'r', 'WITH', 'r.id = a.resource')
            ->leftJoin(\PerfectWeb\Core\Entity\ResourceValue::class, 's', 'WITH', 's.user = u.id AND s.resource = r.id')
            ->where("u.id = :user")
            ->andWhere("r.group = :group")
            ->setParameters(
                array(
                    'user' => $userId,
                    'group' => 'performer_profile'
                )
            )
            ->groupBy('r.id');

        $results = $queryBuilder->getQuery()->getResult();


        $keyValue = $this->settingsToKeyValue($results);

        return $keyValue;

    }

    /**
     * * @deprecated - moved to user_listener
     * @param $settings
     * @return array key value
     */
    private function settingsToKeyValue($settings)
    {

        $res = array();
        foreach ($settings as $set) {
            if (isset($set['key'])) {
                $res[$set['key']] = $set;
            }
        }
        return $res;
    }

    /**
     * @deprecated - moved to user_listener
     * @param $userId
     * @param $newSettings
     * @return array
     */
    public function hydrateProfile($userId, $newSettings)
    {


        $newProfile = array();
        $map = array(
           // 'aboutMe' => "about_me",
            'displayName' => "display_name",
            'birthday' => "birthday",
            'turn_on' => "turned_on",
            'turn_off' => "turned_off",
            'rules' => "room_rules",
            'hobbies' => "interests_hobbies",
        );

        foreach ($newSettings as $new) {

            if (isset($new['key'])) {
                $newProfile[$new['key']] = $new;

                if (!is_null($new['entity']) && $new['entity'] == 'Video') {
                    $ent = $this->getEntityManager()
                        ->getRepository('Videos\Entity\\' . $new['entity'])
                        ->findOneById($new['value']);

                    if ($ent) {
                        $newProfile[$new['key']]['value'] = $ent;
                    } else {
                        $newProfile[$new['key']]['value'] = null;
                    }

                } else if (!is_null($new['entity']) && $new['entity'] == 'Country') {
                     $ent = $this->getEntityManager()
                    ->getRepository('Application\Entity\\'. $new['entity'])
                    ->findOneBy(array('code' => $new['value']));
                    if($ent) {
                        $newProfile[$new['key']]['value'] = $ent->getName();
                    } else {
                        $newProfile[$new['key']]['value'] = '';
                    }

                } else if (!is_null($new['entity']) && $new['entity'] == 'Address') {
                     $ent = $this->getEntityManager()
                    ->getRepository('Application\Entity\Address')
                    ->findOneBy(array('addressId' => $new['value']));

                    if($ent) {
                        $newProfile[$new['key']]['value'] = $ent;
                    } else {
                        $newProfile[$new['key']]['value'] = new \Application\Entity\Address();
                    }
                }

            }
        }

        if (!$userId) return false;
        //settings from old user table
        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->select(' u.displayName, u.birthday')
            ->where("u.id = :user")
            ->setParameters(
                array(
                    'user' => $userId,
                )
            );
        $currentUser = $queryBuilder->getQuery()->getScalarResult();
        $currentUser = $currentUser[0];

        //settings from model info, info for profile - old info
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->from('Application\Entity\Info', 'i')
            ->select(array('mi.value value', 'i.field field', 'mi.userId userId'))
            ->leftJoin('Application\Entity\ModelInfo', 'mi', 'WITH', 'mi.userId = :user AND i.id = mi.fieldId')
            ->where("mi.userId = :user")
            ->setParameters(
                array(
                    'user' => $userId,
                )
            );
        $oldSettingsTmp = $queryBuilder->getQuery()->getScalarResult();

        //add old settings to new profile
        foreach ($oldSettingsTmp as $sett) {
            $fieldName = (array_key_exists($sett['field'], $map) ? $map[$sett['field']] : $sett['field']);
            if (array_key_exists($fieldName, $newProfile)) {
                if (empty($newProfile[$fieldName]['value']) || strlen($newProfile[$fieldName]['value']) < 1) {

                    $newProfile[$fieldName] = array(
                        'user' => $sett['userId'],
                        'value' => $sett['value'],
                        'id' => '',
                        'key' => $sett['field'],
                        'label' => str_replace('_', ' ', ucwords($sett['field'])),
                        'entity' => '',
                    );
                }
            } else {
                $newProfile[$fieldName] = array(
                    'user' => $sett['userId'],
                    'value' => $sett['value'],
                    'id' => '',
                    'key' => $sett['field'],
                    'label' => str_replace('_', ' ', ucwords($sett['field'])),
                    'entity' => '',
                );
            }
        }

        //add settings from old user to new profile
        foreach ($currentUser as $k => $v) {

            if (array_key_exists($k, $map)) {
                if (array_key_exists($map[$k], $newProfile)) {
                    if (empty($newProfile[$map[$k]])) {
                        $newProfile[$map[$k]] = array(
                            'user' => $userId,
                            'value' => $v,
                            'id' => '',
                            'key' => $map[$k],
                            'label' => str_replace('_', ' ', ucwords($map[$k])),
                            'entity' => '',
                        );
                    }
                } else {
                    $newProfile[$map[$k]] = array(
                        'user' => $userId,
                        'value' => $v,
                        'id' => '',
                        'key' => $map[$k],
                        'label' => str_replace('_', ' ', ucwords($map[$k])),
                        'entity' => '',
                    );
                }
            }
        }

        return $newProfile;
    }

    /**
     * @param null $userId
     * @return array
     */
    public function getAccountSettings($userId = null)
    {

        if (!$userId) return false;

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder
            ->select(array('u.id user', 's.value value', 's.id id', 'r.label label', 'r.context context'))
            ->join(\PerfectWeb\Core\Entity\UserAccess::class, 'a', 'WITH', 'a.user = u.id')
            ->join(\PerfectWeb\Core\Entity\Resource::class, 'r', 'WITH', 'r.id = a.resource')
            ->leftJoin(\PerfectWeb\Core\Entity\ResourceValue::class, 's', 'WITH', 's.user = u.id AND s.resource = r.id')
            ->where("u.id = :user")
            ->andWhere("r.group = :group")
            ->setParameters(
                array(
                    'user' => $userId,
                    'group' => 'account'
                )
            )
            ->groupBy('r.id');

        $result = $queryBuilder->getQuery()->getResult();
        $result = $this->settingsToKeyValue($result);
        return $result;
    }

    /**
     * @param null $userId
     * @return array
     */
    public function getDomainSettings($userId = null)
    {
        if (!$userId) return false;

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder
            ->select(array('u.id user', 's.value value', 's.id id', 'r.label label', 'r.context context'))
            ->join(\PerfectWeb\Core\Entity\UserAccess::class, 'a', 'WITH', 'a.user = u.id')
            ->join(\PerfectWeb\Core\Entity\Resource::class, 'r', 'WITH', 'r.id = a.resource')
            ->leftJoin(\PerfectWeb\Core\Entity\ResourceValue::class, 's', 'WITH', 's.user = u.id AND s.resource = r.id')
            ->where("u.id = :user")
            ->andWhere("r.group = :group")
            ->setParameters(
                array(
                    'user' => $userId,
                    'group' => 'domain'
                )
            );

        $result = $queryBuilder->getQuery()->getResult();

        return $result;
    }

    /**
     * @param null $userId
     * @return array
     */
    public function getModelExtraFields($userId = null)
    {
        if (!$userId) return false;

        $query = $this->_em->createQuery('SELECT m FROM  Application\Entity\Model m WHERE m.user = :modelid');
        $query->setParameter('modelid', $userId);
        $modelRow = $query->getScalarResult();

        if(current($modelRow)) {
            return $this->hydrateKeys(current($modelRow));
        } else {
            return array();
        }

        //return $result;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function getUserBySettings($key, $value)
    {

        if (!$key) return false;

        $queryBuilder = $this->createQueryBuilder('u');
        $queryBuilder->select('u')
            ->join(\PerfectWeb\Core\Entity\Resource::class, 'r', 'WITH', 'r.name = :key')
            ->join(\PerfectWeb\Core\Entity\ResourceValue::class, 's', 'WITH',
                's.user = u.id AND r.id = s.resource AND s.value = :value')
            ->setParameters(
                array(
                    'key' => $key,
                    'value' => $value
                )
            );

        return current($queryBuilder->getQuery()->getResult());

    }

    /**
     * @param null $only_future
     * @param null $type
     * @param null $status
     */
    public function getModelSchedule($only_future = NULL, $type = NULL, $status = NULL)
    {

    }

    private function hydrateKeys(array $array){
        if(empty($array)) return array();
        $new = array();
        foreach($array as $k=>$v) {
            $new[substr_replace($k, "", 0, 2)] = $v;
        }

        return $new;
    }

    // made by Alin
    function __call($method, $arguments)
    {

        if (substr($method, 0, 3) == 'get') {
            return $this->createQueryBuilder('u')
                        ->innerJoin('u.roles', 'r', 'WITH', 'r.roleId = :role')
                        ->setMaxResults(50)
                        ->setParameter('role', strtolower(substr($method, 3, -1)))
                        ->getQuery()
                        ->getResult();
        }

        throw new \Exception('Method not defined !');
    }

}
