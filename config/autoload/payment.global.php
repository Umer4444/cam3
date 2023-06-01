<?php
return array(
    'payment' => array(
        'zombaio' => array(
            'site_id' => '287656154',
            'pricing_id' => '1800600',
            'zombaio_gw' => '49CN3I3166BVW2YLN196',
        ),
        'paxum' => array(
            'business_email' => 'paxum@camfriendnetwork.com',
            'currency' => 'USD',
            'button_type_id' => 1,
            'change_quantities' => 2,
        ),
        'stripe' => array(
            'publishable_key' => 'pk_test_MmNbWNMdQMy1GCwr2uHXqcZA',
            'secret_key' => 'sk_test_kOiQFAzlDRO9rBAqgyMooxyf',
        ),
        'epoch' => [
            'publishable_key' => '4Mt5e7S59QC',
            'secret_key' => '9j6k3FnAhpZY969B',
            'sandbox' => true,
        ],
        'authorize.net' => [
            'login_id' => '4Mt5e7S59QC',
            'transaction_key' => '9j6k3FnAhpZY969B',
            'sandbox' => true,
        ],
    ),
    'payum' => array(
        'token_storage' => Application\Entity\PaymentToken::class,
        'gateways' => array(
            'zombaio' => 'Application\Payment\Zombaio',
            //'paxum' => 'Application\Payment\Paxum',
            //'stripe' => 'Payum\Stripe',
            'authorize.net' => 'Application\Payment\AuthorizeNet',
            'epoch' => 'Application\Payment\Epoch',
//            '2checkout' => (new \Payum\OmnipayBridge\OmnipayDirectGatewayFactory)->create(array(
//                'type' => 'TwoCheckout',
//                'options' => array('accountNumber' => '', 'secretWord' => '', 'testMode' => true),
//            ))
        ),
        'storages' => array(
            'Application\Entity\Payment' => 'Application\Entity\Payment'
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Application\Entity\Payment' => function($sm) {
                return new \Payum\Core\Bridge\Doctrine\Storage\DoctrineStorage($sm->get('em'), Application\Entity\Payment::class);
            },
            'Application\Entity\PaymentToken' => function($sm) {
                return new \Payum\Core\Bridge\Doctrine\Storage\DoctrineStorage($sm->get('em'), Application\Entity\PaymentToken::class);
            },
            'Application\Payment\Zombaio' => function($sm) {
                $config = $sm->get('Config')['payment']['zombaio'];
                $config['payum.action.capture'] =
                    (new \Application\Extended\Payum\Zombaio\Action\CaptureAction())->setServiceLocator($sm);
                return (new \Payum\Core\GatewayFactory())->create($config);
            },
            'Application\Payment\AuthorizeNet' => function($sm) {
                return (new \Payum\AuthorizeNet\Aim\AuthorizeNetAimGatewayFactory)->create(
                    $sm->get('Config')['payment']['authorize.net']
                );
            },
            'Application\Payment\Epoch' => function($sm) {

                $config = $sm->get('Config')['payment']['epoch'];
                //$config['payum.action.status'] = new Application\Extended\Payum\Epoch\Action\StatusAction();
                $config['payum.action.capture'] =
                    (new \Application\Extended\Payum\Epoch\Action\CaptureAction())->setServiceLocator($sm);
                //$config['payum.action.create_charge'] =
                //    new Application\Extended\Payum\Zombaio\Action\Api\CreateChargeAction();

                return (new \Payum\Core\GatewayFactory())->create($config);

            },
            'Application\Payment\Paxum' => function($sm) {
                return Omnipay\Omnipay::create('Paxum')->initialize($sm->get('Config')['payment']['paxum']);;
            },
            'Payum\Stripe' => function($sm) {
                $stripe = new \Payum\Stripe\StripeJsGatewayFactory();
                $config = $stripe->createConfig($sm->get('Config')['payment']['stripe']);
                //$config['payum.paths']['PaymentForm'] = getcwd().'/module/Application/view/partials';
                return $stripe->create($config);
            }
        )
    ),
 );