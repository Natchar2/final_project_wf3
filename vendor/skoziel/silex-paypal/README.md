# PayPal

This service provider is based on [PayPal PHP SDK](https://github.com/paypal/PayPal-PHP-SDK) and enables quick creating [Pay Pal Express Checkout](https://developer.paypal.com/docs/integration/direct/express-checkout/) and [Credit Card Payments](https://developer.paypal.com/docs/integration/direct/accept-credit-cards/).

## Requirements

- silex/silex >= 2.0
- paypal/rest-api-sdk-php >= 1.7
- PHP >= 5.6

## Registering

```php
$app->register(new SKoziel\Silex\PayPalRest\PayPalServiceProvider(), array(
    'paypal.settings'=>array(
        'mode'=>$mode //'live' or 'sandbox'(default)
        'clientID'=>$clientID //Checkout PayPal Documentation for more info
        'secret'=>$secret //Checkout PayPal Documentation for more info
        'connectionTimeOut'=>$connectionTimeOut //Connection time out in seconds, optional, default = 30
        'logEnabled'=>false, //This parameter is optional, default = true
        'logdir'=>$logdir, //This parameter is optional, default = ROOT/logs
        'currency'=>'PLN' //This parameter is optional, default = EUR
    )));
```

##Trait

```php
use SKoziel\Silex\PayPalRest\PayPalTrait;

$expressCheckout = $app->createExpressCheckout();
$creditCardPayment = $app->createCreditCreditCardPayment();
$apiContext = $app->getPayPalApiContext();
$executionSuccessful = $app->executePayment($payerId, $paymentID);
$invoiceNumber = $app->generateInvoiceNumber();
```

## Usage

###Creating Express Checkouts

```php
$expressCheckout = $app['paypal']->createExpressCheckout();
$expressCheckout
        ->addItem('Item Name', 1, 'sku0', 120.99)
        ->addItem('Another Item', 2, 'sku1', 10.99)
        ->setTax(29.99)
        ->setShipping(25.99)
        ->setDescription('Description')
        ->setInvoiceNumber($app['paypal']->generateInvoiceNumber())
        ->setSuccessUrl($successUrl)
        ->setFailureUrl($failureUrl);
```

###Creating Credit Card Payment

```php
$creditCardPayment = $app->createCreditCreditCardPayment();
    $creditCardPayment
        ->setType('visa')
        ->setNumber('4669424246660779')
        ->setExpireMonth('11')
        ->setExpireYear('2019')
        ->setCvv2('012')
        ->setFirstName('Joe')
        ->setLastName('Shopper')
        ->setBillingAddress(
            $line1,
            $line2,
            $city,
            $state,
            $postalCode,
            $countryCode)
        ->addItem('Item Name', 1, 'sku0', 120.99)
        ->addItem('Another Item', 2, 'sku1', 10.99)
        ->setTax(29.99)
        ->setShipping(25.99)
        ->setDescription('Description')
        ->setInvoiceNumber($app['paypal']->generateInvoiceNumber());
```

###Getting approval url

```php
//Redirect to approval url to obtain PayerID and PaymetnID required for payment execution
$approvalUrl = $expressCheckout->getApprovalUrl($app['paypal']->getPayPalApiContext());

$approvalUrl = $creditCardPayment->getApprovalUrl($app['paypal']->getPayPalApiContext());
```

###Executing payment

```php
/*
$payerID - string, returned by PayPal after successful approval
$paymentID - string, returned by PayPal after successful approval
*/
//returns true if execution was successful, fals if wasn't
$executionSuccessful = $app['paypal']->executePayment($payerID, $paymentID); 
```

###Generating invoice number

```php
$invoiceNumber = $app['paypal']->generateInvoiceNumber();
```