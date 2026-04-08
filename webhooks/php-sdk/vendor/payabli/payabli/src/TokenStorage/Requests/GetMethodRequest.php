<?php

namespace Payabli\TokenStorage\Requests;

use Payabli\Core\Json\JsonSerializableType;

class GetMethodRequest extends JsonSerializableType
{
    /**
     * Format for card expiration dates in the response.
     *
     * Accepted values:
     *
     * - 0: default, no formatting. Expiration dates are returned in the format they're saved in.
     *
     * - 1: MMYY
     *
     * - 2: MM/YY
     *
     * @var ?int $cardExpirationFormat
     */
    public ?int $cardExpirationFormat;

    /**
     * @var ?bool $includeTemporary When `true`, the request will include temporary tokens in the search and return details for a matching temporary token. The default behavior searches only for permanent tokens.
     */
    public ?bool $includeTemporary;

    /**
     * @param array{
     *   cardExpirationFormat?: ?int,
     *   includeTemporary?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->cardExpirationFormat = $values['cardExpirationFormat'] ?? null;
        $this->includeTemporary = $values['includeTemporary'] ?? null;
    }
}
