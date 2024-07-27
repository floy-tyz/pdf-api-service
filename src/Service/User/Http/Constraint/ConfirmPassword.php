<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\User\Http\Constraint;

use App\Service\Process\Http\Validator\ValidProcessExtensionValidator;
use App\Service\User\Http\Validator\ConfirmPasswordValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ConfirmPassword extends Constraint
{
    public string $message = 'Пароли не совпадают';
    public string $password;

    public function __construct(string $password)
    {
        parent::__construct(['password' => $password]);

        $this->password = $password;
    }

    public function validatedBy(): string
    {
        return ConfirmPasswordValidator::class;
    }
}