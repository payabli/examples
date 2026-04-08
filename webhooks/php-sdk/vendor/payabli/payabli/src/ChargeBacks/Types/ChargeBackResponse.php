<?php

namespace Payabli\ChargeBacks\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Types\BoardingApplicationAttachments;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

class ChargeBackResponse extends JsonSerializableType
{
    /**
     * @var ?BoardingApplicationAttachments $attachments Object with attached files to response
     */
    #[JsonProperty('attachments')]
    public ?BoardingApplicationAttachments $attachments;

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
     * @var ?DateTime $createdAt Timestamp when response was submitted, in UTC.
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?int $id Chargeback response identifier
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $notes Response notes
     */
    #[JsonProperty('notes')]
    public ?string $notes;

    /**
     * @param array{
     *   attachments?: ?BoardingApplicationAttachments,
     *   contactEmail?: ?string,
     *   contactName?: ?string,
     *   createdAt?: ?DateTime,
     *   id?: ?int,
     *   notes?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->attachments = $values['attachments'] ?? null;
        $this->contactEmail = $values['contactEmail'] ?? null;
        $this->contactName = $values['contactName'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->notes = $values['notes'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
