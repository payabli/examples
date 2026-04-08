<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class OrganizationQueryRecord extends JsonSerializableType
{
    /**
     * @var ?array<OrganizationQueryRecordServicesItem> $services
     */
    #[JsonProperty('services'), ArrayType([OrganizationQueryRecordServicesItem::class])]
    public ?array $services;

    /**
     * @var ?Instrument $billingInfo
     */
    #[JsonProperty('billingInfo')]
    public ?Instrument $billingInfo;

    /**
     * @var ?array<Contacts> $contacts
     */
    #[JsonProperty('contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?bool $hasBilling
     */
    #[JsonProperty('hasBilling')]
    public ?bool $hasBilling;

    /**
     * @var ?bool $hasResidual
     */
    #[JsonProperty('hasResidual')]
    public ?bool $hasResidual;

    /**
     * @var ?int $idOrg
     */
    #[JsonProperty('idOrg')]
    public ?int $idOrg;

    /**
     * @var ?bool $isRoot
     */
    #[JsonProperty('isRoot')]
    public ?bool $isRoot;

    /**
     * @var ?string $orgAddress
     */
    #[JsonProperty('orgAddress')]
    public ?string $orgAddress;

    /**
     * @var ?string $orgCity
     */
    #[JsonProperty('orgCity')]
    public ?string $orgCity;

    /**
     * @var ?string $orgCountry
     */
    #[JsonProperty('orgCountry')]
    public ?string $orgCountry;

    /**
     * @var ?string $orgEntryName
     */
    #[JsonProperty('orgEntryName')]
    public ?string $orgEntryName;

    /**
     * @var ?string $orgId
     */
    #[JsonProperty('orgId')]
    public ?string $orgId;

    /**
     * @var ?FileContent $orgLogo
     */
    #[JsonProperty('orgLogo')]
    public ?FileContent $orgLogo;

    /**
     * @var ?string $orgName
     */
    #[JsonProperty('orgName')]
    public ?string $orgName;

    /**
     * @var ?int $orgParentId
     */
    #[JsonProperty('orgParentId')]
    public ?int $orgParentId;

    /**
     * @var ?string $orgParentName
     */
    #[JsonProperty('orgParentName')]
    public ?string $orgParentName;

    /**
     * @var ?string $orgState
     */
    #[JsonProperty('orgState')]
    public ?string $orgState;

    /**
     * @var ?int $orgTimezone
     */
    #[JsonProperty('orgTimezone')]
    public ?int $orgTimezone;

    /**
     * @var ?int $orgType
     */
    #[JsonProperty('orgType')]
    public ?int $orgType;

    /**
     * @var ?string $orgWebsite
     */
    #[JsonProperty('orgWebsite')]
    public ?string $orgWebsite;

    /**
     * @var ?string $orgZip
     */
    #[JsonProperty('orgZip')]
    public ?string $orgZip;

    /**
     * @var ?bool $recipientEmailNotification
     */
    #[JsonProperty('recipientEmailNotification')]
    public ?bool $recipientEmailNotification;

    /**
     * @var ?string $replyToEmail
     */
    #[JsonProperty('replyToEmail')]
    public ?string $replyToEmail;

    /**
     * @var ?bool $resumable
     */
    #[JsonProperty('resumable')]
    public ?bool $resumable;

    /**
     * @var ?SummaryOrg $summary
     */
    #[JsonProperty('summary')]
    public ?SummaryOrg $summary;

    /**
     * @var ?array<UserQueryRecord> $users
     */
    #[JsonProperty('users'), ArrayType([UserQueryRecord::class])]
    public ?array $users;

    /**
     * @param array{
     *   services?: ?array<OrganizationQueryRecordServicesItem>,
     *   billingInfo?: ?Instrument,
     *   contacts?: ?array<Contacts>,
     *   createdAt?: ?DateTime,
     *   hasBilling?: ?bool,
     *   hasResidual?: ?bool,
     *   idOrg?: ?int,
     *   isRoot?: ?bool,
     *   orgAddress?: ?string,
     *   orgCity?: ?string,
     *   orgCountry?: ?string,
     *   orgEntryName?: ?string,
     *   orgId?: ?string,
     *   orgLogo?: ?FileContent,
     *   orgName?: ?string,
     *   orgParentId?: ?int,
     *   orgParentName?: ?string,
     *   orgState?: ?string,
     *   orgTimezone?: ?int,
     *   orgType?: ?int,
     *   orgWebsite?: ?string,
     *   orgZip?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   replyToEmail?: ?string,
     *   resumable?: ?bool,
     *   summary?: ?SummaryOrg,
     *   users?: ?array<UserQueryRecord>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->services = $values['services'] ?? null;
        $this->billingInfo = $values['billingInfo'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->hasBilling = $values['hasBilling'] ?? null;
        $this->hasResidual = $values['hasResidual'] ?? null;
        $this->idOrg = $values['idOrg'] ?? null;
        $this->isRoot = $values['isRoot'] ?? null;
        $this->orgAddress = $values['orgAddress'] ?? null;
        $this->orgCity = $values['orgCity'] ?? null;
        $this->orgCountry = $values['orgCountry'] ?? null;
        $this->orgEntryName = $values['orgEntryName'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
        $this->orgLogo = $values['orgLogo'] ?? null;
        $this->orgName = $values['orgName'] ?? null;
        $this->orgParentId = $values['orgParentId'] ?? null;
        $this->orgParentName = $values['orgParentName'] ?? null;
        $this->orgState = $values['orgState'] ?? null;
        $this->orgTimezone = $values['orgTimezone'] ?? null;
        $this->orgType = $values['orgType'] ?? null;
        $this->orgWebsite = $values['orgWebsite'] ?? null;
        $this->orgZip = $values['orgZip'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->replyToEmail = $values['replyToEmail'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
        $this->summary = $values['summary'] ?? null;
        $this->users = $values['users'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
