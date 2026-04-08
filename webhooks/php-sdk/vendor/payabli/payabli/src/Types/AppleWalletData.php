<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * The wallet data.
 */
class AppleWalletData extends JsonSerializableType
{
    /**
     * @var ?string $entry
     */
    #[JsonProperty('entry')]
    public ?string $entry;

    /**
     * @var ?string $applePayMerchantId The Apple Pay merchant identifier.
     */
    #[JsonProperty('applePayMerchantId')]
    public ?string $applePayMerchantId;

    /**
     * @var ?array<string> $domainNames A list of domain names that are enabled for this paypoint.
     */
    #[JsonProperty('domainNames'), ArrayType(['string'])]
    public ?array $domainNames;

    /**
     * @var ?string $paypointName
     */
    #[JsonProperty('paypointName')]
    public ?string $paypointName;

    /**
     * @var ?string $paypointUrl The paypoint URL.
     */
    #[JsonProperty('paypointUrl')]
    public ?string $paypointUrl;

    /**
     * @var ?DateTime $markedForDeletionAt The date and time a paypoint's Apple Pay registration was scheduled for deletion. The paypoint will be unregistered from Apple Pay permanently 30 days from this value.
     */
    #[JsonProperty('markedForDeletionAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $markedForDeletionAt;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?DateTime $updatedAt
     */
    #[JsonProperty('updatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $updatedAt;

    /**
     * @var ?string $id Internal ID for the Apple Pay paypoint registration update.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $type The record type, in this context it will always be `ApplePayRegistration`.
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @param array{
     *   entry?: ?string,
     *   applePayMerchantId?: ?string,
     *   domainNames?: ?array<string>,
     *   paypointName?: ?string,
     *   paypointUrl?: ?string,
     *   markedForDeletionAt?: ?DateTime,
     *   createdAt?: ?DateTime,
     *   updatedAt?: ?DateTime,
     *   id?: ?string,
     *   type?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entry = $values['entry'] ?? null;
        $this->applePayMerchantId = $values['applePayMerchantId'] ?? null;
        $this->domainNames = $values['domainNames'] ?? null;
        $this->paypointName = $values['paypointName'] ?? null;
        $this->paypointUrl = $values['paypointUrl'] ?? null;
        $this->markedForDeletionAt = $values['markedForDeletionAt'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->updatedAt = $values['updatedAt'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->type = $values['type'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
