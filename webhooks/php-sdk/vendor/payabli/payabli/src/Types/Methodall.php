<?php

namespace Payabli\Types;

enum Methodall: string
{
    case Card = "card";
    case Ach = "ach";
    case Cloud = "cloud";
    case Check = "check";
    case Cash = "cash";
}
