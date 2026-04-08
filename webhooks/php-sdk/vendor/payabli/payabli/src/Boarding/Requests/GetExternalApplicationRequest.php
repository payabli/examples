<?php

namespace Payabli\Boarding\Requests;

use Payabli\Core\Json\JsonSerializableType;

class GetExternalApplicationRequest extends JsonSerializableType
{
    /**
     * @var ?bool $sendEmail If `true`, sends an email that includes the link to the application to the `mail2` address. Defaults to `false`.
     */
    public ?bool $sendEmail;

    /**
     * @param array{
     *   sendEmail?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->sendEmail = $values['sendEmail'] ?? null;
    }
}
