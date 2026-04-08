<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * Information about the standard notification configuration (email, sms, web).
 */
class NotificationStandardRequest extends JsonSerializableType
{
    /**
     * @var ?NotificationStandardRequestContent $content
     */
    #[JsonProperty('content')]
    public ?NotificationStandardRequestContent $content;

    /**
     * @var value-of<NotificationStandardRequestFrequency> $frequency
     */
    #[JsonProperty('frequency')]
    public string $frequency;

    /**
     * @var value-of<NotificationStandardRequestMethod> $method Get near-instant notifications via email, SMS, or webhooks for important events like new payment disputes, merchant activations, fraud alerts, approved transactions, settlement history, vendor payouts, and more. Use webhooks with notifications to get real-time updates and automate operations based on key those key events. See [Notifications](/developers/developer-guides/notifications-and-webhooks-overview#notifications) for more.
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
     * - For method=email the expected value is a list of email addresses separated by semicolon.
     * - For method=sms the expected value is a list of phone numbers separated by semicolon.
     * - For method=web the expected value is a valid and complete URL. Webhooks support only standard HTTP ports: 80, 443, 8080, or 4443.
     *
     * @var string $target
     */
    #[JsonProperty('target')]
    public string $target;

    /**
     * @param array{
     *   frequency: value-of<NotificationStandardRequestFrequency>,
     *   method: value-of<NotificationStandardRequestMethod>,
     *   ownerType: int,
     *   target: string,
     *   content?: ?NotificationStandardRequestContent,
     *   ownerId?: ?string,
     *   status?: ?int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->content = $values['content'] ?? null;
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
