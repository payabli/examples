<?php

namespace Payabli\Types;

enum Frequency: string
{
    case OneTime = "one-time";
    case Weekly = "weekly";
    case Every2Weeks = "every2weeks";
    case Every6Months = "every6months";
    case Monthly = "monthly";
    case Every3Months = "every3months";
    case Annually = "annually";
}
