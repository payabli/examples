<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class PushPayLinkRequestEmail extends JsonSerializableType
{
    /**
     * List of additional email addresses you want to send the paylink to, formatted as an array.
     * Payment links and opt-in requests are sent to the customer email address on file, and additional
     * recipients can be specified here.
     *
     * @var ?array<string> $additionalEmails
     */
    #[JsonProperty('additionalEmails'), ArrayType(['string'])]
    public ?array $additionalEmails;

    /**
     * @var ?bool $attachFile When `true`, attaches a PDF version of the invoice to the email.
     */
    #[JsonProperty('attachFile')]
    public ?bool $attachFile;

    /**
     * @param array{
     *   additionalEmails?: ?array<string>,
     *   attachFile?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->additionalEmails = $values['additionalEmails'] ?? null;
        $this->attachFile = $values['attachFile'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
