<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class Instrument extends JsonSerializableType
{
    /**
     * @var string $achAccount
     */
    #[JsonProperty('achAccount')]
    public string $achAccount;

    /**
     * @var string $achRouting
     */
    #[JsonProperty('achRouting')]
    public string $achRouting;

    /**
     * @var ?string $billingAddress
     */
    #[JsonProperty('billingAddress')]
    public ?string $billingAddress;

    /**
     * @var ?string $billingCity
     */
    #[JsonProperty('billingCity')]
    public ?string $billingCity;

    /**
     * @var ?string $billingCountry
     */
    #[JsonProperty('billingCountry')]
    public ?string $billingCountry;

    /**
     * @var ?string $billingState
     */
    #[JsonProperty('billingState')]
    public ?string $billingState;

    /**
     * @var ?string $billingZip
     */
    #[JsonProperty('billingZip')]
    public ?string $billingZip;

    /**
     * @param array{
     *   achAccount: string,
     *   achRouting: string,
     *   billingAddress?: ?string,
     *   billingCity?: ?string,
     *   billingCountry?: ?string,
     *   billingState?: ?string,
     *   billingZip?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achAccount = $values['achAccount'];
        $this->achRouting = $values['achRouting'];
        $this->billingAddress = $values['billingAddress'] ?? null;
        $this->billingCity = $values['billingCity'] ?? null;
        $this->billingCountry = $values['billingCountry'] ?? null;
        $this->billingState = $values['billingState'] ?? null;
        $this->billingZip = $values['billingZip'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
