<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayMethodCloud extends JsonSerializableType
{
    /**
     * @var ?string $device
     */
    #[JsonProperty('device')]
    public ?string $device;

    /**
     * @var 'cloud' $method Method to use for the transaction. For cloud device transactions, the method is `cloud`.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?bool $saveIfSuccess
     */
    #[JsonProperty('saveIfSuccess')]
    public ?bool $saveIfSuccess;

    /**
     * @param array{
     *   method: 'cloud',
     *   device?: ?string,
     *   saveIfSuccess?: ?bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->device = $values['device'] ?? null;
        $this->method = $values['method'];
        $this->saveIfSuccess = $values['saveIfSuccess'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
