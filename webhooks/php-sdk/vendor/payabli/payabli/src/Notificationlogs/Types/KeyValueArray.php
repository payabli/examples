<?php

namespace Payabli\Notificationlogs\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class KeyValueArray extends JsonSerializableType
{
    /**
     * @var ?string $key
     */
    #[JsonProperty('key')]
    public ?string $key;

    /**
     * @var ?array<string> $value
     */
    #[JsonProperty('value'), ArrayType(['string'])]
    public ?array $value;

    /**
     * @param array{
     *   key?: ?string,
     *   value?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->key = $values['key'] ?? null;
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
