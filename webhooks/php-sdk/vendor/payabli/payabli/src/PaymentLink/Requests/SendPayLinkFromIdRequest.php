<?php

namespace Payabli\PaymentLink\Requests;

use Payabli\Core\Json\JsonSerializableType;

class SendPayLinkFromIdRequest extends JsonSerializableType
{
    /**
     * @var ?bool $attachfile When `true`, attaches a PDF version of invoice to the email.
     */
    public ?bool $attachfile;

    /**
     * @var ?string $mail2 List of recipient email addresses. When there is more than one, separate them by a semicolon (;).
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
