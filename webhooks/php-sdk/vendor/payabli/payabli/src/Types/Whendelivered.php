<?php

namespace Payabli\Types;

enum Whendelivered: string
{
    case Zero7Days = "0-7 Days";
    case Eight14Days = "8-14 Days";
    case Fifteen30Days = "15-30 Days";
    case Over30Days = "Over 30 Days";
}
