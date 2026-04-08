<?php

namespace Payabli\Types;

enum BillQueryRecord2PaymentMethod: string
{
    case Vcard = "vcard";
    case Ach = "ach";
    case Check = "check";
    case Card = "card";
    case Managed = "managed";
}
