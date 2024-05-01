<?php

namespace App\SMS;

use Xenon\LaravelBDSms\Facades\SMS;

class PaymentSMS
{

    public static function sendMessage($number, $payment,$due, $from = "Tusher's care")
    {
        if($due<0){
            $due = 0;
        }

        $month = "";

        if($payment->month){
            $month = "\nMonth: ". date("F", strtotime($payment->month));
        }

        self::shoot(
            $number,
            "Paid: " . $payment->paid . "\nDiscount: " . $payment->discount . "\nDue: ".$due."".$month  . "\n-" . $from
        );
    }

    public static function shoot($number, $message)
    {
        SMS::shoot($number, $message);
    }
}
