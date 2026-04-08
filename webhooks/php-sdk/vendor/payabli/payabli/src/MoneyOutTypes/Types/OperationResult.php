<?php

namespace Payabli\MoneyOutTypes\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class OperationResult extends JsonSerializableType
{
    /**
     * @var ?string $message Message describing the result. If the virtual card link was sent successfully, this contains the email address to which the link was sent.
     */
    #[JsonProperty('message')]
    public ?string $message;

    /**
     * @var bool $success Indicates whether the operation was successful.
     */
    #[JsonProperty('success')]
    public bool $success;

    /**
     * @param array{
     *   success: bool,
     *   message?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->message = $values['message'] ?? null;
        $this->success = $values['success'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
