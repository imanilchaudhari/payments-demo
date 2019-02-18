<?php

namespace app\classes;

define('PP_CONFIG_PATH', __DIR__);

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

class Paypal extends Component
{
    const MODE_SANDBOX = 'sandbox';
    const MODE_LIVE    = 'live';

    /*
     * Logging level can be one of FINE, INFO, WARN or ERROR.
     * Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR.
     */
    const LOG_LEVEL_FINE  = 'FINE';
    const LOG_LEVEL_INFO  = 'INFO';
    const LOG_LEVEL_WARN  = 'WARN';
    const LOG_LEVEL_ERROR = 'ERROR';

    //region API settings
    public $clientId;
    public $clientSecret;
    public $isProduction = false;
    public $currency = 'INR';
    public $config = [];

    /** @var ApiContext */
    public $_apiContext = null;

    /**
     * @setConfig
     * _apiContext in init() method
     */
    public function init()
    {
        $this->setConfig();
    }

    /**
     * @inheritdoc
     */
    private function setConfig()
    {
        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com
        $this->_apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );

        // #### SDK configuration
        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration
        $this->_apiContext->setConfig(ArrayHelper::merge([
            'mode'                      => self::MODE_SANDBOX, // development (sandbox) or production (live) mode
            'http.ConnectionTimeOut'    => 30,
            'http.Retry'                => 1,
            'log.LogEnabled'            => YII_DEBUG ? 1 : 0,
            'log.FileName'              => Yii::getAlias('@runtime/paypal/app.log'),
            'log.LogLevel'              => self::LOG_LEVEL_FINE,
            'validation.level'          => 'log',
            'cache.enabled'             => 'true'
        ], $this->config));

        // Set file name of the log if present
        if (isset($this->config['log.FileName']) && isset($this->config['log.LogEnabled']) && ((bool)$this->config['log.LogEnabled'] == true)) {
            $logFileName = Yii::getAlias($this->config['log.FileName']);
            if ($logFileName && !file_exists($logFileName)) {
                mkdir(str_replace("/app.log", "", $logFileName), 0777);
                if (!touch($logFileName)) {
                    throw new ErrorException('Can\'t create app.log file at: ' . $logFileName);
                }
            }
            $this->config['log.FileName'] = $logFileName;
        }
        return $this->_apiContext;
    }
}
