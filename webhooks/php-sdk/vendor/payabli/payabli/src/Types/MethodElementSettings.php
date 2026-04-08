<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Settings for wallet payment methods.
 */
class MethodElementSettings extends JsonSerializableType
{
    /**
     * @var ?MethodElementSettingsApplePay $applePay
     */
    #[JsonProperty('applePay')]
    public ?MethodElementSettingsApplePay $applePay;

    /**
     * @param array{
     *   applePay?: ?MethodElementSettingsApplePay,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->applePay = $values['applePay'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
