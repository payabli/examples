<?php

namespace Payabli\Types;

enum Whenprovided: string
{
    case ThirtyDaysOrLess = "30 Days or Less";
    case ThirtyOneTo60Days = "31 to 60 Days";
    case SixtyDays = "60+ Days";
}
