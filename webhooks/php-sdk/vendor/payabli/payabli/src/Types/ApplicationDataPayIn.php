<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Fields for Pay In boarding applications.
 */
class ApplicationDataPayIn extends JsonSerializableType
{
    /**
     * @var ApplicationDataPayInServices $services
     */
    #[JsonProperty('services')]
    public ApplicationDataPayInServices $services;

    /**
     * @var ?float $annualRevenue
     */
    #[JsonProperty('annualRevenue')]
    public ?float $annualRevenue;

    /**
     * @var ?string $averageBillSize
     */
    #[JsonProperty('averageBillSize')]
    public ?string $averageBillSize;

    /**
     * @var ?string $averageMonthlyBill
     */
    #[JsonProperty('averageMonthlyBill')]
    public ?string $averageMonthlyBill;

    /**
     * @var ?float $avgmonthly
     */
    #[JsonProperty('avgmonthly')]
    public ?float $avgmonthly;

    /**
     * @var ?string $baddress
     */
    #[JsonProperty('baddress')]
    public ?string $baddress;

    /**
     * @var ?string $baddress1
     */
    #[JsonProperty('baddress1')]
    public ?string $baddress1;

    /**
     * @var ApplicationDataPayInBankData $bankData
     */
    #[JsonProperty('bankData')]
    public ApplicationDataPayInBankData $bankData;

    /**
     * @var ?string $bcity
     */
    #[JsonProperty('bcity')]
    public ?string $bcity;

    /**
     * @var ?string $bcountry
     */
    #[JsonProperty('bcountry')]
    public ?string $bcountry;

    /**
     * @var ?int $binperson
     */
    #[JsonProperty('binperson')]
    public ?int $binperson;

    /**
     * @var ?int $binphone
     */
    #[JsonProperty('binphone')]
    public ?int $binphone;

    /**
     * @var ?int $binweb
     */
    #[JsonProperty('binweb')]
    public ?int $binweb;

    /**
     * @var ?string $boardingLinkId Boarding link ID for the application. Either `templateId` or `boardingLinkId` are required.
     */
    #[JsonProperty('boardingLinkId')]
    public ?string $boardingLinkId;

    /**
     * @var ?string $bstate
     */
    #[JsonProperty('bstate')]
    public ?string $bstate;

    /**
     * @var ?string $bsummary
     */
    #[JsonProperty('bsummary')]
    public ?string $bsummary;

    /**
     * @var ?value-of<OwnType> $btype
     */
    #[JsonProperty('btype')]
    public ?string $btype;

    /**
     * @var ?string $bzip
     */
    #[JsonProperty('bzip')]
    public ?string $bzip;

    /**
     * @var ?array<ApplicationDataPayInContactsItem> $contacts List of contacts for the business.
     */
    #[JsonProperty('contacts'), ArrayType([ApplicationDataPayInContactsItem::class])]
    public ?array $contacts;

    /**
     * @var ?string $creditLimit The maximum amount of credit that our lending partner, has authorized to your business. It's the upper boundary on how much you can spend or owe on a credit account at any given time.
     */
    #[JsonProperty('creditLimit')]
    public ?string $creditLimit;

    /**
     * @var ?string $dbaName The alternate or common name that this business is doing business under usually referred to as a DBA name. Payabli strongly recommends including this information.
     */
    #[JsonProperty('dbaName')]
    public ?string $dbaName;

    /**
     * @var ?string $ein
     */
    #[JsonProperty('ein')]
    public ?string $ein;

    /**
     * @var ?string $externalpaypointId
     */
    #[JsonProperty('externalpaypointID')]
    public ?string $externalpaypointId;

    /**
     * @var ?string $faxnumber The business's fax number.
     */
    #[JsonProperty('faxnumber')]
    public ?string $faxnumber;

    /**
     * @var ?float $highticketamt
     */
    #[JsonProperty('highticketamt')]
    public ?float $highticketamt;

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
     * @var ?string $licstate
     */
    #[JsonProperty('licstate')]
    public ?string $licstate;

    /**
     * @var ?string $maddress
     */
    #[JsonProperty('maddress')]
    public ?string $maddress;

    /**
     * @var ?string $maddress1
     */
    #[JsonProperty('maddress1')]
    public ?string $maddress1;

    /**
     * @var ?string $mcc
     */
    #[JsonProperty('mcc')]
    public ?string $mcc;

    /**
     * @var ?string $mcity
     */
    #[JsonProperty('mcity')]
    public ?string $mcity;

    /**
     * @var ?string $mcountry
     */
    #[JsonProperty('mcountry')]
    public ?string $mcountry;

    /**
     * @var ?string $mstate
     */
    #[JsonProperty('mstate')]
    public ?string $mstate;

    /**
     * @var ?string $mzip
     */
    #[JsonProperty('mzip')]
    public ?string $mzip;

    /**
     * @var ?int $orgId
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?array<ApplicationDataPayInOwnershipItem> $ownership List of Owners with at least a 25% ownership.
     */
    #[JsonProperty('ownership'), ArrayType([ApplicationDataPayInOwnershipItem::class])]
    public ?array $ownership;

    /**
     * @var string $phonenumber The business's phone number.
     */
    #[JsonProperty('phonenumber')]
    public string $phonenumber;

    /**
     * @var string $processingRegion The business's processing region, either `US` or `CA`.
     */
    #[JsonProperty('processingRegion')]
    public string $processingRegion;

    /**
     * @var ?string $recipientEmail Email address for the applicant. This is used to send the applicant a boarding link.
     */
    #[JsonProperty('recipientEmail')]
    public ?string $recipientEmail;

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
     * @var SignerDataRequest $signer
     */
    #[JsonProperty('signer')]
    public SignerDataRequest $signer;

    /**
     * @var ?string $startdate
     */
    #[JsonProperty('startdate')]
    public ?string $startdate;

    /**
     * @var ?string $taxFillName
     */
    #[JsonProperty('taxFillName')]
    public ?string $taxFillName;

    /**
     * @var ?int $templateId The associated boarding template's ID in Payabli. Either `templateId` or `boardingLinkId` are required.
     */
    #[JsonProperty('templateId')]
    public ?int $templateId;

    /**
     * @var ?float $ticketamt
     */
    #[JsonProperty('ticketamt')]
    public ?float $ticketamt;

    /**
     * @var ?string $website
     */
    #[JsonProperty('website')]
    public ?string $website;

    /**
     * @var value-of<Whencharged> $whenCharged
     */
    #[JsonProperty('whenCharged')]
    public string $whenCharged;

    /**
     * @var value-of<Whendelivered> $whenDelivered
     */
    #[JsonProperty('whenDelivered')]
    public string $whenDelivered;

    /**
     * @var value-of<Whenprovided> $whenProvided
     */
    #[JsonProperty('whenProvided')]
    public string $whenProvided;

    /**
     * @var value-of<Whenrefunded> $whenRefunded
     */
    #[JsonProperty('whenRefunded')]
    public string $whenRefunded;

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
     *   services: ApplicationDataPayInServices,
     *   bankData: ApplicationDataPayInBankData,
     *   phonenumber: string,
     *   processingRegion: string,
     *   signer: SignerDataRequest,
     *   whenCharged: value-of<Whencharged>,
     *   whenDelivered: value-of<Whendelivered>,
     *   whenProvided: value-of<Whenprovided>,
     *   whenRefunded: value-of<Whenrefunded>,
     *   annualRevenue?: ?float,
     *   averageBillSize?: ?string,
     *   averageMonthlyBill?: ?string,
     *   avgmonthly?: ?float,
     *   baddress?: ?string,
     *   baddress1?: ?string,
     *   bcity?: ?string,
     *   bcountry?: ?string,
     *   binperson?: ?int,
     *   binphone?: ?int,
     *   binweb?: ?int,
     *   boardingLinkId?: ?string,
     *   bstate?: ?string,
     *   bsummary?: ?string,
     *   btype?: ?value-of<OwnType>,
     *   bzip?: ?string,
     *   contacts?: ?array<ApplicationDataPayInContactsItem>,
     *   creditLimit?: ?string,
     *   dbaName?: ?string,
     *   ein?: ?string,
     *   externalpaypointId?: ?string,
     *   faxnumber?: ?string,
     *   highticketamt?: ?float,
     *   legalName?: ?string,
     *   license?: ?string,
     *   licstate?: ?string,
     *   maddress?: ?string,
     *   maddress1?: ?string,
     *   mcc?: ?string,
     *   mcity?: ?string,
     *   mcountry?: ?string,
     *   mstate?: ?string,
     *   mzip?: ?string,
     *   orgId?: ?int,
     *   ownership?: ?array<ApplicationDataPayInOwnershipItem>,
     *   recipientEmail?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   resumable?: ?bool,
     *   startdate?: ?string,
     *   taxFillName?: ?string,
     *   templateId?: ?int,
     *   ticketamt?: ?float,
     *   website?: ?string,
     *   additionalData?: ?string,
     *   repCode?: ?string,
     *   repName?: ?string,
     *   repOffice?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->services = $values['services'];
        $this->annualRevenue = $values['annualRevenue'] ?? null;
        $this->averageBillSize = $values['averageBillSize'] ?? null;
        $this->averageMonthlyBill = $values['averageMonthlyBill'] ?? null;
        $this->avgmonthly = $values['avgmonthly'] ?? null;
        $this->baddress = $values['baddress'] ?? null;
        $this->baddress1 = $values['baddress1'] ?? null;
        $this->bankData = $values['bankData'];
        $this->bcity = $values['bcity'] ?? null;
        $this->bcountry = $values['bcountry'] ?? null;
        $this->binperson = $values['binperson'] ?? null;
        $this->binphone = $values['binphone'] ?? null;
        $this->binweb = $values['binweb'] ?? null;
        $this->boardingLinkId = $values['boardingLinkId'] ?? null;
        $this->bstate = $values['bstate'] ?? null;
        $this->bsummary = $values['bsummary'] ?? null;
        $this->btype = $values['btype'] ?? null;
        $this->bzip = $values['bzip'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->creditLimit = $values['creditLimit'] ?? null;
        $this->dbaName = $values['dbaName'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->externalpaypointId = $values['externalpaypointId'] ?? null;
        $this->faxnumber = $values['faxnumber'] ?? null;
        $this->highticketamt = $values['highticketamt'] ?? null;
        $this->legalName = $values['legalName'] ?? null;
        $this->license = $values['license'] ?? null;
        $this->licstate = $values['licstate'] ?? null;
        $this->maddress = $values['maddress'] ?? null;
        $this->maddress1 = $values['maddress1'] ?? null;
        $this->mcc = $values['mcc'] ?? null;
        $this->mcity = $values['mcity'] ?? null;
        $this->mcountry = $values['mcountry'] ?? null;
        $this->mstate = $values['mstate'] ?? null;
        $this->mzip = $values['mzip'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
        $this->ownership = $values['ownership'] ?? null;
        $this->phonenumber = $values['phonenumber'];
        $this->processingRegion = $values['processingRegion'];
        $this->recipientEmail = $values['recipientEmail'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
        $this->signer = $values['signer'];
        $this->startdate = $values['startdate'] ?? null;
        $this->taxFillName = $values['taxFillName'] ?? null;
        $this->templateId = $values['templateId'] ?? null;
        $this->ticketamt = $values['ticketamt'] ?? null;
        $this->website = $values['website'] ?? null;
        $this->whenCharged = $values['whenCharged'];
        $this->whenDelivered = $values['whenDelivered'];
        $this->whenProvided = $values['whenProvided'];
        $this->whenRefunded = $values['whenRefunded'];
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
