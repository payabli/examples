<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;

/**
 * The transaction's response data.
 */
class QueryResponseData extends JsonSerializableType
{
    /**
     * @var ?string $authcode
     */
    #[JsonProperty('authcode')]
    public ?string $authcode;

    /**
     * @var ?string $avsresponse Text code describing the result for address validation (applies only for card transactions).
     */
    #[JsonProperty('avsresponse')]
    public ?string $avsresponse;

    /**
     * @var ?string $avsresponseText Text code describing the result for address validation (applies only for card transactions).
     */
    #[JsonProperty('avsresponse_text')]
    public ?string $avsresponseText;

    /**
     * @var ?string $cvvresponse Text code describing the result for CVV validation (applies only for card transactions).
     */
    #[JsonProperty('cvvresponse')]
    public ?string $cvvresponse;

    /**
     * @var ?string $cvvresponseText Text code describing the result for CVV validation (applies only for card transactions).
     */
    #[JsonProperty('cvvresponse_text')]
    public ?string $cvvresponseText;

    /**
     * @var ?string $emvAuthResponseData EMV authorization response data, applicable for card transactions.
     */
    #[JsonProperty('emv_auth_response_data')]
    public ?string $emvAuthResponseData;

    /**
     * @var ?string $orderid
     */
    #[JsonProperty('orderid')]
    public ?string $orderid;

    /**
     * @var ?string $response Response text for operation: 'Success' or 'Declined'.
     */
    #[JsonProperty('response')]
    public ?string $response;

    /**
     * @var ?string $responseCode Internal result code processing the transaction. Value 1 indicates successful operation, values 2 and 3 indicate errors.
     */
    #[JsonProperty('response_code')]
    public ?string $responseCode;

    /**
     * @var ?string $responseCodeText Text describing the result. If resultCode = 1, will return 'Approved' or a general success message. If resultCode = 2 or 3, will contain the cause of the decline.
     */
    #[JsonProperty('response_code_text')]
    public ?string $responseCodeText;

    /**
     * @var ?string $responsetext Text describing the result. If resultCode = 1, will return 'Approved' or a general success message. If resultCode = 2 or 3, will contain the cause of the decline.
     */
    #[JsonProperty('responsetext')]
    public ?string $responsetext;

    /**
     * @var ?string $transactionid The transaction identifier in Payabli.
     */
    #[JsonProperty('transactionid')]
    public ?string $transactionid;

    /**
     * @var ?string $type Type of transaction or operation.
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @param array{
     *   authcode?: ?string,
     *   avsresponse?: ?string,
     *   avsresponseText?: ?string,
     *   cvvresponse?: ?string,
     *   cvvresponseText?: ?string,
     *   emvAuthResponseData?: ?string,
     *   orderid?: ?string,
     *   response?: ?string,
     *   responseCode?: ?string,
     *   responseCodeText?: ?string,
     *   responsetext?: ?string,
     *   transactionid?: ?string,
     *   type?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->authcode = $values['authcode'] ?? null;
        $this->avsresponse = $values['avsresponse'] ?? null;
        $this->avsresponseText = $values['avsresponseText'] ?? null;
        $this->cvvresponse = $values['cvvresponse'] ?? null;
        $this->cvvresponseText = $values['cvvresponseText'] ?? null;
        $this->emvAuthResponseData = $values['emvAuthResponseData'] ?? null;
        $this->orderid = $values['orderid'] ?? null;
        $this->response = $values['response'] ?? null;
        $this->responseCode = $values['responseCode'] ?? null;
        $this->responseCodeText = $values['responseCodeText'] ?? null;
        $this->responsetext = $values['responsetext'] ?? null;
        $this->transactionid = $values['transactionid'] ?? null;
        $this->type = $values['type'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
