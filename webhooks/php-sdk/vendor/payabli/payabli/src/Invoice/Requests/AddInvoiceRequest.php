<?php

namespace Payabli\Invoice\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Invoice\Types\InvoiceDataRequest;

class AddInvoiceRequest extends JsonSerializableType
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
     * @var InvoiceDataRequest $body
     */
    public InvoiceDataRequest $body;

    /**
     * @param array{
     *   body: InvoiceDataRequest,
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
