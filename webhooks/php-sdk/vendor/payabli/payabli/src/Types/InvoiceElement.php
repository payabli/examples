<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class InvoiceElement extends JsonSerializableType
{
    /**
     * @var ?bool $enabled
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?LabelElement $invoiceLink Link to invoice
     */
    #[JsonProperty('invoiceLink')]
    public ?LabelElement $invoiceLink;

    /**
     * @var ?int $order
     */
    #[JsonProperty('order')]
    public ?int $order;

    /**
     * @var ?LabelElement $viewInvoiceDetails Link to view invoice details
     */
    #[JsonProperty('viewInvoiceDetails')]
    public ?LabelElement $viewInvoiceDetails;

    /**
     * @param array{
     *   enabled?: ?bool,
     *   invoiceLink?: ?LabelElement,
     *   order?: ?int,
     *   viewInvoiceDetails?: ?LabelElement,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->enabled = $values['enabled'] ?? null;
        $this->invoiceLink = $values['invoiceLink'] ?? null;
        $this->order = $values['order'] ?? null;
        $this->viewInvoiceDetails = $values['viewInvoiceDetails'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
