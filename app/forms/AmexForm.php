<?php

namespace app\forms;

use Yii;
use app\validators\ECCValidator;
use yii\base\Model;

/**
 * Class AmexForm
 *
 */
class AmexForm extends Model
{
    public $cardnumber;
    public $cardholder;
    public $month;
    public $year;
    public $cvv;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // cardnumber, cardholder, 'month, year, cvv are required
            [['cardnumber', 'cardholder', 'month', 'year', 'cvv'], 'required'],
            [['cardnumber', 'cvv'], 'integer'],
            [['cardnumber'], ECCValidator::className(), 'format' => ECCValidator::AMERICAN_EXPRESS],
            [['cardnumber'], 'string', 'min' => 15, 'max' => 19],
            [['cardholder'], 'string', 'max' => 30],
            [['month'], 'string', 'max' => 2],
            [['year'], 'string', 'max' => 2],
            [['cvv'], 'string', 'min' => 3, 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'cardnumber' => 'Card Number',
			'cardholder' => 'Card Holder',
			'month' => 'Month',
			'year' => 'Year',
			'cvv' => 'CVV'
		];
	}
}
