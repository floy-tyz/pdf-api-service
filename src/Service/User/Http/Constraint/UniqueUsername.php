<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\User\Http\Constraint;

use App\Service\User\Http\Validator\UniqueUsernameValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class UniqueUsername extends Constraint
{
    public string $message = 'Пользователь с таким логином уже существует';

    public function validatedBy(): string
    {
        return UniqueUsernameValidator::class;
    }
}