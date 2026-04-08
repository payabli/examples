<?php

namespace Payabli\CheckCapture\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * Response model for check capture processing.
 */
class CheckCaptureResponse extends JsonSerializableType
{
    /**
     * @var ?string $id Unique ID for the check capture, to be used with the /api/MoneyIn/getpaid endpoint.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var bool $success Indicates whether the check processing was successful.
     */
    #[JsonProperty('success')]
    public bool $success;

    /**
     * @var string $processDate The date and time when the check was processed (ISO 8601 format).
     */
    #[JsonProperty('processDate')]
    public string $processDate;

    /**
     * @var ?string $ocrMicr The OCR-extracted MICR (Magnetic Ink Character Recognition) line from the check.
     */
    #[JsonProperty('ocrMicr')]
    public ?string $ocrMicr;

    /**
     * @var ?string $ocrMicrStatus Status of the MICR extraction process.
     */
    #[JsonProperty('ocrMicrStatus')]
    public ?string $ocrMicrStatus;

    /**
     * @var ?string $ocrMicrConfidence Confidence score for the MICR extraction (0 to 100).
     */
    #[JsonProperty('ocrMicrConfidence')]
    public ?string $ocrMicrConfidence;

    /**
     * @var ?string $ocrAccountNumber The bank account number extracted from the check.
     */
    #[JsonProperty('ocrAccountNumber')]
    public ?string $ocrAccountNumber;

    /**
     * @var ?string $ocrRoutingNumber The bank routing number extracted from the check.
     */
    #[JsonProperty('ocrRoutingNumber')]
    public ?string $ocrRoutingNumber;

    /**
     * @var ?string $ocrCheckNumber The check number extracted from the check.
     */
    #[JsonProperty('ocrCheckNumber')]
    public ?string $ocrCheckNumber;

    /**
     * @var ?string $ocrCheckTranCode The transaction code extracted from the check.
     */
    #[JsonProperty('ocrCheckTranCode')]
    public ?string $ocrCheckTranCode;

    /**
     * @var ?string $ocrAmount The amount extracted via OCR from the check.
     */
    #[JsonProperty('ocrAmount')]
    public ?string $ocrAmount;

    /**
     * @var ?string $ocrAmountStatus Status of the amount extraction process.
     */
    #[JsonProperty('ocrAmountStatus')]
    public ?string $ocrAmountStatus;

    /**
     * @var ?string $ocrAmountConfidence Confidence score for the amount extraction (0 to 100).
     */
    #[JsonProperty('ocrAmountConfidence')]
    public ?string $ocrAmountConfidence;

    /**
     * @var bool $amountDiscrepancyDetected Flag indicating whether there's a discrepancy between the provided amount and the OCR-detected amount.
     */
    #[JsonProperty('amountDiscrepancyDetected')]
    public bool $amountDiscrepancyDetected;

    /**
     * @var bool $endorsementDetected Flag indicating whether an endorsement was detected on the check.
     */
    #[JsonProperty('endorsementDetected')]
    public bool $endorsementDetected;

    /**
     * @var ?array<string> $errors List of error messages that occurred during processing.
     */
    #[JsonProperty('errors'), ArrayType(['string'])]
    public ?array $errors;

    /**
     * @var ?array<string> $messages List of informational messages about the processing.
     */
    #[JsonProperty('messages'), ArrayType(['string'])]
    public ?array $messages;

    /**
     * @var ?string $carLarMatchConfidence Confidence score for the match between Courtesy Amount Recognition (CAR) and Legal Amount Recognition (LAR).
     */
    #[JsonProperty('carLarMatchConfidence')]
    public ?string $carLarMatchConfidence;

    /**
     * @var ?string $carLarMatchStatus Status of the CAR/LAR match.
     */
    #[JsonProperty('carLarMatchStatus')]
    public ?string $carLarMatchStatus;

    /**
     * @var ?string $frontImage Processed front image of the check (Base64-encoded).
     */
    #[JsonProperty('frontImage')]
    public ?string $frontImage;

    /**
     * @var ?string $rearImage Processed rear image of the check (Base64-encoded).
     */
    #[JsonProperty('rearImage')]
    public ?string $rearImage;

    /**
     * Identifier for the type of check.
     * Personal = 1
     * Business = 2
     * Only personal checks are supported for check capture.
     *
     * @var float $checkType
     */
    #[JsonProperty('checkType')]
    public float $checkType;

    /**
     * @var ?string $referenceNumber Reference number for the transaction.
     */
    #[JsonProperty('referenceNumber')]
    public ?string $referenceNumber;

    /**
     * @var ?string $pageIdentifier
     */
    #[JsonProperty('pageIdentifier')]
    public ?string $pageIdentifier;

    /**
     * @param array{
     *   success: bool,
     *   processDate: string,
     *   amountDiscrepancyDetected: bool,
     *   endorsementDetected: bool,
     *   checkType: float,
     *   id?: ?string,
     *   ocrMicr?: ?string,
     *   ocrMicrStatus?: ?string,
     *   ocrMicrConfidence?: ?string,
     *   ocrAccountNumber?: ?string,
     *   ocrRoutingNumber?: ?string,
     *   ocrCheckNumber?: ?string,
     *   ocrCheckTranCode?: ?string,
     *   ocrAmount?: ?string,
     *   ocrAmountStatus?: ?string,
     *   ocrAmountConfidence?: ?string,
     *   errors?: ?array<string>,
     *   messages?: ?array<string>,
     *   carLarMatchConfidence?: ?string,
     *   carLarMatchStatus?: ?string,
     *   frontImage?: ?string,
     *   rearImage?: ?string,
     *   referenceNumber?: ?string,
     *   pageIdentifier?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->id = $values['id'] ?? null;
        $this->success = $values['success'];
        $this->processDate = $values['processDate'];
        $this->ocrMicr = $values['ocrMicr'] ?? null;
        $this->ocrMicrStatus = $values['ocrMicrStatus'] ?? null;
        $this->ocrMicrConfidence = $values['ocrMicrConfidence'] ?? null;
        $this->ocrAccountNumber = $values['ocrAccountNumber'] ?? null;
        $this->ocrRoutingNumber = $values['ocrRoutingNumber'] ?? null;
        $this->ocrCheckNumber = $values['ocrCheckNumber'] ?? null;
        $this->ocrCheckTranCode = $values['ocrCheckTranCode'] ?? null;
        $this->ocrAmount = $values['ocrAmount'] ?? null;
        $this->ocrAmountStatus = $values['ocrAmountStatus'] ?? null;
        $this->ocrAmountConfidence = $values['ocrAmountConfidence'] ?? null;
        $this->amountDiscrepancyDetected = $values['amountDiscrepancyDetected'];
        $this->endorsementDetected = $values['endorsementDetected'];
        $this->errors = $values['errors'] ?? null;
        $this->messages = $values['messages'] ?? null;
        $this->carLarMatchConfidence = $values['carLarMatchConfidence'] ?? null;
        $this->carLarMatchStatus = $values['carLarMatchStatus'] ?? null;
        $this->frontImage = $values['frontImage'] ?? null;
        $this->rearImage = $values['rearImage'] ?? null;
        $this->checkType = $values['checkType'];
        $this->referenceNumber = $values['referenceNumber'] ?? null;
        $this->pageIdentifier = $values['pageIdentifier'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
