<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class Check extends JsonSerializableType
{
    /**
     * @var string $achHolder The checking accountholder's name.
     */
    #[JsonProperty('achHolder')]
    public string $achHolder;

    /**
     * @var 'check' $method Method to use for the transaction. Use `check` for a paper check transaction. When the method is `check`, then `paymentDetails.checkNumber` is required.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @param array{
     *   achHolder: string,
     *   method: 'check',
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->achHolder = $values['achHolder'];
        $this->method = $values['method'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
