<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class ApplicationDataOdp extends JsonSerializableType
{
    /**
     * @var ?Services $services
     */
    #[JsonProperty('services')]
    public ?Services $services;

    /**
     * @var ?float $annualRevenue Annual revenue amount. We recommend including this value.
     */
    #[JsonProperty('annualRevenue')]
    public ?float $annualRevenue;

    /**
     * @var ?array<FileContent> $attachments
     */
    #[JsonProperty('attachments'), ArrayType([FileContent::class])]
    public ?array $attachments;

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
     * @var ?array<Bank> $bankData
     */
    #[JsonProperty('bankData'), ArrayType([Bank::class])]
    public ?array $bankData;

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
     * @var ?array<ApplicationDataOdpContactsItem> $contacts List of contacts for the business.
     */
    #[JsonProperty('contacts'), ArrayType([ApplicationDataOdpContactsItem::class])]
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
     * @var ?array<ApplicationDataOdpOwnershipItem> $ownership List of Owners with at least a 25% ownership.
     */
    #[JsonProperty('ownership'), ArrayType([ApplicationDataOdpOwnershipItem::class])]
    public ?array $ownership;

    /**
     * @var float $payoutAverageMonthlyVolume
     */
    #[JsonProperty('payoutAverageMonthlyVolume')]
    public float $payoutAverageMonthlyVolume;

    /**
     * @var float $payoutAverageTicketAmount
     */
    #[JsonProperty('payoutAverageTicketAmount')]
    public float $payoutAverageTicketAmount;

    /**
     * @var float $payoutCreditLimit
     */
    #[JsonProperty('payoutCreditLimit')]
    public float $payoutCreditLimit;

    /**
     * @var float $payoutHighTicketAmount
     */
    #[JsonProperty('payoutHighTicketAmount')]
    public float $payoutHighTicketAmount;

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
     * @var ?string $taxfillname
     */
    #[JsonProperty('taxfillname')]
    public ?string $taxfillname;

    /**
     * @var ?int $templateId The associated boarding template's ID in Payabli. Either `templateId` or `boardingLinkId` are required.
     */
    #[JsonProperty('templateId')]
    public ?int $templateId;

    /**
     * @var ?string $website
     */
    #[JsonProperty('website')]
    public ?string $website;

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
     *   payoutAverageMonthlyVolume: float,
     *   payoutAverageTicketAmount: float,
     *   payoutCreditLimit: float,
     *   payoutHighTicketAmount: float,
     *   signer: SignerDataRequest,
     *   services?: ?Services,
     *   annualRevenue?: ?float,
     *   attachments?: ?array<FileContent>,
     *   baddress?: ?string,
     *   baddress1?: ?string,
     *   bankData?: ?array<Bank>,
     *   bcity?: ?string,
     *   bcountry?: ?string,
     *   boardingLinkId?: ?string,
     *   bstate?: ?string,
     *   bsummary?: ?string,
     *   btype?: ?value-of<OwnType>,
     *   bzip?: ?string,
     *   contacts?: ?array<ApplicationDataOdpContactsItem>,
     *   dbaname?: ?string,
     *   ein?: ?string,
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
     *   ownership?: ?array<ApplicationDataOdpOwnershipItem>,
     *   phonenumber?: ?string,
     *   recipientEmail?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   resumable?: ?bool,
     *   startdate?: ?string,
     *   taxfillname?: ?string,
     *   templateId?: ?int,
     *   website?: ?string,
     *   repCode?: ?string,
     *   repName?: ?string,
     *   repOffice?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->services = $values['services'] ?? null;
        $this->annualRevenue = $values['annualRevenue'] ?? null;
        $this->attachments = $values['attachments'] ?? null;
        $this->baddress = $values['baddress'] ?? null;
        $this->baddress1 = $values['baddress1'] ?? null;
        $this->bankData = $values['bankData'] ?? null;
        $this->bcity = $values['bcity'] ?? null;
        $this->bcountry = $values['bcountry'] ?? null;
        $this->boardingLinkId = $values['boardingLinkId'] ?? null;
        $this->bstate = $values['bstate'] ?? null;
        $this->bsummary = $values['bsummary'] ?? null;
        $this->btype = $values['btype'] ?? null;
        $this->bzip = $values['bzip'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->dbaname = $values['dbaname'] ?? null;
        $this->ein = $values['ein'] ?? null;
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
        $this->payoutAverageMonthlyVolume = $values['payoutAverageMonthlyVolume'];
        $this->payoutAverageTicketAmount = $values['payoutAverageTicketAmount'];
        $this->payoutCreditLimit = $values['payoutCreditLimit'];
        $this->payoutHighTicketAmount = $values['payoutHighTicketAmount'];
        $this->phonenumber = $values['phonenumber'] ?? null;
        $this->recipientEmail = $values['recipientEmail'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
        $this->signer = $values['signer'];
        $this->startdate = $values['startdate'] ?? null;
        $this->taxfillname = $values['taxfillname'] ?? null;
        $this->templateId = $values['templateId'] ?? null;
        $this->website = $values['website'] ?? null;
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
