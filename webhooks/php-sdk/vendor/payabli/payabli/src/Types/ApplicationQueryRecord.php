<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class ApplicationQueryRecord extends JsonSerializableType
{
    /**
     * @var ?float $annualRevenue
     */
    #[JsonProperty('annualRevenue')]
    public ?float $annualRevenue;

    /**
     * @var ?float $averageMonthlyVolume
     */
    #[JsonProperty('averageMonthlyVolume')]
    public ?float $averageMonthlyVolume;

    /**
     * @var ?float $averageTicketAmount
     */
    #[JsonProperty('averageTicketAmount')]
    public ?float $averageTicketAmount;

    /**
     * @var ?string $bAddress1
     */
    #[JsonProperty('bAddress1')]
    public ?string $bAddress1;

    /**
     * @var ?string $bAddress2
     */
    #[JsonProperty('bAddress2')]
    public ?string $bAddress2;

    /**
     * @var ?array<Bank> $bankData
     */
    #[JsonProperty('bankData'), ArrayType([Bank::class])]
    public ?array $bankData;

    /**
     * @var ?string $bCity
     */
    #[JsonProperty('bCity')]
    public ?string $bCity;

    /**
     * @var ?string $bCountry
     */
    #[JsonProperty('bCountry')]
    public ?string $bCountry;

    /**
     * @var ?string $bFax The business's fax number.
     */
    #[JsonProperty('bFax')]
    public ?string $bFax;

    /**
     * @var ?int $binPerson
     */
    #[JsonProperty('binPerson')]
    public ?int $binPerson;

    /**
     * @var ?int $binPhone
     */
    #[JsonProperty('binPhone')]
    public ?int $binPhone;

    /**
     * @var ?int $binWeb
     */
    #[JsonProperty('binWeb')]
    public ?int $binWeb;

    /**
     * @var ?int $boardingLinkId
     */
    #[JsonProperty('boardingLinkId')]
    public ?int $boardingLinkId;

    /**
     * @var ?int $boardingStatus
     */
    #[JsonProperty('boardingStatus')]
    public ?int $boardingStatus;

    /**
     * @var ?int $boardingSubStatus
     */
    #[JsonProperty('boardingSubStatus')]
    public ?int $boardingSubStatus;

    /**
     * @var ?string $bPhone
     */
    #[JsonProperty('bPhone')]
    public ?string $bPhone;

    /**
     * @var ?string $bStartdate
     */
    #[JsonProperty('bStartdate')]
    public ?string $bStartdate;

    /**
     * @var ?string $bState
     */
    #[JsonProperty('bState')]
    public ?string $bState;

    /**
     * @var ?string $bSummary
     */
    #[JsonProperty('bSummary')]
    public ?string $bSummary;

    /**
     * @var ?BuilderData $builderData
     */
    #[JsonProperty('builderData')]
    public ?BuilderData $builderData;

    /**
     * @var ?string $bZip
     */
    #[JsonProperty('bZip')]
    public ?string $bZip;

    /**
     * @var ?array<Contacts> $contactData
     */
    #[JsonProperty('contactData'), ArrayType([Contacts::class])]
    public ?array $contactData;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $dbaName
     */
    #[JsonProperty('dbaName')]
    public ?string $dbaName;

    /**
     * @var ?BoardingApplicationAttachments $documentsRef
     */
    #[JsonProperty('documentsRef')]
    public ?BoardingApplicationAttachments $documentsRef;

    /**
     * @var ?string $ein
     */
    #[JsonProperty('ein')]
    public ?string $ein;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointId')]
    public ?string $externalPaypointId;

    /**
     * @var ?array<GeneralEvents> $generalEvents Events associated with the application.
     */
    #[JsonProperty('generalEvents'), ArrayType([GeneralEvents::class])]
    public ?array $generalEvents;

    /**
     * @var ?float $highTicketAmount
     */
    #[JsonProperty('highTicketAmount')]
    public ?float $highTicketAmount;

    /**
     * @var ?int $idApplication
     */
    #[JsonProperty('idApplication')]
    public ?int $idApplication;

    /**
     * @var ?DateTime $lastModified
     */
    #[JsonProperty('lastModified'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastModified;

    /**
     * @var ?string $legalName
     */
    #[JsonProperty('legalName')]
    public ?string $legalName;

    /**
     * @var ?string $license
     */
    #[JsonProperty('license')]
    public ?string $license;

    /**
     * @var ?string $licenseState
     */
    #[JsonProperty('licenseState')]
    public ?string $licenseState;

    /**
     * @var ?FileContent $logo Object containing logo file.
     */
    #[JsonProperty('logo')]
    public ?FileContent $logo;

    /**
     * @var ?string $mAddress1
     */
    #[JsonProperty('mAddress1')]
    public ?string $mAddress1;

    /**
     * @var ?string $mAddress2
     */
    #[JsonProperty('mAddress2')]
    public ?string $mAddress2;

    /**
     * @var ?string $mccid
     */
    #[JsonProperty('mccid')]
    public ?string $mccid;

    /**
     * @var ?string $mCity
     */
    #[JsonProperty('mCity')]
    public ?string $mCity;

    /**
     * @var ?string $mCountry
     */
    #[JsonProperty('mCountry')]
    public ?string $mCountry;

    /**
     * @var ?string $mState
     */
    #[JsonProperty('mState')]
    public ?string $mState;

    /**
     * @var ?string $mZip
     */
    #[JsonProperty('mZip')]
    public ?string $mZip;

    /**
     * @var ?int $orgId
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?string $orgParentName
     */
    #[JsonProperty('orgParentName')]
    public ?string $orgParentName;

    /**
     * @var ?array<Owners> $ownerData
     */
    #[JsonProperty('ownerData'), ArrayType([Owners::class])]
    public ?array $ownerData;

    /**
     * @var ?value-of<OwnType> $ownType
     */
    #[JsonProperty('ownType')]
    public ?string $ownType;

    /**
     * @var ?string $pageidentifier
     */
    #[JsonProperty('pageidentifier')]
    public ?string $pageidentifier;

    /**
     * @var ?bool $recipientEmailNotification
     */
    #[JsonProperty('recipientEmailNotification')]
    public ?bool $recipientEmailNotification;

    /**
     * @var ?bool $resumable
     */
    #[JsonProperty('resumable')]
    public ?bool $resumable;

    /**
     * @var ?string $salesCode
     */
    #[JsonProperty('salesCode')]
    public ?string $salesCode;

    /**
     * @var ?Services $serviceData
     */
    #[JsonProperty('serviceData')]
    public ?Services $serviceData;

    /**
     * @var ?SignerData $signer
     */
    #[JsonProperty('signer')]
    public ?SignerData $signer;

    /**
     * @var ?string $taxfillname
     */
    #[JsonProperty('taxfillname')]
    public ?string $taxfillname;

    /**
     * @var ?int $templateId
     */
    #[JsonProperty('templateId')]
    public ?int $templateId;

    /**
     * @var ?string $websiteAddress
     */
    #[JsonProperty('websiteAddress')]
    public ?string $websiteAddress;

    /**
     * @var ?value-of<Whencharged> $whencharged
     */
    #[JsonProperty('whencharged')]
    public ?string $whencharged;

    /**
     * @var ?value-of<Whendelivered> $whendelivered
     */
    #[JsonProperty('whendelivered')]
    public ?string $whendelivered;

    /**
     * @var ?value-of<Whenprovided> $whenProvided
     */
    #[JsonProperty('whenProvided')]
    public ?string $whenProvided;

    /**
     * @var ?value-of<Whenrefunded> $whenrefund
     */
    #[JsonProperty('whenrefund')]
    public ?string $whenrefund;

    /**
     * @var ?string $additionalData
     */
    #[JsonProperty('additionalData')]
    public ?string $additionalData;

    /**
     * @var ?string $repCode
     */
    #[JsonProperty('RepCode')]
    public ?string $repCode;

    /**
     * @var ?string $repName
     */
    #[JsonProperty('RepName')]
    public ?string $repName;

    /**
     * @var ?string $repOffice
     */
    #[JsonProperty('RepOffice')]
    public ?string $repOffice;

    /**
     * @param array{
     *   annualRevenue?: ?float,
     *   averageMonthlyVolume?: ?float,
     *   averageTicketAmount?: ?float,
     *   bAddress1?: ?string,
     *   bAddress2?: ?string,
     *   bankData?: ?array<Bank>,
     *   bCity?: ?string,
     *   bCountry?: ?string,
     *   bFax?: ?string,
     *   binPerson?: ?int,
     *   binPhone?: ?int,
     *   binWeb?: ?int,
     *   boardingLinkId?: ?int,
     *   boardingStatus?: ?int,
     *   boardingSubStatus?: ?int,
     *   bPhone?: ?string,
     *   bStartdate?: ?string,
     *   bState?: ?string,
     *   bSummary?: ?string,
     *   builderData?: ?BuilderData,
     *   bZip?: ?string,
     *   contactData?: ?array<Contacts>,
     *   createdAt?: ?DateTime,
     *   dbaName?: ?string,
     *   documentsRef?: ?BoardingApplicationAttachments,
     *   ein?: ?string,
     *   externalPaypointId?: ?string,
     *   generalEvents?: ?array<GeneralEvents>,
     *   highTicketAmount?: ?float,
     *   idApplication?: ?int,
     *   lastModified?: ?DateTime,
     *   legalName?: ?string,
     *   license?: ?string,
     *   licenseState?: ?string,
     *   logo?: ?FileContent,
     *   mAddress1?: ?string,
     *   mAddress2?: ?string,
     *   mccid?: ?string,
     *   mCity?: ?string,
     *   mCountry?: ?string,
     *   mState?: ?string,
     *   mZip?: ?string,
     *   orgId?: ?int,
     *   orgParentName?: ?string,
     *   ownerData?: ?array<Owners>,
     *   ownType?: ?value-of<OwnType>,
     *   pageidentifier?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   resumable?: ?bool,
     *   salesCode?: ?string,
     *   serviceData?: ?Services,
     *   signer?: ?SignerData,
     *   taxfillname?: ?string,
     *   templateId?: ?int,
     *   websiteAddress?: ?string,
     *   whencharged?: ?value-of<Whencharged>,
     *   whendelivered?: ?value-of<Whendelivered>,
     *   whenProvided?: ?value-of<Whenprovided>,
     *   whenrefund?: ?value-of<Whenrefunded>,
     *   additionalData?: ?string,
     *   repCode?: ?string,
     *   repName?: ?string,
     *   repOffice?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->annualRevenue = $values['annualRevenue'] ?? null;
        $this->averageMonthlyVolume = $values['averageMonthlyVolume'] ?? null;
        $this->averageTicketAmount = $values['averageTicketAmount'] ?? null;
        $this->bAddress1 = $values['bAddress1'] ?? null;
        $this->bAddress2 = $values['bAddress2'] ?? null;
        $this->bankData = $values['bankData'] ?? null;
        $this->bCity = $values['bCity'] ?? null;
        $this->bCountry = $values['bCountry'] ?? null;
        $this->bFax = $values['bFax'] ?? null;
        $this->binPerson = $values['binPerson'] ?? null;
        $this->binPhone = $values['binPhone'] ?? null;
        $this->binWeb = $values['binWeb'] ?? null;
        $this->boardingLinkId = $values['boardingLinkId'] ?? null;
        $this->boardingStatus = $values['boardingStatus'] ?? null;
        $this->boardingSubStatus = $values['boardingSubStatus'] ?? null;
        $this->bPhone = $values['bPhone'] ?? null;
        $this->bStartdate = $values['bStartdate'] ?? null;
        $this->bState = $values['bState'] ?? null;
        $this->bSummary = $values['bSummary'] ?? null;
        $this->builderData = $values['builderData'] ?? null;
        $this->bZip = $values['bZip'] ?? null;
        $this->contactData = $values['contactData'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->dbaName = $values['dbaName'] ?? null;
        $this->documentsRef = $values['documentsRef'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->generalEvents = $values['generalEvents'] ?? null;
        $this->highTicketAmount = $values['highTicketAmount'] ?? null;
        $this->idApplication = $values['idApplication'] ?? null;
        $this->lastModified = $values['lastModified'] ?? null;
        $this->legalName = $values['legalName'] ?? null;
        $this->license = $values['license'] ?? null;
        $this->licenseState = $values['licenseState'] ?? null;
        $this->logo = $values['logo'] ?? null;
        $this->mAddress1 = $values['mAddress1'] ?? null;
        $this->mAddress2 = $values['mAddress2'] ?? null;
        $this->mccid = $values['mccid'] ?? null;
        $this->mCity = $values['mCity'] ?? null;
        $this->mCountry = $values['mCountry'] ?? null;
        $this->mState = $values['mState'] ?? null;
        $this->mZip = $values['mZip'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
        $this->orgParentName = $values['orgParentName'] ?? null;
        $this->ownerData = $values['ownerData'] ?? null;
        $this->ownType = $values['ownType'] ?? null;
        $this->pageidentifier = $values['pageidentifier'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
        $this->salesCode = $values['salesCode'] ?? null;
        $this->serviceData = $values['serviceData'] ?? null;
        $this->signer = $values['signer'] ?? null;
        $this->taxfillname = $values['taxfillname'] ?? null;
        $this->templateId = $values['templateId'] ?? null;
        $this->websiteAddress = $values['websiteAddress'] ?? null;
        $this->whencharged = $values['whencharged'] ?? null;
        $this->whendelivered = $values['whendelivered'] ?? null;
        $this->whenProvided = $values['whenProvided'] ?? null;
        $this->whenrefund = $values['whenrefund'] ?? null;
        $this->additionalData = $values['additionalData'] ?? null;
        $this->repCode = $values['repCode'] ?? null;
        $this->repName = $values['repName'] ?? null;
        $this->repOffice = $values['repOffice'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
