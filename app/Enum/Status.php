<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Class Priority
 *
 * This class represents a priority level.
 *
 */
enum Status: int
{
    /**
     * Represents a completed status.
     *
     * @var int COMPLETED Declare a constant representing a completed status with a value of 1.
     */
    case COMPLETED = 1;

    /**
     * Represents a case of type Incomplete with value 0.
     *
     * This case is used in a switch statement to perform specific actions
     * when the value being switched on is equal to 0 and of type Incomplete.
     *
     */
    case IN_COMPLETED = 0;

    /**
     * Convert the Enum values to an associative array.
     *
     * @return array The Enum values as an associative array, where the keys are the labels
     * and the values are the corresponding values.
     */
    public static function all(): array
    {
        return array_combine(
            array_map(fn (self $enum) => $enum->label(), self::cases()),
            array_map(fn (self $enum) => $enum->value, self::cases())
        );
    }

    /**
     * Get the label associated with the Enum value.
     *
     * @return string The label of the Enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::COMPLETED => 'Completed',
            default => 'In-Completed'
        };
    }
}
