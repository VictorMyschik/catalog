<?php

use App\Services\Catalog\ImportOnlinerService;
use App\Services\Elasticsearch\ESArticlesService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMy(): void
    {
        /** @var ESArticlesService $client */
        $client = app(ESArticlesService::class);
        self::assertTrue($client->ping());
    }

    public function testMyUpdate()
    {
        /** @var ImportOnlinerService $service */
        $service = app(ImportOnlinerService::class);
        $service->updateCatalogGoods();
    }

    public function testMyJson()
    {
        $data = <<<JSON
{
    "product": {
        "id": 4272964,
        "key": "82xq00b5ps",
        "parent_key": "82xq007brk",
        "schema": {
            "id": 193,
            "key": "notebook",
            "name": "Ноутбуки"
        },
        "manufacturer": {
            "id": 2285,
            "key": "lenovo",
            "name": "Lenovo"
        },
        "name": "IdeaPad Slim 3 15AMN8 82XQ00B5PS",
        "full_name": "Lenovo IdeaPad Slim 3 15AMN8 82XQ00B5PS",
        "name_prefix": "Ноутбук",
        "extended_name": "Ноутбук Lenovo IdeaPad Slim 3 15AMN8 82XQ00B5PS",
        "status": "active",
        "images": {
            "header": "https://imgproxy.onliner.by/Ka0WPh8OyEDadTSjlz9b-_ALI59ZyAdrO3nff2ZD0tQ/w:170/h:250/z:2/f:jpg/aHR0cHM6Ly9jb250/ZW50Lm9ubGluZXIu/YnkvY2F0YWxvZy9k/ZXZpY2Uvb3JpZ2lu/YWwvMTI4YjNmMjhk/MTI0YmNkZWQxYWUz/NTc0OWUyMjVhZjAu/anBlZw"
        },
        "description": "15.6\" 1920 x 1080, IPS, 60 Гц, AMD Ryzen 3 7320U, 8 ГБ LPDDR5, SSD 256 ГБ, видеокарта встроенная, без ОС, цвет крышки серый, аккумулятор 47 Вт·ч",
        "description_list": [
            "15.6\" 1920 x 1080, IPS, 60 Гц",
            "AMD Ryzen 3 7320U",
            "8 ГБ LPDDR5",
            "SSD 256 ГБ",
            "видеокарта встроенная",
            "без ОС",
            "цвет крышки серый",
            "аккумулятор 47 Вт·ч"
        ],
        "micro_description": "1920 x 1080 IPS, AMD Ryzen 3 7320U, 8 ГБ, SSD 256 ГБ, встроенная, без ОС, цвет крышки серый",
        "micro_description_list": [
            "1920 x 1080 IPS",
            "AMD Ryzen 3 7320U",
            "8 ГБ",
            "SSD 256 ГБ",
            "встроенная",
            "без ОС",
            "цвет крышки серый"
        ],
        "html_url": "https://catalog.onliner.by/notebook/lenovo/82xq00b5ps",
        "reviews": {
            "rating": 40,
            "count": 1,
            "html_url": "https://catalog.onliner.by/notebook/lenovo/82xq00b5ps/reviews",
            "url": "https://catalog.api.onliner.by/products/82xq00b5ps/reviews"
        },
        "review_url": "https://tech.onliner.by/2023/08/15/gajd-po-noutbukam-lenovo",
        "color_code": "808080",
        "prices": {
            "price_min": {
                "amount": "1399.00",
                "currency": "BYN",
                "converted": {
                    "BYN": {
                        "amount": "1399.00",
                        "currency": "BYN"
                    }
                }
            },
            "price_max": {
                "amount": "1749.50",
                "currency": "BYN",
                "converted": {
                    "BYN": {
                        "amount": "1749.50",
                        "currency": "BYN"
                    }
                }
            },
            "offers": {
                "count": 35
            },
            "html_url": "https://catalog.onliner.by/notebook/lenovo/82xq00b5ps/prices",
            "url": "https://shop.api.onliner.by/products/82xq00b5ps/positions"
        },
        "max_installment_terms": null,
        "max_cobrand_cashback": {
            "percentage": 5,
            "label": "5% на «Клевер»"
        },
        "sale": {
            "is_on_sale": false,
            "discount": 0,
            "min_prices_median": {
                "amount": "1410.00",
                "currency": "BYN"
            },
            "subscribed": false,
            "can_be_subscribed": true
        },
        "second": {
            "offers_count": 0,
            "min_price": null,
            "max_price": null
        },
        "forum": {
            "topic_id": 0,
            "topic_url": null,
            "post_url": "https://forum.onliner.by/posting.php?mode=newtopic&f=65&device=82xq00b5ps",
            "replies_count": null
        },
        "url": "https://catalog.api.onliner.by/products/82xq00b5ps",
        "advertise": null,
        "stickers": [
            {
                "type": "review",
                "label": "Обзор",
                "text": "",
                "color": "056986",
                "bg_color": "CDF5F8",
                "colors": {
                    "light": {
                        "bg": "CDF5F8",
                        "text": "056986"
                    },
                    "dark": {
                        "bg": "3303CDDA",
                        "text": "FF056986"
                    }
                },
                "html_url": "https://tech.onliner.by/2023/08/15/gajd-po-noutbukam-lenovo"
            }
        ],
        "prime_info": {
            "available": false
        },
        "in_bookmarks": false,
        "by_parts_info": {
            "max_term": 48,
            "monthly_payment": {
                "amount": "42.50",
                "currency": "BYN"
            },
            "installment": {
                "periods": [
                    3
                ]
            }
        }
    },
    "type": "Ноутбуки"
}
JSON;

        $product = @json_decode($data, true);

        if ($product['html_url'] ?? null) {
            // Найден новый товар - постановка задачи на скачивание
            $r = 2;
        } else {
            Log::info('Новый товар ' . $product['id'] . " без HTML ссылки", [
                'product' => $product,
            ]);
        }
    }
}