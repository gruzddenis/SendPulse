<?php

namespace App\Exceptions;

/**
 * Class ErrorMessages
 *
 * @package App\Exceptions
 */
final class ErrorMessages
{
    public const INVALID_EMAIL = 'Invalid Email';
    public const EMAIL_EXIST = 'Email exist';
    public const USER_NOT_FOUND = 'User not found';
    public const END_DATE_CANNOT_BE_LESS_CURRENT_DATE = 'End date cannot be less than the current date';
    public const UNAUTHORIZED = 'Unauthorized';
    public const INVALID_PASSWORD = 'Login or password incorrect';
    public const FIELD_TITLE_CAN_NOT_BE_EMPTY = 'Title cannot be empty';
    public const FIELD_TEXT_CAN_NOT_BE_EMPTY = 'Text cannot be empty';
}
