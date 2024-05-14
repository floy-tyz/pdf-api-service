<?php

namespace App\Service\File\Request;

use App\Request\RequestValidation;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileApiRequest extends RequestValidation
{
    /**
     * @return UploadedFile
     */
    public function getImage(): UploadedFile
    {
        return $this->request->files->get('image');
    }

    /**
     * @param $data
     * @return array[]
     */
    protected function getConstraint($data)
    {
        return [

            'image' => [

                new Assert\NotBlank([
                    'message' => 'а картинка где?'
                ]),

                new Assert\Image([

//                    'mimeTypes' => [ 'image/jpg', 'image/jpeg', 'image/png', 'image/x-icon', 'image/svg+xml' ],
//                    'mimeTypesMessage' => 'Только jpg, png, svg и ico',

                    'maxSize' => '10M',
                    'maxSizeMessage' => 'Размер файла не должен превышать 10Мб',

//                    'detectCorrupted' => true,
//                    'corruptedMessage' => 'Изображение повреждено',

                ]),

                new AppAssert\FileWithPhpConstraint([

                ])
            ],

        ];
    }
}