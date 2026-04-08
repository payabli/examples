<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class MethodsList extends JsonSerializableType
{
    /**
     * @var ?bool $amex When `true`, American Express is accepted.
     */
    #[JsonProperty('amex')]
    public ?bool $amex;

    /**
     * @var ?bool $applePay When `true`, Apple Pay is accepted.
     */
    #[JsonProperty('applePay')]
    public ?bool $applePay;

    /**
     * @var ?bool $googlePay When `true`, Google Pay is accepted.
     */
    #[JsonProperty('googlePay')]
    public ?bool $googlePay;

    /**
     * @var ?bool $discover When `true`, Discover is accepted.
     */
    #[JsonProperty('discover')]
    public ?bool $discover;

    /**
     * @var ?bool $eCheck When `true`, ACH is accepted.
     */
    #[JsonProperty('eCheck')]
    public ?bool $eCheck;

    /**
     * @var ?bool $mastercard When `true`, Mastercard is accepted.
     */
    #[JsonProperty('mastercard')]
    public ?bool $mastercard;

    /**
     * @var ?bool $visa When `true`, Visa is accepted.
     */
    #[JsonProperty('visa')]
    public ?bool $visa;

    /**
     * @param array{
     *   amex?: ?bool,
     *   applePay?: ?bool,
     *   googlePay?: ?bool,
     *   discover?: ?bool,
     *   eCheck?: ?bool,
     *   mastercard?: ?bool,
     *   visa?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amex = $values['amex'] ?? null;
        $this->applePay = $values['applePay'] ?? null;
        $this->googlePay = $values['googlePay'] ?? null;
        $this->discover = $values['discover'] ?? null;
        $this->eCheck = $values['eCheck'] ?? null;
        $this->mastercard = $values['mastercard'] ?? null;
        $this->visa = $values['visa'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
