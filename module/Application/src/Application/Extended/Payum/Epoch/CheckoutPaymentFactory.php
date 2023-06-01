<?php
namespace Application\Extended\Payum\Epoch;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\PaymentFactory as CorePaymentFactory;
use Application\Extended\Payum\CCBill\Action\CaptureAction;
use Payum\Core\PaymentFactoryInterface;

class CheckoutPaymentFactory implements PaymentFactoryInterface
{

    /**
     * @var PaymentFactoryInterface
     */
    protected $corePaymentFactory;

    /**
     * @var array
     */
    private $defaultConfig;

    /**
     * @param array $defaultConfig
     * @param PaymentFactoryInterface $corePaymentFactory
     */
    public function __construct(array $defaultConfig = array(), PaymentFactoryInterface $corePaymentFactory = null)
    {
        $this->corePaymentFactory = $corePaymentFactory ?: new CorePaymentFactory();
        $this->defaultConfig = $defaultConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $config = array())
    {
        return $this->corePaymentFactory->create($this->createConfig($config));
    }

    /**
     * {@inheritDoc}
     */
    public function createConfig(array $config = array())
    {
        $config = ArrayObject::ensureArrayObject($config);
        $config->defaults($this->defaultConfig);
        $config->defaults($this->corePaymentFactory->createConfig((array) $config));

        $config->defaults(array(
            'payum.factory_name' => 'ccbill',
            'payum.factory_title' => 'CCBill',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.fill_order_details' => new \Payum\Stripe\Action\FillOrderDetailsAction(),
            'payum.action.status' => new \Payum\Stripe\Action\StatusAction(),
            /*'payum.action.obtain_token' => function (ArrayObject $config) {
                return new ObtainTokenAction($config['payum.template.obtain_token']);
            },*/
        ));

        /*if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'publishable_key' => '',
                'secret_key' => ''
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = array('publishable_key', 'secret_key');

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Keys($config['publishable_key'], $config['secret_key']);
            };
        }*/

        return (array) $config;
    }
}
