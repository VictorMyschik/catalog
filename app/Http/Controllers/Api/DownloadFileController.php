<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DownloadFileController extends Controller
{
    // Скачивание временных файлов
    public function download(Request $request)
    {
        if (!Storage::exists($request->get('file'))) {
            return throw new NotFoundHttpException('File not found');
        }

        return Response::download(Storage::path($request->get('file')), $request->get('name'))->deleteFileAfterSend();
    }
}
