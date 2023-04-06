<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Currency extends Model
{
    public function getRates() {
        // Получаем список всех курсов валют
        $rates = json_decode(file_get_contents('https://blockchain.info/ticker'), true);
        foreach ($rates as $key => &$value) {
            $value = $value['15m'];
        }
        return $rates;
    }
    
}

