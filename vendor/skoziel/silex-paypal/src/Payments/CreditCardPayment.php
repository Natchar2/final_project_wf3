<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 08.10.2016
 * Time: 09:32
 */

namespace SKoziel\Silex\PayPalRest\Payments;

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

class CreditCardPayment extends \SKoziel\Silex\PayPalRest\Payments\Payment
{

    private $creditCard;
    private $fundingInstrument;

    public function __construct($currency)
    {
        $this->creditCard = new CreditCard();

        $this->fundingInstrument = new FundingInstrument();

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

        $this->fundingInstrument->setCreditCard($this->creditCard);

        $this->payer->setPaymentMethod('credit_card');
        $this->payer->setFundingInstruments(array($this->fundingInstrument));

        $this->payment->setIntent('sale');
        $this->payment->setPayer($this->payer);
        $this->payment->setTransactions(array($this->transaction));
        $this->payment->create($apiContext);

        return $this->payment->getApprovalLink();
    }

    /**
     * @param string $type
     * @return CreditCardPayment
     */
    public function setType($type)
    {
        $this->creditCard->setType($type);
        return $this;
    }

    /**
     * @param string $number
     * @return CreditCardPayment
     */
    public function setNumber($number)
    {
        $this->creditCard->setNumber($number);
        return $this;
    }

    /**
     * @param string $month
     * @return CreditCardPayment
     */
    public function setExpireMonth($month)
    {
        $this->creditCard->setExpireMonth($month);
        return $this;
    }

    /**
     * @param string $year
     * @return CreditCardPayment
     */
    public function setExpireYear($year)
    {
        $this->creditCard->setExpireYear($year);
        return $this;
    }

    /**
     * @param string $cvv2
     * @return CreditCardPayment
     */
    public function setCvv2($cvv2)
    {
        $this->creditCard->setCvv2($cvv2);
        return $this;
    }

    /**
     * @param string $firstName
     * @return CreditCardPayment
     */
    public function setFirstName($firstName)
    {
        $this->creditCard->setFirstName($firstName);
        return $this;
    }

    /**
     * @param string $lastName
     * @return CreditCardPayment
     */
    public function setLastName($lastName)
    {
        $this->creditCard->setLastName($lastName);
        return $this;
    }

    /**
     * @param string $line1
     * @param string $line2
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $countryCode
     * @return CreditCardPayment
     */
    public function setBillingAddress($line1, $line2, $city, $state, $postalCode, $countryCode)
    {
        $billingAddress = new Address();
        $billingAddress
            ->setLine1($line1)
            ->setLine2($line2)
            ->setCity($city)
            ->setState($state)
            ->setPostalCode($postalCode)
            ->setCountryCode($countryCode);
        $this->creditCard->setBillingAddress($billingAddress);
        return $this;
    }
}