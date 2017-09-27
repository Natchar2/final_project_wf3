<?php
namespace SKoziel\Silex\PayPalRest;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class PayPalServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['paypal'] = function () use ($app) {
            return new PayPal($app['paypal.settings']);
        };
    }

    public function boot(Container $app)
    {
    }
}