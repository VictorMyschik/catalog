<?php

return [
    //// Контент.Группы, Номенклатура, Характеристики (https://openapi.wildberries.ru/content/api/ru/)
    'content' => [
        'period_update' => 86400 * 30 * 12 * 2, // 24 hours период обновления данных о товарах
        'host'          => 'https://suppliers-api.wildberries.ru',
        'endpoints'     => [
            // Список номенклатур (НМ)
            'get_cards_list'   => '/content/v2/get/cards/list',
            // Список групп (корневых категорий)
            'get_base_groups'  => '/content/v2/object/parent/all',
            // Список групп (дочерних категорий)
            'get_child_groups' => '/content/v2/object/all',
            // Характеристики товара
            'get_attributes'   => '/content/v2/object/charcs/%s',
        ],
        'tokens'        => [
            env('WB_TOKEN_1'),
            env('WB_TOKEN_2'),
        ]
    ],

    'statistics'       => [
        'host'      => 'https://statistics-api.wildberries.ru',
        'endpoints' => [
            // Статистика
            'orders' => '/api/v1/supplier/orders',
            'sales'  => '/api/v1/supplier/sales',
        ],
    ],
    'promotion'        => [
        'host'      => 'https://advert-api.wildberries.ru',
        'endpoints' => [
            // Список рекламных компаний
            'company_list' => '/adv/v1/promotion/count',
            // Показы по рекламным компаниям
            'fullstats'    => '/adv/v2/fullstats',
        ],
    ],
    'analytics'        => [
        'host'      => 'https://seller-analytics-api.wildberries.ru',
        'endpoints' => [
            // Получение статистики КТ по дням по выбранным nmID
            'statistic' => '/api/v2/nm-report/detail/history',
        ],
    ],

    // Общий путь для сохранения файлов/картинок и т.д.
    'global_file_path' => 'wildberries',
];
