<?php

namespace Payabli\Invoice\Requests;

use Payabli\Core\Json\JsonSerializableType;

class SendInvoiceRequest extends JsonSerializableType
{
    /**
     * @var ?bool $attachfile When `true`, attaches a PDF version of invoice to the email.
     */
    public ?bool $attachfile;

    /**
     * @var ?string $mail2 Email address where the invoice will be sent to. If this parameter isn't included, Payabli uses the email address on file for the customer owner of the invoice.
     */
    public ?string $mail2;

    /**
     * @param array{
     *   attachfile?: ?bool,
     *   mail2?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->attachfile = $values['attachfile'] ?? null;
        $this->mail2 = $values['mail2'] ?? null;
    }
}
