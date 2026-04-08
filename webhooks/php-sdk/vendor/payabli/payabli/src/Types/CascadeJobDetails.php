<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;

/**
 * Details about the cascade process.
 */
class CascadeJobDetails extends JsonSerializableType
{
    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $jobErrorMessage Error message for a failed cascade process.
     */
    #[JsonProperty('jobErrorMessage')]
    public ?string $jobErrorMessage;

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
     * @var ?DateTime $updatedAt
     */
    #[JsonProperty('updatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $updatedAt;

    /**
     * @param array{
     *   createdAt?: ?DateTime,
     *   jobErrorMessage?: ?string,
     *   jobId?: ?string,
     *   jobStatus?: ?string,
     *   updatedAt?: ?DateTime,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->createdAt = $values['createdAt'] ?? null;
        $this->jobErrorMessage = $values['jobErrorMessage'] ?? null;
        $this->jobId = $values['jobId'] ?? null;
        $this->jobStatus = $values['jobStatus'] ?? null;
        $this->updatedAt = $values['updatedAt'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
