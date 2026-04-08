<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;

class PayabliApiResponse00Responsedatanonobject extends JsonSerializableType
{
    /**
     * @var ?int $responseCode
     */
    #[JsonProperty('responseCode')]
    public ?int $responseCode;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var ?int $roomId Describes the room ID. Only in use on Boarding endpoints, returns `0` when not applicable.
     */
    #[JsonProperty('roomId')]
    public ?int $roomId;

    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @var (
     *    string
     *   |int
     * )|null $responseData
     */
    #[JsonProperty('responseData'), Union('string', 'integer', 'null')]
    public string|int|null $responseData;

    /**
     * @param array{
     *   responseText: string,
     *   responseCode?: ?int,
     *   pageIdentifier?: ?string,
     *   roomId?: ?int,
     *   isSuccess?: ?bool,
     *   responseData?: (
     *    string
     *   |int
     * )|null,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->responseCode = $values['responseCode'] ?? null;
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->roomId = $values['roomId'] ?? null;
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->responseText = $values['responseText'];
        $this->responseData = $values['responseData'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
