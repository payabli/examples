<?php

namespace Payabli\Invoice\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\PayorDataRequest;
use Payabli\Core\Json\JsonProperty;
use Payabli\Types\BillData;
use Payabli\Types\BillOptions;

class InvoiceDataRequest extends JsonSerializableType
{
    /**
     * @var ?PayorDataRequest $customerData Object describing the customer/payor. Required for POST requests. Which fields are required depends on the paypoint's custom identifier settings.
     */
    #[JsonProperty('customerData')]
    public ?PayorDataRequest $customerData;

    /**
     * @var ?BillData $invoiceData Object describing the invoice. Required for POST requests.
     */
    #[JsonProperty('invoiceData')]
    public ?BillData $invoiceData;

    /**
     * @var ?BillOptions $scheduledOptions Object with options for scheduled invoices.
     */
    #[JsonProperty('scheduledOptions')]
    public ?BillOptions $scheduledOptions;

    /**
     * @param array{
     *   customerData?: ?PayorDataRequest,
     *   invoiceData?: ?BillData,
     *   scheduledOptions?: ?BillOptions,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->customerData = $values['customerData'] ?? null;
        $this->invoiceData = $values['invoiceData'] ?? null;
        $this->scheduledOptions = $values['scheduledOptions'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
