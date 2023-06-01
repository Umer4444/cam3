<?php
namespace UserAccount\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use UserAccount\Form\AccountSettings as AccountSettings;
use UserAccount\Validator\AccountSettingsValidator as AccountSettingsValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class UserAccountController
 * @package UserAccount\Controller
 */
class UserAccountController extends AbstractActionController
{

    protected $entityManager;

    /**
     * @return array|ViewModel
     */
    public function indexAction()
    {

        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();

        $user->setServiceLocator($this->getServiceLocator());

        $accountSettings = $userRepo->getAccountSettings($user);

        $settingEntities = array();

        if ($accountSettings) {
            foreach ($accountSettings as $k => $userSetting) {
                if (!empty($userSetting['entity'])) {
                    $repo = $this->getEntityManager()->getRepository('Application\Entity\\' . $userSetting["entity"]);
                    if ($userSetting['value'])
                        $settingEntities[$userSetting['key']] = $repo->find($userSetting['value']);
                }
                $accountSettings[$userSetting['key']] = $userSetting;
                unset($accountSettings[$k]);
            }
        } else {
            $accountSettings = array();
        }

        return new ViewModel(array(
            'user' => $user,
            'userSettings' => $accountSettings,
            'settingEntities' => $settingEntities,
        ));

    }

    /**
     * @return array|object
     */
    private function getEntityManager()
    {
        if (!$this->entityManager)
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $this->entityManager;
    }

    /**
     * action to build and display form
     *
     */
    public function editAccountAction()
    {

        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $userSettings = $userRepo->getAccountSettings($user->getId());

        //make sure settings are sent even if empty array
        if (!$userSettings) {
            $userSettings = array();
        }

        $form = new AccountSettings($userSettings, $user);
        $form->setAttribute('action', $this->url()->fromRoute('user-account/edit-process', array()));

        return new ViewModel(array(
            'form' => $form,
        ));
    }

    /**
     * action to process form data
     *
     */
    public function editAccountProcessAction()
    {

        return $this->process();

    }

    /**
     * @return JsonModel
     */
    public function editProfileProcessAction()
    {

        return $this->processProfile();

    }

    /**
     * @return JsonModel
     */
    public function process()
    {

        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $profileSettings = $userRepo->getAccountSettings($user->getId());

        $form = new AccountSettings($profileSettings, $user);

        //$user = new User();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager()))
            ->setObject($user);

        $form->bind($user);

        if ($this->getRequest()->isPost()) {
            $formValidator = new AccountSettingsValidator();

            $form->setInputFilter($formValidator->getInputFilter());
            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {

                $user = $form->getData();
                foreach ($user->getSettings() as $profileSettingsEntity) {

                    $profileSettingsEntity->setUser($user);

                    if ($form->has($profileSettingsEntity->getKeyReference()->getKey()))
                        $profileSettingsEntity->setValue($form->get($profileSettingsEntity->getKeyReference()->getKey())->getValue());

                }

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $this->getEntityManager()->refresh($user);

                $response = array(
                    'status' => 'success',
                    'message' => "Form valid",
                );

            } else {
                $response = array(
                    'status' => 'fail',
                    'message' => "Form invalid",
                    'errors' => $form->getMessages(),
                );
            }

        } else {

            $response = array(
                'status' => 'fail',
                'message' => "No post request"
            );
        }

        return new JsonModel($response);

    }

    /**
     * @return JsonModel
     */
    public function processProfile()
    {

        $em = $this->getEntityManager();
        $userRepo = $this->getEntityManager()->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $profileSettings = $userRepo->getProfileSettings($user->getId());

        $form = new \UserProfile\Form\ProfileSettings($profileSettings, $em);

        //$user = new User();
        $form->setHydrator(new DoctrineHydrator($this->getEntityManager()))
            ->setObject($user);

        $form->bind($user);

        $countryRepo = $em
            ->getRepository('Application\Entity\Country');
        $countries = $countryRepo->findAll();


        $countriesArray = array();

        foreach ($countries as $c) {
            $countriesArray[$c->getCountryCode()] = $c->getCountryCode();
        }

        $form->get('country')->setAttribute('value_options', $countriesArray);


        if ($this->getRequest()->isPost()) {

            $formValidator = new \UserProfile\Validator\ProfileSettingsValidator();

            $form->setInputFilter($formValidator->getInputFilter());
            $form->setData($this->getRequest()->getPost());


            if ($form->isValid() && in_array($this->getRequest()->getPost()->country, $countriesArray)) {

                $user = $form->getData();
                foreach ($user->getSettings() as $profileSettingsEntity) {

                    $profileSettingsEntity->setUser($user);

                    if ($form->has($profileSettingsEntity->getKeyReference()->getKey()))
                        $profileSettingsEntity->setValue($form->get($profileSettingsEntity->getKeyReference()->getKey())
                            ->getValue());

                }
                $user->setUsername($this->getRequest()->getPost()->nickname);

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                $this->getEntityManager()->refresh($user);

                $response = array(
                    'status' => 'success',
                    'message' => "Form valid",
                );

            } else {
                $response = array(
                    'status' => 'fail',
                    'message' => "Form invalid",
                    'errors' => $form->getMessages(),
                );
            }

        } else {

            $response = array(
                'status' => 'fail',
                'message' => "No post request"
            );
        }

        return new JsonModel($response);

    }

}

