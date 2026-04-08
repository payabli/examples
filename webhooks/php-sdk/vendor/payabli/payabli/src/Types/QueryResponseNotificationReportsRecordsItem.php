<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use DateTime;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Date;

class QueryResponseNotificationReportsRecordsItem extends JsonSerializableType
{
    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?int $id Unique identifier for the report.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?bool $isDownloadable Indicator of whether the report can be downloaded.
     */
    #[JsonProperty('isDownloadable')]
    public ?bool $isDownloadable;

    /**
     * @var ?string $reportName Name of the report.
     */
    #[JsonProperty('reportName')]
    public ?string $reportName;

    /**
     * @param array{
     *   createdAt?: ?DateTime,
     *   id?: ?int,
     *   isDownloadable?: ?bool,
     *   reportName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->createdAt = $values['createdAt'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->isDownloadable = $values['isDownloadable'] ?? null;
        $this->reportName = $values['reportName'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
