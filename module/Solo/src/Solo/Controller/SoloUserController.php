<?php

// @deprecated to be removed
namespace Solo\Controller;

use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use ZfcUser\Controller\UserController as BaseUserController;

//@todo check if we need this, otherwise remove it
/**
 * Class SoloUserController
 * @package Solo\Controller
 *
 * Extends BaseUserController, and implements custom methods for login and register. change email and password.
 */
class SoloUserController extends BaseUserController
{

    /**
     * @constant route for password
     */
    const ROUTE_CHANGEPASSWD = 'zfcuser/changepassword';

    /**
     * @constant route for email
     */
    const ROUTE_CHANGEEMAIL = 'zfcuser/changeemail';

    /**
     * @var $entityManager ->gets the entity manager
     */
    protected $entityManager;

    /**
     * @return array|\Zend\Http\Response|ViewModel
     *
     */
    public function indexAction()
    {

        $userRepo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $user->setServiceLocator($this->getServiceLocator());

        $accountSettings = $userRepo->getAccountSettings($user->getId());

        $settingEntities = array();

        foreach ($accountSettings as $k => $userSetting) {
            if (!empty($userSetting['entity'])) {
                $repo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\\' . $userSetting["entity"]);
                if ($userSetting['value'])
                    $settingEntities[$userSetting['key']] = $repo->find($userSetting['value']);
            }
            $accountSettings[$userSetting['key']] = $userSetting;
            unset($accountSettings[$k]);
        }

        return new ViewModel(array(
            'user' => $user,
            'userSettings' => $accountSettings,
            'settingEntities' => $settingEntities,
        ));

    }

    /**
     * @return ViewModel
     */
    public function editAction() {

        $userRepo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')
        ->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $userSettings = $userRepo->getAccountSettings($user->getId());
        $form = new \UserAccount\Form\AccountSettings($userSettings, $user);
        $form->setAttribute('action', $this->url()->fromRoute('user-account/edit-process', array()));
        return new ViewModel(array(
            'form' => $form,
        ));
    }

    /**
     * @return ViewModel
     */
    public function profileAction()
    {

        $userRepo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $user->setServiceLocator($this->getServiceLocator());

        $accountSettings = $userRepo->getAccountSettings($user->getId());

        $settingEntities = array();

        foreach ($accountSettings as $k => $userSetting) {
            if (!empty($userSetting['entity'])) {
                $repo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')
                ->getRepository('Application\Entity\\' . $userSetting["entity"]);
                if ($userSetting['value'])
                    $settingEntities[$userSetting['key']] = $repo->find($userSetting['value']);
            }
            $accountSettings[$userSetting['key']] = $userSetting;
            unset($accountSettings[$k]);
        }

        return new ViewModel(array(
            'user' => $user,
            'userSettings' => $accountSettings,
            'settingEntities' => $settingEntities,
        ));
    }

    /**
     * @return ViewModel
     */
    public function editProfileAction()
    {

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $userRepo = $em
        ->getRepository('Application\Entity\User');
        $user = $this->zfcUserAuthentication()->getIdentity();

        $profileSettings = $userRepo->getProfileSettings($user->getId());

        if (!$profileSettings) {
            $profileSettings = array();
        }

        $form = new \UserProfile\Form\ProfileSettings($profileSettings, $em);

        $form->setAttribute('action', $this->url()->fromRoute('user-account/edit-profile-process', array()));

        return new ViewModel(array(
            'form' => $form,
        ));

    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function changepasswordAction()
    {

        // if the user isn't logged in, we can't change password
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangePasswordForm();
        $prg = $this->prg(static::ROUTE_CHANGEPASSWD);

        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changePasswordForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        if (!$this->getUserService()->changePassword($form->getData())) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEPASSWD);
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function changeEmailAction()
    {
        // if the user isn't logged in, we can't change email
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangeEmailForm();
        $request = $this->getRequest();
        $request->getPost()->set('identity', $this->getUserService()->getAuthService()->getIdentity()->getEmail());

        $fm = $this->flashMessenger()->setNamespace('change-email')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        $prg = $this->prg(static::ROUTE_CHANGEEMAIL);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changeEmailForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $change = $this->getUserService()->changeEmail($prg);

        if (!$change) {
            $this->flashMessenger()->setNamespace('change-email')->addMessage(false);
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-email')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEEMAIL);
    }

    /**
     * Login form
     */
    public function loginAction()
    {

        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {

            return array(
                'loginForm' => $form,
                'redirect' => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . $redirect : ''));
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(
           'zfcuser',
            array(
                'action' => 'authenticate',
                'type' => $this->params()->fromRoute('type')
            )
        );
    }

    /**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {

        if ($this->zfcUserAuthentication()->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        $params = $this->params()->fromRoute();
        if (isset($params['type']))
            $userType = $this->params()->fromRoute('type');
        else
            $userType = 'member';

        if (!$auth->isValid()) {

            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl($this->url()->fromRoute('accounts/login', array('type' => $userType))
                . ($redirect ? '?redirect=' . $redirect : ''));
        } else {
            //check if user has required role
            //$userIdentity = $this->getServiceLocator()->get('BjyAuthorize\Provider\Identity\ZfcUserZendDb');
            //$userRoles = $userIdentity->getIdentityRoles();`
            //here we build session for zf1
            $userId = $auth->getIdentity();
            $userRepo = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Application\Entity\User')
                ->findOneBy(array('id' => $auth->getIdentity()));

            $birthday = '';
            if ($userRepo->getBirthday()) {
                $birthday = $userRepo->getBirthday()->format('Y-m-d H:i:s');
            }

            $lastlogin = '';
            if ($userRepo->getLastLogin()) {
                $lastlogin = $userRepo->getLastLogin()->format('Y-m-d H:i:s');
            }

            if (!$userRepo->getUsername()) $username = $userRepo->getEmail();
            else $username = $userRepo->getUsername();

            if (!$userRepo->getDisplayname()) $displayName = $userRepo->getEmail();
            else $displayName = $userRepo->getDisplayname();

            if ($userRepo->getRoles()[0]->getRoleId() == 'member') $role = 'user';
            else if ($userRepo->getRoles()[0]->getRoleId() == 'performer') $role = 'model';
            else $role = $userRepo->getRoles()[0]->getRoleId();

            $_SESSION['group'] = $role;
            $_SESSION['user'] = array(
                'user_id' => $userId,
                'id' => $userId,
                'username' => $username,
                'password' => $userRepo->getPassword(),
                'email' => $userRepo->getEmail(),
                'display_name' => $displayName,
                'state' => $userRepo->getState(),
                'birthday' => $birthday,
                'country' => $userRepo->getCountry(),
                'avatar' => $userRepo->getAvatar(),
                'about_me' => $userRepo->getAboutMe(),
                'joined' => $userRepo->getJoined(),
                'last_login' => $lastlogin,
                'active' => $userRepo->getActive(),
                'chips' => $userRepo->getChips(),
                'refferal_code' => $userRepo->getReferralCode(),
                'activation_code' => $userRepo->getActivationCode(),
                'reset_code' => $userRepo->getResetCode(),
                'timezone' => $userRepo->getTimezone(),
                'last_activity' => $userRepo->getLastActivity(),
                'online' => $userRepo->getOnline(),
                'session_id' => session_id(),
                'phone' => $userRepo->getPhone(),
                'user_photo' => '',
                'profile_url' => '',
            );

            //here we build session for zf1
            $allRoles = $this->getServiceLocator()->get('BjyAuthorize\Provider\Role\ZendDb')->getRoles();

            $found = false;
            foreach ($allRoles as $currentRole) {

                if ($currentRole->getRoleId() == $userType) {
                    $found = true;
                    break;
                } else {
                    if ($currentRole->getParent()) {
                        if ($currentRole->getParent()->getRoleId() == $userType) {

                            $found = true;
                            break;
                        } else {
                            if ($currentRole->getParent()->getParent()) {

                                if ($currentRole->getParent()->getParent()->getRoleId() == $userType) {

                                    $found = true;
                                    break;
                                }
                            }
                        }
                    }
                }

            }

            if (!$found) {
                $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
                $adapter->resetAdapters();

                // clear identity
                $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

                return $this->redirect()->toUrl(
                    $this->url()->fromRoute(
                        'accounts/login',
                        array('type' => $userType)
                    )
                    . ($redirect ? '?redirect=' . $redirect : ''));
            }
        }

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }

        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());

    }

}