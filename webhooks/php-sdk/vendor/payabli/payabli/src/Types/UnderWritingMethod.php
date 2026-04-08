<?php

namespace Payabli\Types;

enum UnderWritingMethod: string
{
    case Automatic = "automatic";
    case Manual = "manual";
    case Bypass = "bypass";
}
