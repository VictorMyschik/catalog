<?php

namespace App\Orchid\Layouts\Lego;

use App\Models\User;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\ViewField;

class InfoPanelLayout
{
    public static function getButtons(object $model): Group
    {
        $user = 'Не указан';
        if ($userModel = User::find($model->user_id)) {
            $user = $userModel->name . ' (' . $userModel->email . ')';
        }

        return Group::make([
            ViewField::make('')->view('admin.catalog.h6')->value(['text' => 'Пользователь: ' . $user]),
            ViewField::make('')->view('admin.catalog.h6')->value(['text' => ' | ']),
            ViewField::make('')->view('admin.catalog.h6')->value(['text' => 'Обновлено: ' . $model->updated_at?->format('d.m.Y H:i')]),
        ])->autoWidth();
    }
}
