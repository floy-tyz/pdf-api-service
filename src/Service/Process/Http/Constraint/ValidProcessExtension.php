<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\Process\Http\Constraint;

use App\Service\Process\Http\Validator\ValidProcessExtensionValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ValidProcessExtension extends Constraint
{
    public string $message = 'Недопустимое расширение для конвертации';
    public string $allowedExtension;

    public function __construct(string $password)
    {
        parent::__construct(['allowedExtension' => $password]);

        $this->allowedExtension = $password;
    }

    public function validatedBy(): string
    {
        return ValidProcessExtensionValidator::class;
    }
}