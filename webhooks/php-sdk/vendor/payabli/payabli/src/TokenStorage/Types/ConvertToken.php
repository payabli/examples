<?php

namespace Payabli\TokenStorage\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Object containing the information needed to convert a temporary token to a permanent token.
 */
class ConvertToken extends JsonSerializableType
{
    /**
     * @var string $method The type of payment method to tokenize. When converting a temp token to a permanent token, this should match the `method` set for the temporary token, either `ach` or `card`.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var string $tokenId A temporary stored token ID to be converted to permanent.
     */
    #[JsonProperty('tokenId')]
    public string $tokenId;

    /**
     * @param array{
     *   method: string,
     *   tokenId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->method = $values['method'];
        $this->tokenId = $values['tokenId'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
