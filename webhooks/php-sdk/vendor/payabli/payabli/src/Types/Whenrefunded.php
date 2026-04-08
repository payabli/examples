<?php

namespace Payabli\Types;

enum Whenrefunded: string
{
    case ExchangeOnly = "Exchange Only";
    case NoRefundOrExchange = "No Refund or Exchange";
    case MoreThan30Days = "More than 30 days";
    case ThirtyDaysOrLess = "30 Days or Less";
}
