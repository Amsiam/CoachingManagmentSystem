<?php

namespace App\SMS;

use Xenon\LaravelBDSms\Facades\SMS;

class AdmissionSms
{
    public static function sendMessage($number, $name, $reg, $roll, $password, $payment, $from = "Tusher's care")
    {

        SMS::shoot(
            $number,
            "Welcome To " . $from . " familly.\nName: "
                . $name .
                "\nYour Registration: "
                . $reg .
                "\nYour roll: "
                . $roll .
                "\nYour Password: "
                . $password . "\nTotal: "
                . $payment->total . "\nDiscount: " . $payment->discount .
                "\nPaid: " . $payment->paid .
                 "\nDue: " . $payment->due
        );
    }
}
