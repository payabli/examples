<?php

namespace Payabli\Types;

enum VendorDataResponsePaymentMethod: string
{
    case Vcard = "vcard";
    case Ach = "ach";
    case Check = "check";
    case Card = "card";
}
