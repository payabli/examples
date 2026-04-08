<?php

namespace Payabli\Bill\Requests;

use Payabli\Core\Json\JsonSerializableType;

class SetApprovedBillRequest extends JsonSerializableType
{
    /**
     * @var ?string $email Email or username of user modifying approval status.
     */
    public ?string $email;

    /**
     * @param array{
     *   email?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->email = $values['email'] ?? null;
    }
}
