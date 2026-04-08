<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class DocumentSection extends JsonSerializableType
{
    /**
     * @var ?bool $visble
     */
    #[JsonProperty('visble')]
    public ?bool $visble;

    /**
     * @var ?string $subFooter
     */
    #[JsonProperty('subFooter')]
    public ?string $subFooter;

    /**
     * @var ?string $subHeader
     */
    #[JsonProperty('subHeader')]
    public ?string $subHeader;

    /**
     * @var ?BankSection $depositBank
     */
    #[JsonProperty('depositBank')]
    public ?BankSection $depositBank;

    /**
     * @var ?int $minimumDocuments The minimum number of documents the applicant must upload with the application.
     */
    #[JsonProperty('minimumDocuments')]
    public ?int $minimumDocuments;

    /**
     * @var ?bool $uploadDocuments When `true`, allows the applicant to upload documents to the application.
     */
    #[JsonProperty('uploadDocuments')]
    public ?bool $uploadDocuments;

    /**
     * @var ?BankSection $bankData
     */
    #[JsonProperty('bankData')]
    public ?BankSection $bankData;

    /**
     * @var ?DocumentSectionTermsAndConditions $termsAndConditions
     */
    #[JsonProperty('termsAndConditions')]
    public ?DocumentSectionTermsAndConditions $termsAndConditions;

    /**
     * @var ?SignerSection $signer
     */
    #[JsonProperty('signer')]
    public ?SignerSection $signer;

    /**
     * @var ?bool $visible
     */
    #[JsonProperty('visible')]
    public ?bool $visible;

    /**
     * @var ?BankSection $withdrawalBank
     */
    #[JsonProperty('withdrawalBank')]
    public ?BankSection $withdrawalBank;

    /**
     * @param array{
     *   visble?: ?bool,
     *   subFooter?: ?string,
     *   subHeader?: ?string,
     *   depositBank?: ?BankSection,
     *   minimumDocuments?: ?int,
     *   uploadDocuments?: ?bool,
     *   bankData?: ?BankSection,
     *   termsAndConditions?: ?DocumentSectionTermsAndConditions,
     *   signer?: ?SignerSection,
     *   visible?: ?bool,
     *   withdrawalBank?: ?BankSection,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->visble = $values['visble'] ?? null;
        $this->subFooter = $values['subFooter'] ?? null;
        $this->subHeader = $values['subHeader'] ?? null;
        $this->depositBank = $values['depositBank'] ?? null;
        $this->minimumDocuments = $values['minimumDocuments'] ?? null;
        $this->uploadDocuments = $values['uploadDocuments'] ?? null;
        $this->bankData = $values['bankData'] ?? null;
        $this->termsAndConditions = $values['termsAndConditions'] ?? null;
        $this->signer = $values['signer'] ?? null;
        $this->visible = $values['visible'] ?? null;
        $this->withdrawalBank = $values['withdrawalBank'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
