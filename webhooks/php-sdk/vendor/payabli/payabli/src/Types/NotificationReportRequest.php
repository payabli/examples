<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Information about the report notification configuration (report-email, report-web).
 */
class NotificationReportRequest extends JsonSerializableType
{
    /**
     * @var NotificationReportRequestContent $content
     */
    #[JsonProperty('content')]
    public NotificationReportRequestContent $content;

    /**
     * @var value-of<NotificationReportRequestFrequency> $frequency
     */
    #[JsonProperty('frequency')]
    public string $frequency;

    /**
     * @var value-of<NotificationReportRequestMethod> $method Automated reporting lets you gather critical reports without manually filtering and exporting the data. Get automated daily, weekly, and monthly report for daily sales, ACH returns, settlements, and more. You can send these reports via email or via webhook. See [Automated Reports](/developers/developer-guides/notifications-and-webhooks-overview#automated-reports) for more.
     */
    #[JsonProperty('method')]
    public string $method;

    /**
     * @var ?string $ownerId
     */
    #[JsonProperty('ownerId')]
    public ?string $ownerId;

    /**
     * @var int $ownerType
     */
    #[JsonProperty('ownerType')]
    public int $ownerType;

    /**
     * @var ?int $status
     */
    #[JsonProperty('status')]
    public ?int $status;

    /**
     * Specify the notification target.
     *
     * For method=report-email the expected value is a list of email addresses separated by semicolon.
     *
     * For method=report-web the expected value is a valid and complete URL. Webhooks support only standard HTTP ports: 80, 443, 8080, or 4443.
     *
     * @var string $target
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @param array{
     *   content: NotificationReportRequestContent,
     *   frequency: value-of<NotificationReportRequestFrequency>,
     *   method: value-of<NotificationReportRequestMethod>,
     *   ownerType: int,
     *   target: string,
     *   ownerId?: ?string,
     *   status?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->content = $values['content'];
        $this->frequency = $values['frequency'];
        $this->method = $values['method'];
        $this->ownerId = $values['ownerId'] ?? null;
        $this->ownerType = $values['ownerType'];
        $this->status = $values['status'] ?? null;
        $this->target = $values['target'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
