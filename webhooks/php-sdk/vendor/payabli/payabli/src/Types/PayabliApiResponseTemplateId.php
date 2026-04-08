<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;

class PayabliApiResponseTemplateId extends JsonSerializableType
{
    /**
     * @var ?bool $isSuccess
     */
    #[JsonProperty('isSuccess')]
    public ?bool $isSuccess;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @var ?int $responseCode
     */
    #[JsonProperty('responseCode')]
    public ?int $responseCode;

    /**
     * @var (
     *    string
     *   |int
     * )|null $responseData When the operation is successful, contains the template's ID.
     */
    #[JsonProperty('responseData'), Union('string', 'integer', 'null')]
    public string|int|null $responseData;

    /**
     * @var string $responseText
     */
    #[JsonProperty('responseText')]
    public string $responseText;

    /**
     * @param array{
     *   responseText: string,
     *   isSuccess?: ?bool,
     *   pageIdentifier?: ?string,
     *   responseCode?: ?int,
     *   responseData?: (
     *    string
     *   |int
     * )|null,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->isSuccess = $values['isSuccess'] ?? null;
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
        $this->responseCode = $values['responseCode'] ?? null;
        $this->responseData = $values['responseData'] ?? null;
        $this->responseText = $values['responseText'];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
