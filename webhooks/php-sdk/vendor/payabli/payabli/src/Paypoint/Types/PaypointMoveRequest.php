<?php

namespace Payabli\Paypoint\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PaypointMoveRequest extends JsonSerializableType
{
    /**
     * @var string $entryPoint
     */
    #[JsonProperty('entryPoint')]
    public string $entryPoint;

    /**
     * @var int $newParentOrganizationId The ID for the paypoint's new parent organization.
     */
    #[JsonProperty('newParentOrganizationId')]
    public int $newParentOrganizationId;

    /**
     * @var ?NotificationRequest $notificationRequest Optional notification request object for a webhook
     */
    #[JsonProperty('notificationRequest')]
    public ?NotificationRequest $notificationRequest;

    /**
     * @param array{
     *   entryPoint: string,
     *   newParentOrganizationId: int,
     *   notificationRequest?: ?NotificationRequest,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->entryPoint = $values['entryPoint'];
        $this->newParentOrganizationId = $values['newParentOrganizationId'];
        $this->notificationRequest = $values['notificationRequest'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
