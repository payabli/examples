<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

class PayabliCredentialsPascal extends JsonSerializableType
{
    /**
     * @var ?string $service
     */
    #[JsonProperty('Service')]
    public ?string $service;

    /**
     * @var ?int $mode
     */
    #[JsonProperty('Mode')]
    public ?int $mode;

    /**
     * @var ?float $minTicket
     */
    #[JsonProperty('MinTicket')]
    public ?float $minTicket;

    /**
     * @var ?float $maxTicket
     */
    #[JsonProperty('MaxTicket')]
    public ?float $maxTicket;

    /**
     * @var ?float $cfeeFix
     */
    #[JsonProperty('CfeeFix')]
    public ?float $cfeeFix;

    /**
     * @var ?float $cfeeFloat
     */
    #[JsonProperty('CfeeFloat')]
    public ?float $cfeeFloat;

    /**
     * @var ?float $cfeeMin
     */
    #[JsonProperty('CfeeMin')]
    public ?float $cfeeMin;

    /**
     * @var ?float $cfeeMax
     */
    #[JsonProperty('CfeeMax')]
    public ?float $cfeeMax;

    /**
     * @var ?string $accountId
     */
    #[JsonProperty('AccountId')]
    public ?string $accountId;

    /**
     * @var ?int $referenceId
     */
    #[JsonProperty('ReferenceId')]
    public ?int $referenceId;

    /**
     * @var ?bool $acceptSameDayAch
     */
    #[JsonProperty('acceptSameDayACH')]
    public ?bool $acceptSameDayAch;

    /**
     * @var ?string $currency The default currency for the paypoint, either `USD` or `CAD`.
     */
    #[JsonProperty('Currency')]
    public ?string $currency;

    /**
     * @param array{
     *   service?: ?string,
     *   mode?: ?int,
     *   minTicket?: ?float,
     *   maxTicket?: ?float,
     *   cfeeFix?: ?float,
     *   cfeeFloat?: ?float,
     *   cfeeMin?: ?float,
     *   cfeeMax?: ?float,
     *   accountId?: ?string,
     *   referenceId?: ?int,
     *   acceptSameDayAch?: ?bool,
     *   currency?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->service = $values['service'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->minTicket = $values['minTicket'] ?? null;
        $this->maxTicket = $values['maxTicket'] ?? null;
        $this->cfeeFix = $values['cfeeFix'] ?? null;
        $this->cfeeFloat = $values['cfeeFloat'] ?? null;
        $this->cfeeMin = $values['cfeeMin'] ?? null;
        $this->cfeeMax = $values['cfeeMax'] ?? null;
        $this->accountId = $values['accountId'] ?? null;
        $this->referenceId = $values['referenceId'] ?? null;
        $this->acceptSameDayAch = $values['acceptSameDayAch'] ?? null;
        $this->currency = $values['currency'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
