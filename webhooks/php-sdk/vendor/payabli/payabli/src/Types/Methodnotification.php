<?php

namespace Payabli\Types;

enum Methodnotification: string
{
    case Email = "email";
    case Sms = "sms";
    case Web = "web";
    case ReportEmail = "report-email";
    case ReportWeb = "report-web";
}
