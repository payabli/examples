<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class ApplicationData extends JsonSerializableType
{
    /**
     * @var ?Services $services
     */
    #[JsonProperty('services')]
    public ?Services $services;

    /**
     * @var ?float $annualRevenue
     */
    #[JsonProperty('annualRevenue')]
    public ?float $annualRevenue;

    /**
     * @var ?array<FileContent> $attachments
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

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
     * @var ?Bank $bankData
     */
    #[JsonProperty('bankData')]
    public ?Bank $bankData;

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
     * @var ?array<Contacts> $contacts
     */
    #[JsonProperty('contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var ?string $dbaname
     */
    #[JsonProperty('dbaname')]
    public ?string $dbaname;

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
     * @var ?string $faxnumber
     */
    #[JsonProperty('faxnumber')]
    public ?string $faxnumber;

    /**
     * @var ?float $highticketamt
     */
    #[JsonProperty('highticketamt')]
    public ?float $highticketamt;

    /**
     * @var ?string $legalname
     */
    #[JsonProperty('legalname')]
    public ?string $legalname;

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
     * @var ?array<Owners> $ownership
     */
    #[JsonProperty('ownership'), ArrayType([Owners::class])]
    public ?array $ownership;

    /**
     * @var ?float $payoutAverageMonthlyVolume
     */
    #[JsonProperty('payoutAverageMonthlyVolume')]
    public ?float $payoutAverageMonthlyVolume;

    /**
     * @var ?float $payoutAverageTicketLimit
     */
    #[JsonProperty('payoutAverageTicketLimit')]
    public ?float $payoutAverageTicketLimit;

    /**
     * @var ?float $payoutCreditLimit
     */
    #[JsonProperty('payoutCreditLimit')]
    public ?float $payoutCreditLimit;

    /**
     * @var ?float $payoutHighTicketAmount
     */
    #[JsonProperty('payoutHighTicketAmount')]
    public ?float $payoutHighTicketAmount;

    /**
     * @var ?string $phonenumber
     */
    #[JsonProperty('phonenumber')]
    public ?string $phonenumber;

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
     * @var ?SignerDataRequest $signer
     */
    #[JsonProperty('signer')]
    public ?SignerDataRequest $signer;

    /**
     * @var ?string $startdate
     */
    #[JsonProperty('startdate')]
    public ?string $startdate;

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
     * @var ?value-of<Whencharged> $whenCharged
     */
    #[JsonProperty('whenCharged')]
    public ?string $whenCharged;

    /**
     * @var ?value-of<Whendelivered> $whenDelivered
     */
    #[JsonProperty('whenDelivered')]
    public ?string $whenDelivered;

    /**
     * @var ?value-of<Whenprovided> $whenProvided
     */
    #[JsonProperty('whenProvided')]
    public ?string $whenProvided;

    /**
     * @var ?value-of<Whenrefunded> $whenRefunded
     */
    #[JsonProperty('whenRefunded')]
    public ?string $whenRefunded;

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
     *   services?: ?Services,
     *   annualRevenue?: ?float,
     *   attachments?: ?array<FileContent>,
     *   avgmonthly?: ?float,
     *   baddress?: ?string,
     *   baddress1?: ?string,
     *   bankData?: ?Bank,
     *   bcity?: ?string,
     *   bcountry?: ?string,
     *   binperson?: ?int,
     *   binphone?: ?int,
     *   binweb?: ?int,
     *   bstate?: ?string,
     *   bsummary?: ?string,
     *   btype?: ?value-of<OwnType>,
     *   bzip?: ?string,
     *   contacts?: ?array<Contacts>,
     *   dbaname?: ?string,
     *   ein?: ?string,
     *   externalPaypointId?: ?string,
     *   faxnumber?: ?string,
     *   highticketamt?: ?float,
     *   legalname?: ?string,
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
     *   ownership?: ?array<Owners>,
     *   payoutAverageMonthlyVolume?: ?float,
     *   payoutAverageTicketLimit?: ?float,
     *   payoutCreditLimit?: ?float,
     *   payoutHighTicketAmount?: ?float,
     *   phonenumber?: ?string,
     *   recipientEmail?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   resumable?: ?bool,
     *   signer?: ?SignerDataRequest,
     *   startdate?: ?string,
     *   taxfillname?: ?string,
     *   templateId?: ?int,
     *   ticketamt?: ?float,
     *   website?: ?string,
     *   whenCharged?: ?value-of<Whencharged>,
     *   whenDelivered?: ?value-of<Whendelivered>,
     *   whenProvided?: ?value-of<Whenprovided>,
     *   whenRefunded?: ?value-of<Whenrefunded>,
     *   repCode?: ?string,
     *   repName?: ?string,
     *   repOffice?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->services = $values['services'] ?? null;
        $this->annualRevenue = $values['annualRevenue'] ?? null;
        $this->attachments = $values['attachments'] ?? null;
        $this->avgmonthly = $values['avgmonthly'] ?? null;
        $this->baddress = $values['baddress'] ?? null;
        $this->baddress1 = $values['baddress1'] ?? null;
        $this->bankData = $values['bankData'] ?? null;
        $this->bcity = $values['bcity'] ?? null;
        $this->bcountry = $values['bcountry'] ?? null;
        $this->binperson = $values['binperson'] ?? null;
        $this->binphone = $values['binphone'] ?? null;
        $this->binweb = $values['binweb'] ?? null;
        $this->bstate = $values['bstate'] ?? null;
        $this->bsummary = $values['bsummary'] ?? null;
        $this->btype = $values['btype'] ?? null;
        $this->bzip = $values['bzip'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->dbaname = $values['dbaname'] ?? null;
        $this->ein = $values['ein'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->faxnumber = $values['faxnumber'] ?? null;
        $this->highticketamt = $values['highticketamt'] ?? null;
        $this->legalname = $values['legalname'] ?? null;
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
        $this->payoutAverageMonthlyVolume = $values['payoutAverageMonthlyVolume'] ?? null;
        $this->payoutAverageTicketLimit = $values['payoutAverageTicketLimit'] ?? null;
        $this->payoutCreditLimit = $values['payoutCreditLimit'] ?? null;
        $this->payoutHighTicketAmount = $values['payoutHighTicketAmount'] ?? null;
        $this->phonenumber = $values['phonenumber'] ?? null;
        $this->recipientEmail = $values['recipientEmail'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
        $this->signer = $values['signer'] ?? null;
        $this->startdate = $values['startdate'] ?? null;
        $this->taxfillname = $values['taxfillname'] ?? null;
        $this->templateId = $values['templateId'] ?? null;
        $this->ticketamt = $values['ticketamt'] ?? null;
        $this->website = $values['website'] ?? null;
        $this->whenCharged = $values['whenCharged'] ?? null;
        $this->whenDelivered = $values['whenDelivered'] ?? null;
        $this->whenProvided = $values['whenProvided'] ?? null;
        $this->whenRefunded = $values['whenRefunded'] ?? null;
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
