<?php

return array(

    'service_manager' => array(

        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'moderatorMenu' => 'Application\Navigation\Service\ModeratorNavigationFactory',

            'zfcuser_register_form' => function ($sm) {
                $options = $sm->get('zfcuser_module_options');
                $form = new \ZfcUser\Form\Register(null, $options);
                //$form->setCaptchaElement($sm->get('zfcuser_captcha_element'));
                $form->add(array(
                    'name' => 'type',
                    'type'  => \Zend\Form\Element\Select::class,
                    'options' => array(
                        'label' => 'Type',
                        'value_options' => [
                            'user' => 'user',
                            'performer' => 'performer',
                            'studio' => 'studio',
                        ]
                    ),
                ));
                $form->setInputFilter(new \ZfcUser\Form\RegisterFilter(
                    new \ZfcUser\Validator\NoRecordExists(array(
                        'mapper' => $sm->get('zfcuser_user_mapper'),
                        'key'    => 'email'
                    )),
                    new \ZfcUser\Validator\NoRecordExists(array(
                        'mapper' => $sm->get('zfcuser_user_mapper'),
                        'key'    => 'username'
                    )),
                    $options
                ));
                return $form;
            },

        ),

        'invokables' => array(
            'notifications' => 'Application\Service\Notifications',
            'twilio' => 'Application\Service\Twilio',
            'messages' => 'Application\Service\Messages',
            'wallet' => 'Application\Service\Wallet',

            'Application\Widgets\TimeLine\TimeLineAggregator' => 'Application\Widgets\TimeLine\TimeLineAggregator',
            'Application\Service\ReviewService' => 'Application\Service\ReviewService',
            'Application\Service\ReviewFormService' => 'Application\Service\ReviewFormService',

        ),

        'initializers' => array(
            function ($instance, $sm) {
                if ($instance instanceof Zend\Db\Adapter\AdapterAwareInterface) {
                    return $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                }
            }
        ),

        'aliases' => array(
            'zfcuser_doctrine_em' => 'Doctrine\ORM\EntityManager'
        ),

    ),

);
