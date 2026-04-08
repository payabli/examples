<?php

namespace Payabli\Types;

enum PayMethodStoredMethodMethod: string
{
    case Card = "card";
    case Ach = "ach";
}
