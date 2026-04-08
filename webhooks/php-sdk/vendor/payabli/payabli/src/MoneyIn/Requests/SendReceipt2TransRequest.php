<?php

namespace Payabli\MoneyIn\Requests;

use Payabli\Core\Json\JsonSerializableType;

class SendReceipt2TransRequest extends JsonSerializableType
{
    /**
     * Email address where the payment receipt should be sent.
     *
     * If not provided, the email address on file for the user owner of the transaction is used.
     *
     * @var ?string $email
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
