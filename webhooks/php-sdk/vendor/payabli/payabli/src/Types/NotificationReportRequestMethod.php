<?php

namespace Payabli\Types;

enum NotificationReportRequestMethod: string
{
    case ReportEmail = "report-email";
    case ReportWeb = "report-web";
}
