<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 07.10.2016
 * Time: 22:10
 */

namespace SKoziel\Silex\PayPalRest\Payments;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;


class ExpressCheckout extends \SKoziel\Silex\PayPalRest\Payments\Payment
{

    public function __construct($currency)
    {
        $this->payer = new Payer();
        $this->payment = new Payment();
        $this->itemList = new ItemList();
        $this->details = new Details();
        $this->amount = new Amount();
        $this->transaction = new Transaction();
        $this->redirectUrls = new RedirectUrls();
        $this->currency = $currency;
    }

    /**
     * @param ApiContext $apiContext
     * @return null|string
     */
    public function getApprovalUrl(ApiContext $apiContext)
    {
        $this->details->setSubtotal($this::calculateSubTotal());

        $this->amount->setCurrency($this->currency);
        $this->amount->setTotal($this::calculateTotal());
        $this->amount->setDetails($this->details);

        $this->transaction->setAmount($this->amount);
        $this->transaction->setItemList($this->itemList);

        $this->payer->setPaymentMethod('paypal');

        $this->payment->setIntent('sale');
        $this->payment->setPayer($this->payer);
        $this->payment->setRedirectUrls($this->redirectUrls);
        $this->payment->setTransactions(array($this->transaction));
        $this->payment->create($apiContext);

        return $this->payment->getApprovalLink();
    }


}