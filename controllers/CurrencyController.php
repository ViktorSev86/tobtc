<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Currency;
use yii\helpers\Json;
use yii\helpers\Url;

class CurrencyController extends Controller

{
    private $token = "1111111111111111111111111111111111111111111111111111111111111111";

    public function actionRates()
    {
        $headers = Yii::$app->request->headers;
        if ($headers->has('Authorization') && $headers->get('Authorization') == 'Bearer ' . $token) {
            
            $currencyModel = new Currency;
            $rates = $currencyModel->getRates();

            if (!isset($rates[$currencyFrom]) || !isset($rates[$currencyTo])) {
                return json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid currencies',
                ]);
            }
            
            // Учитываем комиссию 2 %
            foreach ($rates as $key => &$value) {
                $value = round($value * 1.02, 2);
            }
            asort($rates);
            return json_encode([
                'status' => 'success',
                'code' => 200,
                'message' => 'Rates to BTC with commission 2%',
                'data' => $rates
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'code' => 403,
                'message' => 'Invalid token'
            ]);
        }
    }


    public function actionConvert()
    {
        $headers = Yii::$app->req1->headers;

        if ($headers->has('Authorization') && $headers->get('Authorization') == 'Bearer ' . $token) {

            $params = Yii::$app->getRequest()->getBodyParams();

            $currencyFrom = strtoupper($params['currency_from']);
            $currencyTo = strtoupper($params['currency_to']);
            $value = (float)$params['value'];

            // проверка на валидность данных
            if (!$currencyFrom || !$currencyTo || !$value) {
                return json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid parameters',
                ]);
            }

            $currencyModel = new Currency();
            $rates = $currencyModel->getRates();

            if (!isset($rates[$currencyFrom]) || !isset($rates[$currencyTo])) {
                return json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid currencies',
                ]);
            }

            // учитываем, что минимальный обмен равен 0,01 валюты from
            if ($value < 0.01 && $currencyFrom != 'BTC') {
                return json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Minimum exchange rate is 0.01 ' . $currencyFrom,
                ]);
            }

            if ($currencyFrom == 'BTC') {
                $rate = $rates[$currencyTo];
                $tempValue = $value / $rate * 0.98;
                $minValue = ceil(0.01 * $rate / 0.98 * 100) / 100;
                if ($tempValue < 0.01) {
                    return json_encode([
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Minimum exchange rate is '. $minValue . $currencyFrom,
                    ]);
                }
                $convertedValue = round($tempValue, 2);
                
            } else if ($currencyTo == 'BTC') {
                $rate = $rates[$currencyFrom];
                $convertedValue = round($value * $rate * 0.98, 10);
            } else {
                return json_encode([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid currencies',
                ]);
            }

            return json_encode([
                'status' => 'success',
                'code' => 200,
                'data' => json_encode([
                    'currency_from' => $currencyFrom,
                    'currency_to' => $currencyTo,
                    'value' => $value,
                    'converted_value' => $convertedValue,
                    'rate' => $rate,
                ]),
            ]);
        
        } else {
            return json_encode([
                'status' => 'error',
                'code' => 403,
                'message' => 'Invalid token'
            ]);
        }
    }

    
        

        
    
    
    public function actionIndex() // Для отладки
    {
        echo 'Hi';
        /*
        // Отладка метода actionRates()
        $currencyModel = new Currency;
        $rates = $currencyModel->getRates();
        //var_dump($data);
        foreach ($rates as $key => &$value) {
            $value = round($value['15m'] * 1.02, 2);
        }
        asort($rates);
        //var_dump($data);
        $q = json_encode([
            'status' => 'success',
            'code' => 200,
            'message' => 'Rates to BTC with commission 2%',
            'data' => $rates
        ]);
        var_dump($q);
        */



        

        /*
        // Отладка метода actionConvert()
        $data = [
            'currency_from' => 'BTC', // USD
            'currency_to' => 'USD', // BTC
            'value' => '1000.00', // 1
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                            "Authorization: Bearer 1111111111111111111111111111111111111111111111111111111111111111\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];

        
        $headers = $options['http']['header'];
        if (strpos($headers, '1111111111111111111111111111111111111111111111111111111111111111')) {

            //$params = Yii::$app->getRequest()->getBodyParams();
            $params = $data;
            $currencyFrom = strtoupper($params['currency_from']);
            //echo($currencyFrom);
            $currencyTo = strtoupper($params['currency_to']);
            $value = (float)$params['value'];
            //var_dump($value);
            $currencyModel = new Currency();
            $rates = $currencyModel->getRates();

            //var_dump($rates[$currencyFrom]);
            //var_dump($rates[$currencyTo]);

            if ($currencyFrom == 'BTC') {
                $rate = $rates[$currencyTo];
                //var_dump($rate);
                $convertedValue = round(($value / $rate) * 0.98, 2);
            } else if ($currencyTo == 'BTC') {
                $rate = $rates[$currencyFrom];
                $convertedValue = round($value * $rate * 0.98, 10);
            }

            echo($convertedValue);

        
        } */

        //return $this->render('index');
    }


}
