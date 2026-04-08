<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\Union;

class PayabliApiResponseNotifications extends JsonSerializableType
{
    /**
     *
     * If `isSuccess` = true, `responseData` contains the notification identifier.
     *
     * If `isSuccess` = false, `responseData` contains the reason for the error.
     *
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
     *    int
     *   |string
     * )|null $responseData When the request was successful, this contains the notification ID, or `nID` used to manage the notification.
     */
    #[JsonProperty('responseData'), Union('integer', 'string', 'null')]
    public int|string|null $responseData;

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
     *    int
     *   |string
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
