<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

class PaypointEntryConfig extends JsonSerializableType
{
    /**
     * @var ?string $entryComment
     */
    #[JsonProperty('EntryComment')]
    public ?string $entryComment;

    /**
     * @var ?string $entryLogo
     */
    #[JsonProperty('EntryLogo')]
    public ?string $entryLogo;

    /**
     * @var ?string $entryName
     */
    #[JsonProperty('EntryName')]
    public ?string $entryName;

    /**
     * @var ?array<PayabliPages> $entryPages
     */
    #[JsonProperty('EntryPages'), ArrayType([PayabliPages::class])]
    public ?array $entryPages;

    /**
     * @var ?string $entrySubtitle
     */
    #[JsonProperty('EntrySubtitle')]
    public ?string $entrySubtitle;

    /**
     * @var ?string $entryTitle
     */
    #[JsonProperty('EntryTitle')]
    public ?string $entryTitle;

    /**
     * @var ?int $idEntry
     */
    #[JsonProperty('IdEntry')]
    public ?int $idEntry;

    /**
     * @var ?PaypointData $paypoint
     */
    #[JsonProperty('Paypoint')]
    public ?PaypointData $paypoint;

    /**
     * @param array{
     *   entryComment?: ?string,
     *   entryLogo?: ?string,
     *   entryName?: ?string,
     *   entryPages?: ?array<PayabliPages>,
     *   entrySubtitle?: ?string,
     *   entryTitle?: ?string,
     *   idEntry?: ?int,
     *   paypoint?: ?PaypointData,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->entryComment = $values['entryComment'] ?? null;
        $this->entryLogo = $values['entryLogo'] ?? null;
        $this->entryName = $values['entryName'] ?? null;
        $this->entryPages = $values['entryPages'] ?? null;
        $this->entrySubtitle = $values['entrySubtitle'] ?? null;
        $this->entryTitle = $values['entryTitle'] ?? null;
        $this->idEntry = $values['idEntry'] ?? null;
        $this->paypoint = $values['paypoint'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
