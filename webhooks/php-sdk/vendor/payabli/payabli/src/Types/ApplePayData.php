<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Details about the status of the Apple Pay service.
 */
class ApplePayData extends JsonSerializableType
{
    /**
     * @var ?ApplePayStatusData $data This object is only returned when the domain verification check fails. If a domain has failed validation, this object contains information about the failure.
     */
    #[JsonProperty('data')]
    public ?ApplePayStatusData $data;

    /**
     * @var ?bool $isEnabled When `true`, Apple Pay is enabled.
     */
    #[JsonProperty('isEnabled')]
    public ?bool $isEnabled;

    /**
     * @param array{
     *   data?: ?ApplePayStatusData,
     *   isEnabled?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->data = $values['data'] ?? null;
        $this->isEnabled = $values['isEnabled'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
