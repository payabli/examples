<?php

namespace Payabli\Bill\Requests;

use Payabli\Core\Json\JsonSerializableType;

class SendToApprovalBillRequest extends JsonSerializableType
{
    /**
     * @var ?bool $autocreateUser Automatically create the target user for approval if they don't exist.
     */
    public ?bool $autocreateUser;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var array<string> $body
     */
    public array $body;

    /**
     * @param array{
     *   body: array<string>,
     *   autocreateUser?: ?bool,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->autocreateUser = $values['autocreateUser'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
