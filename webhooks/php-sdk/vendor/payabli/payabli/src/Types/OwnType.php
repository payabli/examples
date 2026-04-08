<?php

namespace Payabli\Types;

enum OwnType: string
{
    case LimitedLiabilityCompany = "Limited Liability Company";
    case NonProfitOrg = "Non-Profit Org";
    case Partnership = "Partnership";
    case PrivateCorp = "Private Corp";
    case PublicCorp = "Public Corp";
    case TaxExempt = "Tax Exempt";
    case Government = "Government";
    case SoleProprietor = "Sole Proprietor";
}
