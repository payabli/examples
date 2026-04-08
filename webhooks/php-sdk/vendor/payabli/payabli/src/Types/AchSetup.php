<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class AchSetup extends JsonSerializableType
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

    /**
     * @param array{
     *   acceptCcd?: ?bool,
     *   acceptPpd?: ?bool,
     *   acceptWeb?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptCcd = $values['acceptCcd'] ?? null;
        $this->acceptPpd = $values['acceptPpd'] ?? null;
        $this->acceptWeb = $values['acceptWeb'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
