<?php

namespace Payabli\Types;

enum BillDataPaymentTerms: string
{
    case Pia = "PIA";
    case Cia = "CIA";
    case Ur = "UR";
    case Net10 = "NET10";
    case Net20 = "NET20";
    case Net30 = "NET30";
    case Net45 = "NET45";
    case Net60 = "NET60";
    case Net90 = "NET90";
    case Eom = "EOM";
    case Mfi = "MFI";
    case FiveMfi = "5MFI";
    case TenMfi = "10MFI";
    case FifteenMfi = "15MFI";
    case TwentyMfi = "20MFI";
    case Two10Net30 = "2/10NET30";
    case Uf = "UF";
    case TenUf = "10UF";
    case TwentyUf = "20UF";
    case TwentyFiveUf = "25UF";
    case FiftyUf = "50UF";
}
