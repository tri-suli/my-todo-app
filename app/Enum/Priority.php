<?php

declare(strict_types=1);

namespace App\Enum;

/**
 * Class Priority
 *
 * This class represents a priority level.
 *
 */
enum Priority: int
{
    /**
     * Represents a case of type Keep In View with value 0.
     *
     * This case is used in a switch statement to perform specific actions
     * when the value being switched on is equal to 0 and of type KIV.
     *
     */
    case KIV = 0;

    /**
     * Represents a case of type Urgent with value 1.
     *
     * This case is used in a switch statement to perform specific actions
     * when the value being switched on is equal to 1 and of type Urgent.
     *
     */
    case URGENT = 1;

    /**
     * Represents a case of type High with value 2.
     *
     * This case is used in a switch statement to perform specific actions
     * when the value being switched on is equal to 2.
     *
     */
    case HIGH = 2;

    /**
     * Represents a case of type Normal with value 3.
     *
     * This case is used in a switch statement to perform specific actions
     * when the value being switched on is equal to 3 and of type Normal.
     *
     */
    case NORMAL = 3;

    /**
     * Represents a case of type Low with value 4.
     *
     * This case is used in a switch statement to perform specific actions
     * when the value being switched on is equal to 4 and of type Low.
     *
     */
    case LOW = 4;

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
     * Get the label for the priority case value.
     *
     * @return string The label corresponding to the value.
     */
    public function label(): string
    {
        return match ($this) {
            self::URGENT => 'Urgent',
            self::HIGH => 'High',
            self::NORMAL => 'Normal',
            self::LOW => 'Low',
            default => 'Keep In View'
        };
    }
}
