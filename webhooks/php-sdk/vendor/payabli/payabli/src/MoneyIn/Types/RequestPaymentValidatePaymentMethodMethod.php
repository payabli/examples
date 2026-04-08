<?php

namespace Payabli\MoneyIn\Types;

enum RequestPaymentValidatePaymentMethodMethod: string
{
    case Card = "card";
    case Cloud = "cloud";
}
