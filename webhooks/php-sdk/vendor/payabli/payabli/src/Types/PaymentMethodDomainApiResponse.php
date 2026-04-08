<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

/**
 * Data related to the payment method domain.
 */
class PaymentMethodDomainApiResponse extends JsonSerializableType
{
    /**
     * @var ?string $type The record type. For payment method domains, this is always `PaymentMethodDomain`.
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ApplePayData $applePay
     */
    #[JsonProperty('applePay')]
    public ApplePayData $applePay;

    /**
     * @var GooglePayData $googlePay
     */
    #[JsonProperty('googlePay')]
    public GooglePayData $googlePay;

    /**
     * @var ?array<CascadeJobDetails> $cascades Data about the domain's cascade status.
     */
    #[JsonProperty('cascades'), ArrayType([CascadeJobDetails::class])]
    public ?array $cascades;

    /**
     * @var DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public DateTime $createdAt;

    /**
     * @var string $domainName
     */
    #[JsonProperty('domainName')]
    public string $domainName;

    /**
     * @var int $entityId
     */
    #[JsonProperty('entityId')]
    public int $entityId;

    /**
     * @var string $entityType
     */
    #[JsonProperty('entityType')]
    public string $entityType;

    /**
     * @var string $id
     */
    #[JsonProperty('id')]
    public string $id;

    /**
     * @var int $ownerEntityId
     */
    #[JsonProperty('ownerEntityId')]
    public int $ownerEntityId;

    /**
     * @var string $ownerEntityType
     */
    #[JsonProperty('ownerEntityType')]
    public string $ownerEntityType;

    /**
     * @var ?DateTime $updatedAt
     */
    #[JsonProperty('updatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $updatedAt;

    /**
     * @param array{
     *   applePay: ApplePayData,
     *   googlePay: GooglePayData,
     *   createdAt: DateTime,
     *   domainName: string,
     *   entityId: int,
     *   entityType: string,
     *   id: string,
     *   ownerEntityId: int,
     *   ownerEntityType: string,
     *   type?: ?string,
     *   cascades?: ?array<CascadeJobDetails>,
     *   updatedAt?: ?DateTime,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->type = $values['type'] ?? null;
        $this->applePay = $values['applePay'];
        $this->googlePay = $values['googlePay'];
        $this->cascades = $values['cascades'] ?? null;
        $this->createdAt = $values['createdAt'];
        $this->domainName = $values['domainName'];
        $this->entityId = $values['entityId'];
        $this->entityType = $values['entityType'];
        $this->id = $values['id'];
        $this->ownerEntityId = $values['ownerEntityId'];
        $this->ownerEntityType = $values['ownerEntityType'];
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
