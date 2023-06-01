<?php

namespace PerfectWeb\Core\Traits;

trait Ensure
{

    use EntityManager;

    function ensureFolder($path)
    {

        $workingDir = getcwd().'/public/';

        // make sure the doc root does not occur multiple times
        exec('mkdir -p '.escapeshellarg($workingDir.str_replace($workingDir, '', $path)));

        return file_exists($path);

    }

    function ensureUser($user)
    {

        if ($user instanceof \Application\Entity\User) {
            return $user;
        }
        elseif (is_object($user) && method_exists($user, 'getUser') && $user->getUser() instanceof \Application\Entity\User) {
            $user = $user->getUser();
        }
        elseif (is_numeric($user)) {
            $user = $this->getEntityManager()->find(\Application\Entity\User::class, $user);
        }
        else {

            try {
                $user = $this->getEntityManager()
                             ->getRepository(\Application\Entity\User::class)
                             ->createQueryBuilder('r')
                             ->select('r')
                             ->where('r.username = :user')
                             ->orWhere('r.displayName = :user')
                             ->setParameter('user', $user)
                             ->getQuery()
                             ->getSingleResult();
            }
            catch (\Exception $e) {}
        }

        if (!($user instanceof \Application\Entity\User)) {
            throw new \Exception('The user could not be determined from the provided arguments !');
        }

        return $user;

    }

    function ensureObject($object, $identity = null)
    {

        if (is_object($object)) {
            return $object;
        }
        elseif ($object && is_numeric($identity)) {
            $object = $this->getEntityManager()->find($object, $identity);
        }
        elseif ($object) {
            list($object, $identity) = explode('::', $object);
            $object = $this->getEntityManager()->find($object, $identity);
        }

        if (!$object) {
            throw new \Exception('The object could not be determined from the provided arguments !');
        }

        return $object;

    }

}