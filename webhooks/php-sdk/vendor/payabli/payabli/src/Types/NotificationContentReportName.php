<?php

namespace Payabli\Types;

enum NotificationContentReportName: string
{
    case Transaction = "Transaction";
    case Settlement = "Settlement";
    case Boarding = "Boarding";
    case Returned = "Returned";
}
