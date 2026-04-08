<?php

namespace Payabli\Types;

enum MethodElementSettingsApplePayButtonType: string
{
    case Plain = "plain";
    case Buy = "buy";
    case Donate = "donate";
    case CheckOut = "check-out";
    case Book = "book";
    case Continue_ = "continue";
    case TopUp = "top-up";
    case Order = "order";
    case Rent = "rent";
    case Support = "support";
    case Contribute = "contribute";
    case Tip = "tip";
    case Pay = "pay";
}
