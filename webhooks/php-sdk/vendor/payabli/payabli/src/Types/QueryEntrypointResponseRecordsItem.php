<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class QueryEntrypointResponseRecordsItem extends JsonSerializableType
{
    /**
     * @var ?float $averageMonthlyVolume
     */
    #[JsonProperty('AverageMonthlyVolume')]
    public ?float $averageMonthlyVolume;

    /**
     * @var ?float $averageTicketAmount
     */
    #[JsonProperty('AverageTicketAmount')]
    public ?float $averageTicketAmount;

    /**
     * @var ?string $bAddress1
     */
    #[JsonProperty('BAddress1')]
    public ?string $bAddress1;

    /**
     * @var ?string $bAddress2
     */
    #[JsonProperty('BAddress2')]
    public ?string $bAddress2;

    /**
     * @var ?array<Bank> $bankData
     */
    #[JsonProperty('BankData'), ArrayType([Bank::class])]
    public ?array $bankData;

    /**
     * @var ?string $bCity
     */
    #[JsonProperty('BCity')]
    public ?string $bCity;

    /**
     * @var ?string $bCountry
     */
    #[JsonProperty('BCountry')]
    public ?string $bCountry;

    /**
     * @var ?string $bFax The business's fax number.
     */
    #[JsonProperty('BFax')]
    public ?string $bFax;

    /**
     * @var ?int $binPerson
     */
    #[JsonProperty('BinPerson')]
    public ?int $binPerson;

    /**
     * @var ?int $binPhone
     */
    #[JsonProperty('BinPhone')]
    public ?int $binPhone;

    /**
     * @var ?int $binWeb
     */
    #[JsonProperty('BinWeb')]
    public ?int $binWeb;

    /**
     * @var ?int $boardingId
     */
    #[JsonProperty('BoardingId')]
    public ?int $boardingId;

    /**
     * @var ?string $bPhone
     */
    #[JsonProperty('BPhone')]
    public ?string $bPhone;

    /**
     * @var ?string $bStartdate
     */
    #[JsonProperty('BStartdate')]
    public ?string $bStartdate;

    /**
     * @var ?string $bState
     */
    #[JsonProperty('BState')]
    public ?string $bState;

    /**
     * @var ?string $bSummary
     */
    #[JsonProperty('BSummary')]
    public ?string $bSummary;

    /**
     * @var ?int $bTimeZone
     */
    #[JsonProperty('BTimeZone')]
    public ?int $bTimeZone;

    /**
     * @var ?string $bZip
     */
    #[JsonProperty('BZip')]
    public ?string $bZip;

    /**
     * @var ?array<Contacts> $contactData
     */
    #[JsonProperty('ContactData'), ArrayType([Contacts::class])]
    public ?array $contactData;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('CreatedAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?string $dbaName
     */
    #[JsonProperty('DbaName')]
    public ?string $dbaName;

    /**
     * @var ?string $documentsRef
     */
    #[JsonProperty('DocumentsRef')]
    public ?string $documentsRef;

    /**
     * @var ?string $ein
     */
    #[JsonProperty('Ein')]
    public ?string $ein;

    /**
     * @var ?array<PaypointEntryConfig> $entryPoints
     */
    #[JsonProperty('EntryPoints'), ArrayType([PaypointEntryConfig::class])]
    public ?array $entryPoints;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $externalProcessorInformation
     */
    #[JsonProperty('ExternalProcessorInformation')]
    public ?string $externalProcessorInformation;

    /**
     * @var ?float $highTicketAmount
     */
    #[JsonProperty('HighTicketAmount')]
    public ?float $highTicketAmount;

    /**
     * @var ?int $idPaypoint
     */
    #[JsonProperty('IdPaypoint')]
    public ?int $idPaypoint;

    /**
     * @var ?DateTime $lastModified
     */
    #[JsonProperty('LastModified'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastModified;

    /**
     * @var ?string $legalName
     */
    #[JsonProperty('LegalName')]
    public ?string $legalName;

    /**
     * @var ?string $license
     */
    #[JsonProperty('License')]
    public ?string $license;

    /**
     * @var ?string $licenseState
     */
    #[JsonProperty('LicenseState')]
    public ?string $licenseState;

    /**
     * @var ?string $mAddress1
     */
    #[JsonProperty('MAddress1')]
    public ?string $mAddress1;

    /**
     * @var ?string $mAddress2
     */
    #[JsonProperty('MAddress2')]
    public ?string $mAddress2;

    /**
     * @var ?string $mccid
     */
    #[JsonProperty('Mccid')]
    public ?string $mccid;

    /**
     * @var ?string $mCity
     */
    #[JsonProperty('MCity')]
    public ?string $mCity;

    /**
     * @var ?string $mCountry
     */
    #[JsonProperty('MCountry')]
    public ?string $mCountry;

    /**
     * @var ?string $mState
     */
    #[JsonProperty('MState')]
    public ?string $mState;

    /**
     * @var ?string $mZip
     */
    #[JsonProperty('MZip')]
    public ?string $mZip;

    /**
     * @var ?int $orgId
     */
    #[JsonProperty('OrgId')]
    public ?int $orgId;

    /**
     * @var ?string $orgParentName
     */
    #[JsonProperty('OrgParentName')]
    public ?string $orgParentName;

    /**
     * @var ?array<Owners> $ownerData
     */
    #[JsonProperty('OwnerData'), ArrayType([Owners::class])]
    public ?array $ownerData;

    /**
     * @var ?value-of<OwnType> $ownType
     */
    #[JsonProperty('OwnType')]
    public ?string $ownType;

    /**
     * @var ?int $paypointStatus
     */
    #[JsonProperty('PaypointStatus')]
    public ?int $paypointStatus;

    /**
     * @var ?string $salesCode
     */
    #[JsonProperty('SalesCode')]
    public ?string $salesCode;

    /**
     * @var ?Services $serviceData
     */
    #[JsonProperty('ServiceData')]
    public ?Services $serviceData;

    /**
     * @var ?PaypointSummary $summary
     */
    #[JsonProperty('summary')]
    public ?PaypointSummary $summary;

    /**
     * @var ?string $taxfillname
     */
    #[JsonProperty('Taxfillname')]
    public ?string $taxfillname;

    /**
     * @var ?int $templateId
     */
    #[JsonProperty('TemplateId')]
    public ?int $templateId;

    /**
     * @var ?string $websiteAddress Business website.
     */
    #[JsonProperty('WebsiteAddress')]
    public ?string $websiteAddress;

    /**
     * @var ?value-of<Whencharged> $whencharged
     */
    #[JsonProperty('Whencharged')]
    public ?string $whencharged;

    /**
     * @var ?value-of<Whendelivered> $whendelivered
     */
    #[JsonProperty('Whendelivered')]
    public ?string $whendelivered;

    /**
     * @var ?value-of<Whenprovided> $whenprovided
     */
    #[JsonProperty('Whenprovided')]
    public ?string $whenprovided;

    /**
     * @var ?value-of<Whenrefunded> $whenrefund
     */
    #[JsonProperty('Whenrefund')]
    public ?string $whenrefund;

    /**
     * @param array{
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
     *   boardingId?: ?int,
     *   bPhone?: ?string,
     *   bStartdate?: ?string,
     *   bState?: ?string,
     *   bSummary?: ?string,
     *   bTimeZone?: ?int,
     *   bZip?: ?string,
     *   contactData?: ?array<Contacts>,
     *   createdAt?: ?DateTime,
     *   dbaName?: ?string,
     *   documentsRef?: ?string,
     *   ein?: ?string,
     *   entryPoints?: ?array<PaypointEntryConfig>,
     *   externalPaypointId?: ?string,
     *   externalProcessorInformation?: ?string,
     *   highTicketAmount?: ?float,
     *   idPaypoint?: ?int,
     *   lastModified?: ?DateTime,
     *   legalName?: ?string,
     *   license?: ?string,
     *   licenseState?: ?string,
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
     *   paypointStatus?: ?int,
     *   salesCode?: ?string,
     *   serviceData?: ?Services,
     *   summary?: ?PaypointSummary,
     *   taxfillname?: ?string,
     *   templateId?: ?int,
     *   websiteAddress?: ?string,
     *   whencharged?: ?value-of<Whencharged>,
     *   whendelivered?: ?value-of<Whendelivered>,
     *   whenprovided?: ?value-of<Whenprovided>,
     *   whenrefund?: ?value-of<Whenrefunded>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
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
        $this->boardingId = $values['boardingId'] ?? null;
        $this->bPhone = $values['bPhone'] ?? null;
        $this->bStartdate = $values['bStartdate'] ?? null;
        $this->bState = $values['bState'] ?? null;
        $this->bSummary = $values['bSummary'] ?? null;
        $this->bTimeZone = $values['bTimeZone'] ?? null;
        $this->bZip = $values['bZip'] ?? null;
        $this->contactData = $values['contactData'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->dbaName = $values['dbaName'] ?? null;
        $this->documentsRef = $values['documentsRef'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->entryPoints = $values['entryPoints'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->externalProcessorInformation = $values['externalProcessorInformation'] ?? null;
        $this->highTicketAmount = $values['highTicketAmount'] ?? null;
        $this->idPaypoint = $values['idPaypoint'] ?? null;
        $this->lastModified = $values['lastModified'] ?? null;
        $this->legalName = $values['legalName'] ?? null;
        $this->license = $values['license'] ?? null;
        $this->licenseState = $values['licenseState'] ?? null;
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
        $this->paypointStatus = $values['paypointStatus'] ?? null;
        $this->salesCode = $values['salesCode'] ?? null;
        $this->serviceData = $values['serviceData'] ?? null;
        $this->summary = $values['summary'] ?? null;
        $this->taxfillname = $values['taxfillname'] ?? null;
        $this->templateId = $values['templateId'] ?? null;
        $this->websiteAddress = $values['websiteAddress'] ?? null;
        $this->whencharged = $values['whencharged'] ?? null;
        $this->whendelivered = $values['whendelivered'] ?? null;
        $this->whenprovided = $values['whenprovided'] ?? null;
        $this->whenrefund = $values['whenrefund'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
