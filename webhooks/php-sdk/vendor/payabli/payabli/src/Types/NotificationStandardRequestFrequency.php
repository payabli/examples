<?php

namespace Payabli\Types;

enum NotificationStandardRequestFrequency: string
{
    case OneTime = "one-time";
    case Untilcancelled = "untilcancelled";
}
