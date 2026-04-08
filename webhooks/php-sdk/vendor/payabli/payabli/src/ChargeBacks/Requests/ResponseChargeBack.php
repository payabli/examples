<?php

namespace Payabli\ChargeBacks\Requests;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\FileContent;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class ResponseChargeBack extends JsonSerializableType
{
    /**
     * @var ?string $idempotencyKey
     */
    public ?string $idempotencyKey;

    /**
     * @var ?array<FileContent> $attachments Array of attached files to response.
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

    /**
     * @var ?string $contactEmail Email of response submitter.
     */
    #[JsonProperty('contactEmail')]
    public ?string $contactEmail;

    /**
     * @var ?string $contactName Name of response submitter
     */
    #[JsonProperty('contactName')]
    public ?string $contactName;

    /**
     * @var ?string $notes Response notes
     */
    #[JsonProperty('notes')]
    public ?string $notes;

    /**
     * @param array{
     *   idempotencyKey?: ?string,
     *   attachments?: ?array<FileContent>,
     *   contactEmail?: ?string,
     *   contactName?: ?string,
     *   notes?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->idempotencyKey = $values['idempotencyKey'] ?? null;
        $this->attachments = $values['attachments'] ?? null;
        $this->contactEmail = $values['contactEmail'] ?? null;
        $this->contactName = $values['contactName'] ?? null;
        $this->notes = $values['notes'] ?? null;
    }
}
