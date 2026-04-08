<?php

namespace Payabli\Types;

use Payabli\Core\Json\JsonSerializableType;
use Payabli\Core\Json\JsonProperty;
use Payabli\Core\Types\ArrayType;

/**
 * The response data containing the result of the import operation.
 */
class PayabliApiResponseImportResponseData extends JsonSerializableType
{
    /**
     * @var ?int $added The number of records successfully added.
     */
    #[JsonProperty('added')]
    public ?int $added;

    /**
     * @var ?array<string> $errors List of errors, if any.
     */
    #[JsonProperty('errors'), ArrayType(['string'])]
    public ?array $errors;

    /**
     * @var ?int $rejected The number of records that were rejected.
     */
    #[JsonProperty('rejected')]
    public ?int $rejected;

    /**
     * @param array{
     *   added?: ?int,
     *   errors?: ?array<string>,
     *   rejected?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->added = $values['added'] ?? null;
        $this->errors = $values['errors'] ?? null;
        $this->rejected = $values['rejected'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
