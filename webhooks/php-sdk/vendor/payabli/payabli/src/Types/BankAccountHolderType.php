<?php

namespace Payabli\Types;

enum BankAccountHolderType: string
{
    case Personal = "Personal";
    case Business = "Business";
}
