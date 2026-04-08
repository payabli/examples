<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class KeyValue extends JsonSerializableType
{
    /**
     * @var ?string $key Key name.
     */
    #[JsonProperty('key')]
    public ?string $key;

    /**
     * @var ?bool $readOnly
     */
    #[JsonProperty('readOnly')]
    public ?bool $readOnly;

    /**
     * @var ?string $value Key value.
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * @param array{
     *   key?: ?string,
     *   readOnly?: ?bool,
     *   value?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->key = $values['key'] ?? null;
        $this->readOnly = $values['readOnly'] ?? null;
        $this->value = $values['value'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
