<?php

namespace Payabli\Paypoint\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class NotificationRequest extends JsonSerializableType
{
    /**
     * @var string $notificationUrl Complete HTTP URL receiving the notification
     */
    #[JsonProperty('notificationUrl')]
    public string $notificationUrl;

    /**
     * @var ?array<WebHeaderParameter> $webHeaderParameters A dictionary of key-value pairs to be inserted in the header when the notification request is submitted
     */
    #[JsonProperty('webHeaderParameters'), ArrayType([WebHeaderParameter::class])]
    public ?array $webHeaderParameters;

    /**
     * @param array{
     *   notificationUrl: string,
     *   webHeaderParameters?: ?array<WebHeaderParameter>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->notificationUrl = $values['notificationUrl'];
        $this->webHeaderParameters = $values['webHeaderParameters'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
