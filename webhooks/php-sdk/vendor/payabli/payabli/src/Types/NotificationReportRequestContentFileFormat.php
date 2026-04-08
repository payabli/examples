<?php

namespace Payabli\Types;

enum NotificationReportRequestContentFileFormat: string
{
    case Json = "json";
    case Csv = "csv";
    case Xlsx = "xlsx";
}
