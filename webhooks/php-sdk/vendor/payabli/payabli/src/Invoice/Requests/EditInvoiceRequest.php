<?php

namespace Payabli\Invoice\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Invoice\Types\InvoiceDataRequest;

class EditInvoiceRequest extends JsonSerializableType
{
    /**
     * @var ?bool $forceCustomerCreation When `true`, the request creates a new customer record, regardless of whether customer identifiers match an existing customer.
     */
    public ?bool $forceCustomerCreation;

    /**
     * @var InvoiceDataRequest $body
     */
    public InvoiceDataRequest $body;

    /**
     * @param array{
     *   body: InvoiceDataRequest,
     *   forceCustomerCreation?: ?bool,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->forceCustomerCreation = $values['forceCustomerCreation'] ?? null;
        $this->body = $values['body'];
    }
}
