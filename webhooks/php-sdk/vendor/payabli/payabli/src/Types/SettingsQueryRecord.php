<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class SettingsQueryRecord extends JsonSerializableType
{
    /**
     * @var ?array<KeyValue> $customFields Any custom fields defined for the org.
     */
    #[JsonProperty('customFields'), ArrayType([KeyValue::class])]
    public ?array $customFields;

    /**
     * @var ?array<KeyValue> $forInvoices
     */
    #[JsonProperty('forInvoices'), ArrayType([KeyValue::class])]
    public ?array $forInvoices;

    /**
     * @var ?array<KeyValue> $forPayOuts
     */
    #[JsonProperty('forPayOuts'), ArrayType([KeyValue::class])]
    public ?array $forPayOuts;

    /**
     * @var ?array<KeyValue> $forWallets Information about digital wallet settings for the entity. Available values are `isApplePayEnabled` and `isGooglePayEnabled`.
     */
    #[JsonProperty('forWallets'), ArrayType([KeyValue::class])]
    public ?array $forWallets;

    /**
     * @var ?array<KeyValue> $general
     */
    #[JsonProperty('general'), ArrayType([KeyValue::class])]
    public ?array $general;

    /**
     * @var ?array<KeyValue> $identifiers
     */
    #[JsonProperty('identifiers'), ArrayType([KeyValue::class])]
    public ?array $identifiers;

    /**
     * @param array{
     *   customFields?: ?array<KeyValue>,
     *   forInvoices?: ?array<KeyValue>,
     *   forPayOuts?: ?array<KeyValue>,
     *   forWallets?: ?array<KeyValue>,
     *   general?: ?array<KeyValue>,
     *   identifiers?: ?array<KeyValue>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->customFields = $values['customFields'] ?? null;
        $this->forInvoices = $values['forInvoices'] ?? null;
        $this->forPayOuts = $values['forPayOuts'] ?? null;
        $this->forWallets = $values['forWallets'] ?? null;
        $this->general = $values['general'] ?? null;
        $this->identifiers = $values['identifiers'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
