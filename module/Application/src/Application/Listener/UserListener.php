<?php

namespace Application\Listener;

use Application\Entity\Newsletter;
use Application\Entity\Role;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use PerfectWeb\Core\Entity\ResourceValue;
use PerfectWeb\Core\Entity\Resource;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use PerfectWeb\Core\Traits;
use Zend\EventManager\Event;
use Application\Entity\User;

class UserListener implements EventSubscriber, ServiceLocatorAwareInterface
{

    use Traits\User;
    use Traits\Ensure;

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::postLoad,
            Events::postPersist,
        );
    }

    public function postLoad(User $user, LifecycleEventArgs $eventArgs)
    {

        if (!($user instanceof User)) {
            return;
        }
        $this->setUser($user);

        // set first role from the stack as reference role
        if($user->getRoles()) {
            $user->setRole($user->getRoles()[0]->getRoleId());
        }

    }

    public function prePersist(User $user, LifecycleEventArgs $eventArgs)
    {

        if ($user->isPerformer()) {
            $setting = new ResourceValue();
            $setting->setValue(
                '[{"id":"bio","file":"","x":0,"y":0,"width":12,"height":2,"locked":true},{"id":"what_turns_me_on","file":"","x":0,"y":2,"width":6,"height":2,"locked":true},{"id":"what_turns_me_off","file":"","x":6,"y":2,"width":6,"height":2,"locked":true},{"id":"things_i_do_in_private","file":"","x":0,"y":4,"width":6,"height":2,"locked":true},{"id":"things_i_dont_in_private","file":"","x":6,"y":4,"width":6,"height":2,"locked":true},{"id":"interests_hobbies","file":"","x":0,"y":6,"width":12,"height":2,"locked":true},{"id":"accomplishments_and_achievements","file":"","x":0,"y":8,"width":12,"height":2,"locked":true},{"id":"upcoming_appearances","file":"","x":0,"y":10,"width":6,"height":2,"locked":true},{"id":"room_rules","file":"","x":6,"y":10,"width":6,"height":2,"locked":true}]'
            );
            $setting->setResource(
                $eventArgs->getObjectManager()
                          ->getRepository(Resource::class)
                          ->findOneBy(['name' => 'profile_blocks', 'group' => 'profile_settings'])
            );
            $user->addSetting($setting);
        }
    }

    public function postPersist(User $user, LifecycleEventArgs $eventArgs)
    {

        foreach (\Application\Entity\Custom\UserMethods::$folders as $folder) {
            $this->ensureFolder('uploads/users/'. $user->getId().'/'.$folder);
        }

        $this->getServiceLocator()
             ->get('zfcuser_user_service')
             ->getEventManager()
             ->attach('register.post', [$this, 'magentoRegister']);

        /** @var \AcMailer\Service\MailService $mailer */
        $mailer = $this->getServiceLocator()->get('mail');
        $mailer->getMessage()->setTo($user->getEmail());
        $newsletterRepo = $this->getEntityManager()->getRepository(Newsletter::class);

        switch ($user->getRole()) {

            case Role::PERFORMER:
                $tag = Newsletter::PERFORMER_REGISTRATION;
            break;

            case Role::STUDIO_MANAGER:
                $tag = Newsletter::STUDIO_REGISTRATION;
            break;

            default:
                $tag = Newsletter::MEMBER_REGISTRATION;
            break;

        }

        $newsletter = $newsletterRepo->findOneBy(['tag' => $tag], ['default' => 'ASC']);

        $mailer->getMessage()->setBody($newsletter->getContent())->setSubject($newsletter->getSubject());

        if (!defined('FIXTURES')) {
            $mailer->send();
        }

    }

    public function magentoRegister(Event $e)
    {

        $user = $e->getParam('user');

        // create magento users
        system('php magento/bin/magento camclients:register'.
               ' --username='.escapeshellarg($user->getUsername()).
               ' --password='.escapeshellarg($e->getParam('form')->get('password')->getValue()).
               ' --email='.escapeshellarg($user->getEmail()).
               ' --fname='.escapeshellarg($user->getFirstName() ?: 'n/a').
               ' --lname='.escapeshellarg($user->getLastName() ?: 'n/a')
        );

    }

}