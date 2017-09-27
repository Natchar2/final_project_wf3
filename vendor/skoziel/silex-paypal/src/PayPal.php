<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 07.10.2016
 * Time: 22:27
 */

namespace SKoziel\Silex\PayPalRest;


use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Invoice;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use SKoziel\Silex\PayPalRest\Payments\CreditCardPayment;
use SKoziel\Silex\PayPalRest\Payments\ExpressCheckout;

class PayPal
{
    private $currency;
    private $apiContext;

    const LOG_LEVEL = 'FINE';
    const LOG_FILE_NAME = '/paypal.log';

    /**
     * PayPal constructor.
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->currency = (array_key_exists('currency', $settings) ? $settings['currency'] : 'EUR');
        $mode = (array_key_exists('mode', $settings) ? $settings['mode'] : 'sandbox');
        $clientID = (array_key_exists('clientID', $settings) ? $settings['clientID'] : null);
        $secret = (array_key_exists('secret', $settings) ? $settings['secret'] : null);
        $connectionTimeOut = (array_key_exists('connectionTimeOut', $settings) ? $settings['connectionTimeOut'] : 30);
        $logEnabled = (array_key_exists('logEnabled', $settings) ? $settings['logEnabled'] : true);
        $logDir = (array_key_exists('logDir', $settings) ? $settings['logDir'] : __DIR__ . '/../../../../logs');

        if (!file_exists($logDir)) {
            mkdir($logDir);
        }

        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $clientID,
            $secret
        ));

        $this->apiContext->setConfig(array(
            'mode' => $mode,
            'http.ConnectionTimeOut' => $connectionTimeOut,
            'log.LogEnabled' => $logEnabled,
            'log.FileName' => $logDir . self::LOG_FILE_NAME,
            'log.LogLevel' => self::LOG_LEVEL
        ));
    }

    /**
     * @return ExpressCheckout
     */
    public function createExpressCheckout()
    {
        return new ExpressCheckout($this->currency);
    }

    /**
     * @return CreditCardPayment
     */
    public function createCreditCardPayment()
    {
        return new CreditCardPayment($this->currency);
    }

    /**
     * @return ApiContext
     */
    public function getPayPalApiContext()
    {
        return $this->apiContext;
    }

    /**
     * @param string $payerId
     * @param string $paymentID
     * @return bool
     */
    public function executePayment($payerId, $paymentID)
    {
        $payment = Payment::get($paymentID, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        try {
            $payment->execute($execution, $this->apiContext);
            return true;
        } catch (\Exception $exp) {
            return false;
        }
    }

    /**
     * @return string
     */
    public function generateInvoiceNumber()
    {
        $invoice = Invoice::generateNumber($this->apiContext);
        return $invoice->number;
    }
}