<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * Information about the point of interaction device (also known as a terminal or cloud device) used to process the transaction.
 */
class PoiDevice extends JsonSerializableType
{
    /**
     * @var ?bool $connected The device connection status.
     */
    #[JsonProperty('connected')]
    public ?bool $connected;

    /**
     * @var ?DateTime $dateDeRegistered The date the device was unregistered.
     */
    #[JsonProperty('dateDeRegistered'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $dateDeRegistered;

    /**
     * @var ?DateTime $dateRegistered The date the device was registered.
     */
    #[JsonProperty('dateRegistered'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $dateRegistered;

    /**
     * @var ?string $deviceId The device identifier.
     */
    #[JsonProperty('deviceId')]
    public ?string $deviceId;

    /**
     * @var ?string $deviceLicense Device license. This is typically the same as `deviceId`.
     */
    #[JsonProperty('deviceLicense')]
    public ?string $deviceLicense;

    /**
     * @var ?string $deviceNickName Device description provided during registration.
     */
    #[JsonProperty('deviceNickName')]
    public ?string $deviceNickName;

    /**
     * @var ?DateTime $lastConnectedDate Last connected date.
     */
    #[JsonProperty('lastConnectedDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastConnectedDate;

    /**
     * @var ?DateTime $lastDisconnectedDate Last disconnected date.
     */
    #[JsonProperty('lastDisconnectedDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastDisconnectedDate;

    /**
     * @var ?DateTime $lastTransactionDate Last transaction date.
     */
    #[JsonProperty('lastTransactionDate'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastTransactionDate;

    /**
     * @var ?string $make The device manufacturer.
     */
    #[JsonProperty('make')]
    public ?string $make;

    /**
     * @var ?string $model The device model.
     */
    #[JsonProperty('model')]
    public ?string $model;

    /**
     * @var ?bool $registered The device registration status.
     */
    #[JsonProperty('registered')]
    public ?bool $registered;

    /**
     * @var ?string $serialNumber The device serial number.
     */
    #[JsonProperty('serialNumber')]
    public ?string $serialNumber;

    /**
     * @param array{
     *   connected?: ?bool,
     *   dateDeRegistered?: ?DateTime,
     *   dateRegistered?: ?DateTime,
     *   deviceId?: ?string,
     *   deviceLicense?: ?string,
     *   deviceNickName?: ?string,
     *   lastConnectedDate?: ?DateTime,
     *   lastDisconnectedDate?: ?DateTime,
     *   lastTransactionDate?: ?DateTime,
     *   make?: ?string,
     *   model?: ?string,
     *   registered?: ?bool,
     *   serialNumber?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->connected = $values['connected'] ?? null;
        $this->dateDeRegistered = $values['dateDeRegistered'] ?? null;
        $this->dateRegistered = $values['dateRegistered'] ?? null;
        $this->deviceId = $values['deviceId'] ?? null;
        $this->deviceLicense = $values['deviceLicense'] ?? null;
        $this->deviceNickName = $values['deviceNickName'] ?? null;
        $this->lastConnectedDate = $values['lastConnectedDate'] ?? null;
        $this->lastDisconnectedDate = $values['lastDisconnectedDate'] ?? null;
        $this->lastTransactionDate = $values['lastTransactionDate'] ?? null;
        $this->make = $values['make'] ?? null;
        $this->model = $values['model'] ?? null;
        $this->registered = $values['registered'] ?? null;
        $this->serialNumber = $values['serialNumber'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
