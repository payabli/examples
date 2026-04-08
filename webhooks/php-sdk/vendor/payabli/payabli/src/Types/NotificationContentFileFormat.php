<?php

namespace Payabli\Types;

enum NotificationContentFileFormat: string
{
    case Json = "json";
    case Csv = "csv";
    case Xlsx = "xlsx";
}
