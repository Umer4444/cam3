<?php
namespace Application\Extended\Payum;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Payum\Core\GatewayFactoryInterface;

class CheckoutPaymentFactory extends GatewayFactory implements GatewayFactoryInterface
{

    /**
     * @var GatewayFactoryInterface
     */
    protected $corePaymentFactory;

    /**
     * @var array
     */
    private $defaultConfig;

    /**
     * @param array $defaultConfig
     * @param GatewayFactoryInterface $corePaymentFactory
     */
    public function __construct(array $defaultConfig = array(), GatewayFactoryInterface $corePaymentFactory = null)
    {
        $this->corePaymentFactory = $corePaymentFactory ?: new Gateway();
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
            'payum.factory_name' => 'zombaio',
            'payum.factory_title' => 'Zombaio',
            //'payum.action.fill_order_details' => new \Payum\Stripe\Action\FillOrderDetailsAction(),
        ));

        return (array) $config;

    }
}
