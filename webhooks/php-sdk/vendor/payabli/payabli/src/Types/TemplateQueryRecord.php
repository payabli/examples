<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;
use DateTime;
use Payabli\Core\Types\Date;

class TemplateQueryRecord extends JsonSerializableType
{
    /**
     * @var ?bool $addPrice
     */
    #[JsonProperty('addPrice')]
    public ?bool $addPrice;

    /**
     * @var ?array<BoardingQueryLinks> $boardingLinks
     */
    #[JsonProperty('boardingLinks'), ArrayType([BoardingQueryLinks::class])]
    public ?array $boardingLinks;

    /**
     * @var ?DateTime $createdAt
     */
    #[JsonProperty('createdAt'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $createdAt;

    /**
     * @var ?int $idTemplate
     */
    #[JsonProperty('idTemplate')]
    public ?int $idTemplate;

    /**
     * @var ?bool $isRoot
     */
    #[JsonProperty('isRoot')]
    public ?bool $isRoot;

    /**
     * @var ?string $orgParentName
     */
    #[JsonProperty('orgParentName')]
    public ?string $orgParentName;

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
     * @var ?string $templateCode
     */
    #[JsonProperty('templateCode')]
    public ?string $templateCode;

    /**
     * @var ?TemplateContentResponse $templateContent
     */
    #[JsonProperty('templateContent')]
    public ?TemplateContentResponse $templateContent;

    /**
     * @var ?string $templateDescription
     */
    #[JsonProperty('templateDescription')]
    public ?string $templateDescription;

    /**
     * @var ?string $templateTitle
     */
    #[JsonProperty('templateTitle')]
    public ?string $templateTitle;

    /**
     * @var ?int $usedBy
     */
    #[JsonProperty('usedBy')]
    public ?int $usedBy;

    /**
     * @param array{
     *   addPrice?: ?bool,
     *   boardingLinks?: ?array<BoardingQueryLinks>,
     *   createdAt?: ?DateTime,
     *   idTemplate?: ?int,
     *   isRoot?: ?bool,
     *   orgParentName?: ?string,
     *   recipientEmailNotification?: ?bool,
     *   resumable?: ?bool,
     *   templateCode?: ?string,
     *   templateContent?: ?TemplateContentResponse,
     *   templateDescription?: ?string,
     *   templateTitle?: ?string,
     *   usedBy?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->addPrice = $values['addPrice'] ?? null;
        $this->boardingLinks = $values['boardingLinks'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->idTemplate = $values['idTemplate'] ?? null;
        $this->isRoot = $values['isRoot'] ?? null;
        $this->orgParentName = $values['orgParentName'] ?? null;
        $this->recipientEmailNotification = $values['recipientEmailNotification'] ?? null;
        $this->resumable = $values['resumable'] ?? null;
        $this->templateCode = $values['templateCode'] ?? null;
        $this->templateContent = $values['templateContent'] ?? null;
        $this->templateDescription = $values['templateDescription'] ?? null;
        $this->templateTitle = $values['templateTitle'] ?? null;
        $this->usedBy = $values['usedBy'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
