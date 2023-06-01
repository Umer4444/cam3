<?php

namespace Application\Controller;

use Application\Entity\Role;
use Zend\Stdlib\Parameters;
use ZfcUser\Controller\UserController as BaseUserController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Http\Client;
use Zend\Validator\Uri as UriValidator;

/**
 * Class UserController
 * @package Application\Controller
 */
class UserController extends BaseUserController
{

    /**
     * General-purpose authentication action
     */
    public function authenticateAction()
    {

        if ($this->zfcUserAuthentication()->hasIdentity()) {
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

        if (!$auth->isValid()) {

            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            return $this->redirect()->toUrl(
                $this->url()->fromRoute(static::ROUTE_LOGIN) .
                ($redirect ? '?redirect=' . rawurlencode($redirect) : '')
            );

        }
        else {

            $user = $this->zfcUserAuthentication()->getIdentity();

            $user->setSessionId(session_id());
            $user->setLastActivity(new \DateTime());
            $user->setLastLogin(new \DateTime());

            $em = $this->getServiceLocator()->get('em');
            $em->flush();
            $settings = $em->getRepository('Application\Entity\User')->getProfileSettings($user->getId());

            $_SESSION['group'] = $user->getRole();
            $_SESSION['moxiemanager.filesystem.rootpath'] = 'Home='.getcwd()."/public/uploads/users/". (int)$user->getId();
            $_SESSION['user'] = array( // session settings fore moximanager, make sure the key `user` is set
                'user_id' => $user->getId(),
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
                'email' => $user->getEmail(),
                'display_name' => $user->getDisplayName(),
                'screen_name' => $user->getDisplayName(),
                'state' => $user->getState(),
                'birthday' => $user->getBirthday() ? $user->getBirthday()->format('Y-m-d H:i:s') : '',
                'country' => $user->getCountry(),
                'avatar' => $user->getAvatar(),
                'about_me' => $user->getAboutMe(),
                'joined' => $user->getJoined(),
                'last_login' => $user->getLastLogin() ? $user->getLastLogin()->format('Y-m-d H:i:s') : '',
                'active' => $user->getActive(),
                'chips' => $user->getCredit(),
                'refferal_code' => $user->getReferralCode(),
                'activation_code' => $user->getActivationCode(),
                'reset_code' => $user->getResetCode(),
                'timezone' => $user->getTimezone(),
                'last_activity' => $user->getLastActivity(),
                'online' => $user->getOnline(),
                'session_id' => session_id(),
                'phone' => $user->getPhone(),
                'user_photo' => '',
                'profile_url' => '',
                'chat_font' => (isset($settings['chat_font']) ? $settings['chat_font']['value'] : '')
            );

            if ($user->getRole() == Role::PERFORMER) {

                // login to magento
                $client = new Client(
                    $this->url()
                         ->fromRoute(
                             'zfcadmin/store/magento', ['uri' => 'admin', 'proxy' => '~'], ['force_canonical' => true]
                         ),
                    ['adapter' => \Zend\Http\Client\Adapter\Curl::class, 'strictredirects' => true]
                );

                $client->setMethod('POST');
                $client->setEncType(Client::ENC_FORMDATA);
                $client->setCookies($_COOKIE);
                $client->setParameterPost(
                    [
                        'login[username]' => $this->params()->fromPost('identity'),
                        'login[password]' => $this->params()->fromPost('credential')
                    ]
                );

                try {

                    $response = $client->send();

                    if ($response instanceof \Traversable) {
                        foreach ($response->getCookie() as $cookie) {
                            $this->getResponse()->getHeaders()->addHeader($cookie);
                        }
                    }

                }
                catch (\Exception $e) {}

            }

        }

        $validator = new UriValidator();

        if ($validator->isValid($redirect)) {
            return $this->redirect()->toUrl(rawurldecode($redirect));
        }

        $redirect = $this->redirectCallback;
        return $redirect();

    }

    /**
     * @return mixed|\Zend\Http\Response
     */
    public function registerAction()
    {
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }

        $params = $this->params()->fromRoute();

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();
        $form->setData($params);

        $form->setAttribute('action', $this->url()->fromRoute(static::ROUTE_REGISTER, array('type' => $params['type'])));

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = false;
        }

        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER, array('type' => $params['type']))
            . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');

        $prg = $this->prg($redirectUrl, true);

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        $post = $prg;
        $user = $service->register($post);

        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
            );
        }

        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $post['credential'] = $post['password'];
            $request->setPost(new Parameters($post));
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        //use the redirect parameter here...
        return $this->redirect()->toUrl(
            $this->url()->fromRoute(static::ROUTE_LOGIN) .
            ($redirect ? '?redirect=' . rawurlencode($redirect) : '')
        );
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {

        if ($this->zfcUserAuthentication()->hasIdentity()) {

            $user = $this->zfcUserAuthentication()->getIdentity();

            $user->setSessionId('-');
            $user->setOnline(0);
            $this->getServiceLocator()->get('em')->flush();

            $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
            $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
            $this->zfcUserAuthentication()->getAuthService()->clearIdentity();
            unset($_SESSION);

        }

        $redirect = $this->redirectCallback;

        return $redirect();
    }

}