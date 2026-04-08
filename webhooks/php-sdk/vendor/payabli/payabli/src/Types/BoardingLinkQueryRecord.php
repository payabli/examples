<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class BoardingLinkQueryRecord extends JsonSerializableType
{
    /**
     * @var ?bool $acceptOauth
     */
    #[JsonProperty('acceptOauth')]
    public ?bool $acceptOauth;

    /**
     * @var ?bool $acceptRegister
     */
    #[JsonProperty('acceptRegister')]
    public ?bool $acceptRegister;

    /**
     * @var ?BuilderData $builderData
     */
    #[JsonProperty('builderData')]
    public ?BuilderData $builderData;

    /**
     * @var ?string $entryAttributes
     */
    #[JsonProperty('entryAttributes')]
    public ?string $entryAttributes;

    /**
     * @var ?int $id
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?FileContent $logo Object containing logo file.
     */
    #[JsonProperty('logo')]
    public ?FileContent $logo;

    /**
     * @var ?int $orgId
     */
    #[JsonProperty('orgId')]
    public ?int $orgId;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier:')]
    public ?string $pageIdentifier;

    /**
     * @var ?bool $recipientEmailNotification
     */
    #[JsonProperty('recipientEmailNotification')]
    public ?bool $recipientEmailNotification;

    /**
     * @var ?string $referenceName
     */
    #[JsonProperty('referenceName')]
    public ?string $referenceName;

    /**
     * @var ?int $referenceTemplateId
     */
    #[JsonProperty('referenceTemplateId')]
    public ?int $referenceTemplateId;

    /**
     * @var ?bool $resumable
     */
    #[JsonProperty('resumable')]
    public ?bool $resumable;

    /**
     * @param array{
     *   acceptOauth?: ?bool,
     *   acceptRegister?: ?bool,
     *   builderData?: ?BuilderData,
     *   entryAttributes?: ?string,
     *   id?: ?int,
     *   logo?: ?FileContent,
     *   orgId?: ?int,
     *   pageIdentifier?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   referenceName?: ?string,
     *   referenceTemplateId?: ?int,
     *   resumable?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->acceptOauth = $values['acceptOauth'] ?? null;
        $this->acceptRegister = $values['acceptRegister'] ?? null;
        $this->builderData = $values['builderData'] ?? null;
        $this->entryAttributes = $values['entryAttributes'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->logo = $values['logo'] ?? null;
        $this->orgId = $values['orgId'] ?? null;
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->referenceName = $values['referenceName'] ?? null;
        $this->referenceTemplateId = $values['referenceTemplateId'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
