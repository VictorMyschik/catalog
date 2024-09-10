<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Response\Components;

/**
 * @OA\Schema(
 *     schema="LanguageComponent",
 *     required={"code", "title"},
 *     @OA\Property(
 *         property="code",
 *         type="string",
 *         description="The code of the language"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="The title of the language"
 *     ),
 * )
 */
final readonly class LanguageComponent
{
    public function __construct(
        public string $code,
        public string $title,
    ) {}
}
