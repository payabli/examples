<?php

namespace Payabli\Types;

enum Frequencynotification: string
{
    case OneTime = "one-time";
    case Daily = "daily";
    case Weekly = "weekly";
    case Biweekly = "biweekly";
    case Monthly = "monthly";
    case Quarterly = "quarterly";
    case Semiannually = "semiannually";
    case Annually = "annually";
    case Untilcancelled = "untilcancelled";
}
