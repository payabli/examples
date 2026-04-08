<?php

namespace Payabli\MoneyIn\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\MoneyIn\Types\TransRequestBody;

class RequestPaymentAuthorize extends JsonSerializableType
{
    /**
     * @var ?bool $forceCustomerCreation
     */
    public ?bool $forceCustomerCreation;

    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var TransRequestBody $body
     */
    public TransRequestBody $body;

    /**
     * @param array{
     *   body: TransRequestBody,
     *   forceCustomerCreation?: ?bool,
     *   idempotencyKey?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->forceCustomerCreation = $values['forceCustomerCreation'] ?? null;
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->body = $values['body'];
    }
}
