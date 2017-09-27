<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 08.10.2016
 * Time: 09:05
 */

namespace SKoziel\Silex\PayPalRest\Payments;

use PayPal\Api\ItemList;
use PayPal\Api\Item;
use PayPal\Rest\ApiContext;

abstract class Payment
{
    protected $payer;
    protected $itemList;
    protected $details;
    protected $amount;
    protected $transaction;
    protected $redirectUrls;
    protected $currency;
    protected $payment;

    /**
     * @param ApiContext $apiContext
     * @return string
     */
    abstract public function getApprovalUrl(ApiContext $apiContext);

    /**
     * @return ItemList
     */
    public function getItemList()
    {
        return $this->itemList;
    }

    /**
     * @return array
     */
    public function getItemListAsArray()
    {
        return $this->itemList->items;
    }

    /**
     * @param string $itemName
     * @param int $quantity
     * @param string $sku
     * @param float $price
     * @return Payment
     */
    public function addItem($itemName, $quantity, $sku, $price)
    {
        $newItem = new Item();

        $newItem->setName($itemName)
            ->setCurrency($this->currency)
            ->setQuantity(intval($quantity))
            ->setSku($sku)
            ->setPrice(floatval($price));

        $arrayOfCurrentItemsOnList = $this->itemList->items;

        if (is_null($arrayOfCurrentItemsOnList)) {
            $arrayOfCurrentItemsOnList = array();
        }

        array_push($arrayOfCurrentItemsOnList, $newItem);

        $this->itemList->setItems($arrayOfCurrentItemsOnList);

        return $this;
    }

    /**
     * @param float $shipping
     * @return Payment
     */
    public function setShipping($shipping)
    {
        $this->details->setShipping(floatval($shipping));

        return $this;
    }

    /**
     * @param float $tax
     * @return Payment
     */
    public function setTax($tax)
    {
        $this->details->setTax(floatval($tax));

        return $this;
    }

    /**
     * @param string $invoiceNumber
     * @return Payment
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->transaction->setInvoiceNumber($invoiceNumber);

        return $this;
    }

    /**
     * @param string $description
     * @return Payment
     */
    public function setDescription($description)
    {
        $this->transaction->setDescription($description);

        return $this;
    }

    /**
     * @param string $successUrl
     * @return Payment
     */
    public function setSuccessUrl($successUrl)
    {
        $this->redirectUrls->setReturnUrl($successUrl);

        return $this;
    }

    /**
     * @param string $failureUrl
     * @return Payment
     */
    public function setFailureUrl($failureUrl)
    {
        $this->redirectUrls->setCancelUrl($failureUrl);

        return $this;
    }

    /**
     * @return float
     */
    protected function calculateSubTotal()
    {
        $items = $this->getItemListAsArray();

        $totalPrice = 0;

        if (count($items) == 0) {
            return $totalPrice;
        }

        foreach ($items as $item) {
            $totalPrice += floatval($item->getPrice());
        }

        return $totalPrice;
    }

    /**
     * @return float
     */
    protected function calculateTotal()
    {
        $subTotal = floatval($this->details->getSubtotal());

        $shipping = floatval($this->details->getShipping());

        $tax = floatval($this->details->getTax());

        $total = $subTotal + $shipping + $tax;

        return $total;
    }
}