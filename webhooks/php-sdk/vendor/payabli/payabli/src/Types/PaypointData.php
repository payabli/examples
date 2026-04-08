<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class PaypointData extends JsonSerializableType
{
    /**
     * @var ?string $address1
     */
    #[JsonProperty('address1')]
    public ?string $address1;

    /**
     * @var ?string $address2
     */
    #[JsonProperty('address2')]
    public ?string $address2;

    /**
     * @var ?array<Bank> $bankData
     */
    #[JsonProperty('bankData'), ArrayType([Bank::class])]
    public ?array $bankData;

    /**
     * @var ?int $boardingId
     */
    #[JsonProperty('boardingId')]
    public ?int $boardingId;

    /**
     * @var ?string $city
     */
    #[JsonProperty('city')]
    public ?string $city;

    /**
     * @var ?array<Contacts> $contacts
     */
    #[JsonProperty('contacts'), ArrayType([Contacts::class])]
    public ?array $contacts;

    /**
     * @var ?string $country
     */
    #[JsonProperty('country')]
    public ?string $country;

    /**
     * @var ?array<PayabliCredentialsPascal> $credentials
     */
    #[JsonProperty('credentials'), ArrayType([PayabliCredentialsPascal::class])]
    public ?array $credentials;

    /**
     * @var ?string $dbaName
     */
    #[JsonProperty('dbaName')]
    public ?string $dbaName;

    /**
     * @var ?string $externalPaypointId
     */
    #[JsonProperty('externalPaypointID')]
    public ?string $externalPaypointId;

    /**
     * @var ?string $fax Fax number
     */
    #[JsonProperty('fax')]
    public ?string $fax;

    /**
     * @var ?int $idPaypoint
     */
    #[JsonProperty('idPaypoint')]
    public ?int $idPaypoint;

    /**
     * @var ?string $legalName
     */
    #[JsonProperty('legalName')]
    public ?string $legalName;

    /**
     * @var ?OrgData $parentOrg
     */
    #[JsonProperty('parentOrg')]
    public ?OrgData $parentOrg;

    /**
     * @var ?int $paypointStatus
     */
    #[JsonProperty('paypointStatus')]
    public ?int $paypointStatus;

    /**
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @var ?Services $serviceData
     */
    #[JsonProperty('serviceData')]
    public ?Services $serviceData;

    /**
     * @var ?string $state
     */
    #[JsonProperty('state')]
    public ?string $state;

    /**
     * @var ?PaypointSummary $summary
     */
    #[JsonProperty('summary')]
    public ?PaypointSummary $summary;

    /**
     * @var ?int $timeZone
     */
    #[JsonProperty('timeZone')]
    public ?int $timeZone;

    /**
     * @var ?string $websiteAddress
     */
    #[JsonProperty('websiteAddress')]
    public ?string $websiteAddress;

    /**
     * @var ?string $zip
     */
    #[JsonProperty('zip')]
    public ?string $zip;

    /**
     * @param array{
     *   address1?: ?string,
     *   address2?: ?string,
     *   bankData?: ?array<Bank>,
     *   boardingId?: ?int,
     *   city?: ?string,
     *   contacts?: ?array<Contacts>,
     *   country?: ?string,
     *   credentials?: ?array<PayabliCredentialsPascal>,
     *   dbaName?: ?string,
     *   externalPaypointId?: ?string,
     *   fax?: ?string,
     *   idPaypoint?: ?int,
     *   legalName?: ?string,
     *   parentOrg?: ?OrgData,
     *   paypointStatus?: ?int,
     *   phone?: ?string,
     *   serviceData?: ?Services,
     *   state?: ?string,
     *   summary?: ?PaypointSummary,
     *   timeZone?: ?int,
     *   websiteAddress?: ?string,
     *   zip?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->address1 = $values['address1'] ?? null;
        $this->address2 = $values['address2'] ?? null;
        $this->bankData = $values['bankData'] ?? null;
        $this->boardingId = $values['boardingId'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->contacts = $values['contacts'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->credentials = $values['credentials'] ?? null;
        $this->dbaName = $values['dbaName'] ?? null;
        $this->externalPaypointId = $values['externalPaypointId'] ?? null;
        $this->fax = $values['fax'] ?? null;
        $this->idPaypoint = $values['idPaypoint'] ?? null;
        $this->legalName = $values['legalName'] ?? null;
        $this->parentOrg = $values['parentOrg'] ?? null;
        $this->paypointStatus = $values['paypointStatus'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->serviceData = $values['serviceData'] ?? null;
        $this->state = $values['state'] ?? null;
        $this->summary = $values['summary'] ?? null;
        $this->timeZone = $values['timeZone'] ?? null;
        $this->websiteAddress = $values['websiteAddress'] ?? null;
        $this->zip = $values['zip'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
