<?php

namespace Payabli\HostedPaymentPages\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\PayabliPages;

class NewPageRequest extends JsonSerializableType
{
    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var PayabliPages $body
     */
    public PayabliPages $body;

    /**
     * @param array{
     *   body: PayabliPages,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
