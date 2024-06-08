<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\Process\Request\Constraint;

use App\Service\Process\Request\Validator\ValidProcessExtensionValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ValidProcessExtension extends Constraint
{
    public string $message = 'Недопустимое расширение для конвертации';
    public string $allowedExtension;

    public function __construct(string $allowedExtension)
    {
        parent::__construct(['allowedExtension' => $allowedExtension]);

        $this->allowedExtension = $allowedExtension;
    }

    public function validatedBy(): string
    {
        return ValidProcessExtensionValidator::class;
    }
}