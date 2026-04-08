<?php

namespace Payabli\Types;

enum NotificationReportRequestContentReportName: string
{
    case Transaction = "Transaction";
    case Settlement = "Settlement";
    case Boarding = "Boarding";
    case Returned = "Returned";
}
