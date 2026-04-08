<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;

class ApplePayOrganizationUpdateData extends JsonSerializableType
{
    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $id Internal ID for the Apple Pay organization update.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $jobId
     */
    #[JsonProperty('jobId')]
    public ?string $jobId;

    /**
     * @var ?string $jobStatus
     */
    #[JsonProperty('jobStatus')]
    public ?string $jobStatus;

    /**
     * @var ?int $organizationId
     */
    #[JsonProperty('organizationId')]
    public ?int $organizationId;

    /**
     * @var ?string $type The record type, in this context it will always be `ApplePayOrganizationUpdate`.
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ?DateTime $updatedAt
     */
    #[JsonProperty('updatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $updatedAt;

    /**
     * @var ?OrganizationUpdates $updates
     */
    #[JsonProperty('updates')]
    public ?OrganizationUpdates $updates;

    /**
     * @param array{
     *   createdAt?: ?DateTime,
     *   id?: ?string,
     *   jobId?: ?string,
     *   jobStatus?: ?string,
     *   organizationId?: ?int,
     *   type?: ?string,
     *   updatedAt?: ?DateTime,
     *   updates?: ?OrganizationUpdates,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->createdAt = $values['createdAt'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->jobId = $values['jobId'] ?? null;
        $this->jobStatus = $values['jobStatus'] ?? null;
        $this->organizationId = $values['organizationId'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->updatedAt = $values['updatedAt'] ?? null;
        $this->updates = $values['updates'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
