<?php

namespace app\controllers;

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

class PaypalController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['process', 'test'],
                'rules' => [
                    [
                        'actions' => ['process'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }

    public function actionProcess()
    {
        $api = Yii::$app->ppl->_apiContext;
        $shipping_address = new ShippingAddress();
        $shipping_address->setRecipientName('Anil Chaudhari');
        $shipping_address->setLine1('Mansul Gally');
        $shipping_address->setPostalCode(110001);
        $shipping_address->setCity('110001');
        $shipping_address->setState('DL');
        $shipping_address->setCountryCode('IN');

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item = new Item();
        $item->setName("iPhone Cover case")->setCurrency('INR')->setQuantity(1)->setPrice(1000);
        $items[] =  $item;

        $itemList = new ItemList();
        $itemList->setItems($items);
        $itemList->setShippingAddress($shipping_address);

        $amount = new Amount();
        $amount->setCurrency("INR")
            ->setTotal(1000);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Product Lists")
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(Url::to(['/paypal/confirm'], true));
        $redirectUrls->setCancelUrl(Url::to(['/paypal/cancel'], true));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        // For Sample Purposes Only.
        $request = clone $payment;

        try {
            $payment->create($api);
            $approvalLink = $payment->getApprovalLink();
            return $this->redirect($approvalLink);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            Yii::trace($ex->getMessage(), 'tranx');
            throw $ex;
        } catch (\Exception $ex) {
            Yii::trace($ex->getMessage(), 'tranx');
            throw $ex;
        }
    }

    public function actionConfirm($paymentId = null, $token = null, $PayerID = null)
    {
        echo '<pre>';
        $apiContext = Yii::$app->ppl->_apiContext;
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);
        try {
            $result = $payment->execute($execution, $apiContext);

            print_r($result);
            exit;
            try {
                if ($result['status'] != 'APPROVED') {
                    if (Yii::$app->paypal->isProduction === true) {
                        //Live mode basic error message
                        $error = 'We were unable to process your request. Please try again later';
                    } else {
                        //Sandbox output the actual error message to dive in.
                        $error = $result['L_LONGMESSAGE0'];
                    }
                    $trans = Yii::$app->db->beginTransaction();

                    //insert into db
                    try {

                    } catch (Exception $e) {
                        $trans->rollback();
                        Yii::error($e->getMessage(), 'tranx');
                    }
                } else {
                    return $this->render('confirm');
                }
            } catch (Exception $ex) {
                print_r($ex);
                Yii::error($ex->getMessage(), 'tranx');
            }

        } catch (\Exception $ex) {
            print_r($ex);
            Yii::error($ex->getMessage(), 'tranx');
        }
    }

    public function actionCancel()
    {
        return $this->render('cancel');
    }
}
