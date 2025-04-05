<?php

namespace App\Service\Process\Map;

class ProcessContextParametersMap
{
    const string MERGE = 'merge';
    const string OPTIMIZE = 'optimize';
    const string ORIENTATION = 'orientation';
//    const array WATERMARK = ['key' => 'watermark', 'value' => $this->getSome(...)];

    const array SUPPORTED_PARAMETERS = [
        ProcessContextParametersMap::MERGE => [
            'name' => 'Объединить',
            'description' => 'Конвертировать все изображения в один PDF-файл',
            'type' => 'checkbox',
            'values' => [
                true,
                false,
            ],
            'default' => true,
        ],
        ProcessContextParametersMap::OPTIMIZE => [
            'name' => 'Оптимизация',
            'description' => 'Конвертация в FDF-файл - занимает меньше места, чем PDF, так как хранит только данные для заполнения '
                . 'полей формы, без текста, графики и других элементов исходного документа, которые присутствуют в PDF.',
            'type' => 'checkbox',
            'values' => [
                true,
                false,
            ],
            'default' => false,
        ],
        ProcessContextParametersMap::ORIENTATION => [
            'name' => 'Ориентация страницы',
            'type' => 'radiobutton',
            'values' => [
                'vertical' => [
                    'name' => 'Вертикальный',
                    'image' => 'base64',
                ],
                'scenery' => [
                    'name' => 'Пейзаж',
                    'image' => 'base64',
                ],
            ],
            'default' => 'vertical',
        ],
    ];
}