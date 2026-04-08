<?php

namespace Payabli\Traits;

use Payabli\Core\Json\JsonProperty;

/**
 * @property ?bool $acceptCcd
 * @property ?bool $acceptPpd
 * @property ?bool $acceptWeb
 */
trait AchSetup
{
    /**
     * @var ?bool $acceptCcd CCD is an ACH SEC Code that can be used in ACH transactions by the user that indicates the transaction is a Corporate Credit or Debit Entry. Options are: `true` and `false`
     */
    #[JsonProperty('acceptCCD')]
    public ?bool $acceptCcd;

    /**
     * @var ?bool $acceptPpd PPD is an ACH SEC Code that can be used in ACH transactions by the user that indicates the transaction is a Prearranged Payment and Deposit.
     */
    #[JsonProperty('acceptPPD')]
    public ?bool $acceptPpd;

    /**
     * @var ?bool $acceptWeb Web is an ACH SEC Code that can be used in ACH transactions by the user that indicates the transaction is a Internet Initiated/Mobile Entry Options are `true` and `false`.
     */
    #[JsonProperty('acceptWeb')]
    public ?bool $acceptWeb;
}
