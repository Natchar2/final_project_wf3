<?php

namespace SKoziel\Silex\PayPalRest;

use PayPal\Rest\ApiContext;
use SKoziel\Silex\PayPalRest\Payments\CreditCardPayment;
use SKoziel\Silex\PayPalRest\Payments\ExpressCheckout;

trait PayPalTrait
{

    /**
     * @return ExpressCheckout
     */
    public function createExpressCheckout()
    {
        return $this['paypal']->createExpressCheckout();
    }

    /**
     * @return CreditCardPayment
     */
    public function createCreditCreditCardPayment()
    {
        return $this['paypal']->createCreditCardPayment();
    }

    /**
     * @return ApiContext
     */
    public function getPayPalApiContext()
    {
        return $this['paypal']->getPayPalApiContext();
    }

    /**
     * @param string $payerId
     * @param string $paymentID
     * @return bool
     */
    public function executePayment($payerId, $paymentID)
    {
        return $this['paypal']->executePayment($payerId, $paymentID);
    }

    /**
     * @return string
     */
    public function generateInvoiceNumber()
    {
        return $this['paypal']->generateInvoiceNumber();
    }
}