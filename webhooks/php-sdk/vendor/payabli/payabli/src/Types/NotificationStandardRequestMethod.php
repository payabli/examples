<?php

namespace Payabli\Types;

enum NotificationStandardRequestMethod: string
{
    case Email = "email";
    case Sms = "sms";
    case Web = "web";
}
