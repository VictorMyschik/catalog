<?php

declare(strict_types=1);

use App\Models\Catalog\Onliner\OnCatalogGood;
use App\Services\Catalog\Onliner\ImportOnlinerService;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class MyTest extends TestCase
{
    public function testMyUpdate()
    {
        /** @var ImportOnlinerService $service */
        $service = app(ImportOnlinerService::class);
        $service->reloadGoods(OnCatalogGood::loadByOrDie(1));
    }
    // Otzt-4nTjf698a3Mshl122Z-IkvnlVoBQAFWUduWyAo
    // 1fd96342e4d1bb2c4722ac81f1106509
    public function testMy(): void
    {

        $data = <<<TEXT
{
                        main: aP,
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002Fi7sahiUMjC_8lRqQY7Xi2aSdgGRYUr0rFNLbyrsHJoE\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMGQxYzM4Y2MzZGQw\u002FZmFiMzgxNmQ1YzYw\u002FMzNiN2JlOGQuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FOtzt-4nTjf698a3Mshl122Z-IkvnlVoBQAFWUduWyAo\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMGQxYzM4Y2MzZGQw\u002FZmFiMzgxNmQ1YzYw\u002FMzNiN2JlOGQuanBn"
                    },{
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FbeZe_08biW_LCQRJ3ZoyZRK0p-Qr2Bh2NENg-XDZtjI\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZjkxNjg5YmQ5YTA0\u002FNjc1MDMzMzg5Nzhh\u002FNTJhZWMyOWYuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002F0UEL6JF5tzheD94Dmq3txkAzVIfXtKQ3C3PAtHLo9YU\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZjkxNjg5YmQ5YTA0\u002FNjc1MDMzMzg5Nzhh\u002FNTJhZWMyOWYuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FF21jQPY0G9c8T5vwiyjywga1zJ2byx5jhPhr7rtzmL4\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZjkxNjg5YmQ5YTA0\u002FNjc1MDMzMzg5Nzhh\u002FNTJhZWMyOWYuanBn"
                    }
TEXT;


        // Pattern to match URLs within retina
        $url_pattern = '/(\w+):\s*"(https:\\\\u002F\\\\u002F[^"]+)"/';
        // Find all matches
        preg_match_all($url_pattern, $data, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $key = $match[1]; // main, retina, or thumbnail
            $url = json_decode('"' . $match[2] . '"'); // Decode Unicode escape sequences
            $result[] = [
                'type' => $key,
                'url' => $url
            ];
        }



        $url = 'https:\u002F\u002Fimgproxy.onliner.by\u002Fi7sahiUMjC_8lRqQY7Xi2aSdgGRYUr0rFNLbyrsHJoE\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMGQxYzM4Y2MzZGQw\u002FZmFiMzgxNmQ1YzYw\u002FMzNiN2JlOGQuanBn"';

        // Заменяем \u002F на обычный слеш /
        $cleanedUrl = str_replace('\u002F', '/', $url);

        // Декодируем оставшиеся закодированные части (например, Base64 в конце)
        $decodedUrl = urldecode($cleanedUrl);

        $content = file_get_contents('https://catalog.onliner.by/notebook/honor/5301akxl');
        $crawler = new Crawler($content);

        // Extarct data[] from script tag
        $data = $crawler->filter('script')->each(function (Crawler $node) {
            if (str_contains($node->text(), 'window.__NUXT__') && str_contains($node->text(), 'imgproxy')) {
                return $node->text();
            }

            return null;
        });

        $data = array_filter($data);
        $scriptText = reset($data);

        $url_pattern = '/(\w+):\s*"(https:\\\\u002F\\\\u002F[^"]+)"/';
        // Find all matches
        preg_match_all($url_pattern, $scriptText, $matches, PREG_SET_ORDER);

        $result = [];
        foreach ($matches as $match) {
            $key = $match[1]; // main, retina, or thumbnail
            $url = json_decode('"' . $match[2] . '"');
            if ($key === 'retina'){
                $result[] = $url;
            }
        }
    }

    private function example(): string
    {
        return <<<HTML
[{
                        main: aP,
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002Fi7sahiUMjC_8lRqQY7Xi2aSdgGRYUr0rFNLbyrsHJoE\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMGQxYzM4Y2MzZGQw\u002FZmFiMzgxNmQ1YzYw\u002FMzNiN2JlOGQuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FOtzt-4nTjf698a3Mshl122Z-IkvnlVoBQAFWUduWyAo\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMGQxYzM4Y2MzZGQw\u002FZmFiMzgxNmQ1YzYw\u002FMzNiN2JlOGQuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FbeZe_08biW_LCQRJ3ZoyZRK0p-Qr2Bh2NENg-XDZtjI\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZjkxNjg5YmQ5YTA0\u002FNjc1MDMzMzg5Nzhh\u002FNTJhZWMyOWYuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002F0UEL6JF5tzheD94Dmq3txkAzVIfXtKQ3C3PAtHLo9YU\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZjkxNjg5YmQ5YTA0\u002FNjc1MDMzMzg5Nzhh\u002FNTJhZWMyOWYuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FF21jQPY0G9c8T5vwiyjywga1zJ2byx5jhPhr7rtzmL4\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZjkxNjg5YmQ5YTA0\u002FNjc1MDMzMzg5Nzhh\u002FNTJhZWMyOWYuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FJVajA0b2kOHRmxy4IDO4wThU1APiEPiCaVNPbE17YPk\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FYWMwN2RhMTQ1NDEz\u002FMzFiMmUzOWQyMjZk\u002FOWUzNjVkYWUuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FaMbLUTMaz-Pu3Nm5bdtgKHjXGdD8gt8ThSvE7rCz8n8\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FYWMwN2RhMTQ1NDEz\u002FMzFiMmUzOWQyMjZk\u002FOWUzNjVkYWUuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002F9rbo0mu0im2zJfQ0Drx9ePS5Zcd_ltBNOD3ioG6uW0Y\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FYWMwN2RhMTQ1NDEz\u002FMzFiMmUzOWQyMjZk\u002FOWUzNjVkYWUuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FLDwmzlHusDWCejL4fVG1RFwde68bqJYHpLpWDLrZzv4\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNmE1MWUxNTY4NWQ2\u002FZDU1NjBmOGFhYzFm\u002FZjk0ZTM4MzMuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FRx4nqRhgYpQwW-v7dCfAjrAG2nLzfkT2McPU03_9mR4\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNmE1MWUxNTY4NWQ2\u002FZDU1NjBmOGFhYzFm\u002FZjk0ZTM4MzMuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002F5bFYLyg9hd6N9TZ4FPIvnzQWHnD4mb8UTJ2PIag9VyA\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNmE1MWUxNTY4NWQ2\u002FZDU1NjBmOGFhYzFm\u002FZjk0ZTM4MzMuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FoRqMlB0xmi40oSf1hHJN4z1iPT6EEx3Gjm_KE-AXYeg\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FYzJkZGRmYWU2ODJl\u002FZTAwMmY5NTg5NTI1\u002FNzhiY2NhNWQuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FiSmLe6yjtr19oVWBnt5Y0gqPSOLwerOeSMm8TccTpHw\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FYzJkZGRmYWU2ODJl\u002FZTAwMmY5NTg5NTI1\u002FNzhiY2NhNWQuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FRcVAuhv5vZQgduIrQdfGLHlo53CNR0Xl59YfYi4hpCw\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FYzJkZGRmYWU2ODJl\u002FZTAwMmY5NTg5NTI1\u002FNzhiY2NhNWQuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002F8mHc7EDZS07UCAxJoG89rAmyzgju8wolwfpz1Ue-ZC4\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMDMzODk2NjMxNGFi\u002FYWY4MDFiZmJmZDEy\u002FNGJjMmYzMGIuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FWXFpgvgb-jFKIjC7bwJPTpn88Hr4ZmcwalU-IPpkmCU\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMDMzODk2NjMxNGFi\u002FYWY4MDFiZmJmZDEy\u002FNGJjMmYzMGIuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FdaDrqBe3oXewlX8p-k78Lws9at84cTd9WbDH0h7FCko\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMDMzODk2NjMxNGFi\u002FYWY4MDFiZmJmZDEy\u002FNGJjMmYzMGIuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FpeJ7YAa4EzZUzOV99gjXIW_DLIe6DHcmLxjYL0KQW2M\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNDA2NzUzYzA2NzEx\u002FZWJhY2U2ODZmNDYy\u002FZjk1ZjQwNWEuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FN_oBT8THvEeJa846l_QWQ1ti5GGta5g_hvCPOI7iQMs\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNDA2NzUzYzA2NzEx\u002FZWJhY2U2ODZmNDYy\u002FZjk1ZjQwNWEuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FDAwzvYLD3aDj-_w83gs_FDDbjXzfFzWXnRFpNtH_SQ4\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNDA2NzUzYzA2NzEx\u002FZWJhY2U2ODZmNDYy\u002FZjk1ZjQwNWEuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002Fv4pu_ZewyThb3wY65gIcYYA7hjwmokX-S9YjUYglR_w\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FODkyYzRjNWRiZDZi\u002FMTczZWJkNTMyZjYx\u002FMjQzMzM1MTMuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002F0WdfCTnj0_Tb_Y3ZJRlq-mPwRPkl_TK7Hu88-WWsGf4\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FODkyYzRjNWRiZDZi\u002FMTczZWJkNTMyZjYx\u002FMjQzMzM1MTMuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002F2p2SPdAFKu9HR7ag43OLP8YWCqwFKOsX3mtvT047p4s\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FODkyYzRjNWRiZDZi\u002FMTczZWJkNTMyZjYx\u002FMjQzMzM1MTMuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002F1mfHc9pQpYa3_1xH7Fu0q09FSdKrjtdfaknc7A2MzeM\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNjQ3ZWFkOTgxNmE1\u002FZTRhOWNiNzdlYjBh\u002FZDgwNjI4ZjYuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002Fud1-GvWXrGjeJeWv5rVOkLYEqidrQfaFBXDtC6RFoCY\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNjQ3ZWFkOTgxNmE1\u002FZTRhOWNiNzdlYjBh\u002FZDgwNjI4ZjYuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FUtT5CM4ps4Vtu8KQcwk2w57BlrxmBNs81qTluyPuRO0\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FNjQ3ZWFkOTgxNmE1\u002FZTRhOWNiNzdlYjBh\u002FZDgwNjI4ZjYuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FfmrI6HNrIfvEyXW8nrgptP2IyLp7pPnFY49JH0KnlSY\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FM2E3NDk3MDU2NDY4\u002FYjg1NDViM2Y1NjQ1\u002FMDZiYzgzODMuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FTAgEm9M-zacRWUWJdVkeIUIET1HI4omMobb7CYOeSGc\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FM2E3NDk3MDU2NDY4\u002FYjg1NDViM2Y1NjQ1\u002FMDZiYzgzODMuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FTYVGl-8I4WSugcdz_cuMt7bXGVIlNJiT-f-msjMXYck\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FM2E3NDk3MDU2NDY4\u002FYjg1NDViM2Y1NjQ1\u002FMDZiYzgzODMuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002Fa8fLgTj1ZTYN31ON4H73FiOcJ7her3iMuMUnY8CzeD0\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMTA2MmM0NWYxOTEy\u002FOTNiYjU5MzE0MDg2\u002FZGExNDlhNDUuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FwM0SaAy9TCD1AdItDFeGOaeBexUycDv-O9Ie35SKR2g\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMTA2MmM0NWYxOTEy\u002FOTNiYjU5MzE0MDg2\u002FZGExNDlhNDUuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FWctSRzbEN7cY7N3xNYvZyojUUwfX6IHlLvk-8E8MK9U\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMTA2MmM0NWYxOTEy\u002FOTNiYjU5MzE0MDg2\u002FZGExNDlhNDUuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FPlAXSghIGBVqCOVCLXSJfMh41lxWA1-n1Fa9L5cKvRU\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMTJkYmQ0Y2RjMzZi\u002FNTIyYTk3MGY2ODc2\u002FYmQ3Mjc2OTUuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002Fz8zTjMihLXU2uTOii3o3N3x9b_A4PWgvu2Cn1Bwe_W4\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMTJkYmQ0Y2RjMzZi\u002FNTIyYTk3MGY2ODc2\u002FYmQ3Mjc2OTUuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FIzCbNAEgsRjeY526B9Cct92cd-mb8VnuxA2nu5QL11A\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FMTJkYmQ0Y2RjMzZi\u002FNTIyYTk3MGY2ODc2\u002FYmQ3Mjc2OTUuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002F4RIlsJD65KjxOqVMtmS1HR762Kp9-nTlIjGU36nXBI8\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FM2Y0MjZkZDcwMTg4\u002FNWQxMWIzNDliMGY2\u002FOTJlODQxM2QuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FPeoZ-NDkHliVWMh1hqmQpkfY8NOv4DkVio2tDj5axo8\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FM2Y0MjZkZDcwMTg4\u002FNWQxMWIzNDliMGY2\u002FOTJlODQxM2QuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FI5OpyzHnya-BMjN-eVgWHAdBRgxbghQWi8LCsYjODPc\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FM2Y0MjZkZDcwMTg4\u002FNWQxMWIzNDliMGY2\u002FOTJlODQxM2QuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FgaNof7qyHnpLQXnyKqxeySKhhrBLdd1V4RTm5qbOCKE\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZGU2ZDE1ZTBhZGEz\u002FMTY2MDZiY2JmOWQ0\u002FNmZjZmU0OWQuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FsGlaHcK9ikXWSe7vyJ4Erzhq2otF-JYTp99PXHiJPVI\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZGU2ZDE1ZTBhZGEz\u002FMTY2MDZiY2JmOWQ0\u002FNmZjZmU0OWQuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002Fafej98lRupaEeW09YmAulDUFjPboUc7oQUqRMGUzUiE\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZGU2ZDE1ZTBhZGEz\u002FMTY2MDZiY2JmOWQ0\u002FNmZjZmU0OWQuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002F9Sd4kxGJz95Mora7H6CZhmcrsbh5vgA2yi2NxNROyZM\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZWJhYWRjYTFiMWY0\u002FMmFkNzcyNjM3ZjVi\u002FMWUxM2IzOTUuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FrYv-Yj9dibjon785PCLa14SpKURfSAO4UQaAoTEEc_k\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZWJhYWRjYTFiMWY0\u002FMmFkNzcyNjM3ZjVi\u002FMWUxM2IzOTUuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FY_qKF-LGmbaIklpIOwWcZ53xtVrImfAXRHukwZ7M7iI\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZWJhYWRjYTFiMWY0\u002FMmFkNzcyNjM3ZjVi\u002FMWUxM2IzOTUuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FPpwP_nbKtR8fVZK8FlKPxp6FVG_uaL7pMkR3OtvEex8\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZGU4NjczOTYxYWIy\u002FZjYzZWE5YzljYjNj\u002FNzA2ZDgwNjQuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002FI8Dir73noBBYij6qdoWoGD58wqaQHxSv0xq1v6ibBV4\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZGU4NjczOTYxYWIy\u002FZjYzZWE5YzljYjNj\u002FNzA2ZDgwNjQuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002Fz_dOJeptNmsFcp4c3nDgEO-DIhqSudqkCQl0ZjdWiDE\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZGU4NjczOTYxYWIy\u002FZjYzZWE5YzljYjNj\u002FNzA2ZDgwNjQuanBn"
                    }, {
                        main: "https:\u002F\u002Fimgproxy.onliner.by\u002FxIImRQLlhSK2slALo-LC7Yp1xp1s3iit4a9OC3KlcII\u002Fw:700\u002Fh:550\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZTc4ZjQ3OTRkYzlj\u002FMjE5MzQwYTNlY2Qy\u002FNDJmYjNmZmIuanBn",
                        retina: "https:\u002F\u002Fimgproxy.onliner.by\u002Fn1RtjscHCjvmb01lUppyCIGC99tYsod6ss0ZjX3iWFg\u002Fw:700\u002Fh:550\u002Fz:2\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZTc4ZjQ3OTRkYzlj\u002FMjE5MzQwYTNlY2Qy\u002FNDJmYjNmZmIuanBn",
                        thumbnail: "https:\u002F\u002Fimgproxy.onliner.by\u002FUh2HWCpWoFs-3dTgMxMkEjGLIbLSOwvM-KvVN7124Q4\u002Fw:200\u002Fh:200\u002Fex:1\u002Ff:jpg\u002FaHR0cHM6Ly9jb250\u002FZW50Lm9ubGluZXIu\u002FYnkvY2F0YWxvZy9k\u002FZXZpY2UvbGFyZ2Uv\u002FZTc4ZjQ3OTRkYzlj\u002FMjE5MzQwYTNlY2Qy\u002FNDJmYjNmZmIuanBn"
                    }]
HTML;
    }
}
