<?php

namespace App\Orchid\Layouts\Lego;

use App\Orchid\DTO\ButtonDTO;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class ActionsModelLayout
{
    /**
     * @param ButtonDTO|ModalToggle[] $buttons
     * @return Group
     */
    public static function getActionButtons(array $buttons): Group
    {
        $groupButtons = [];

        foreach ($buttons as $button) {
            if ($button instanceof ButtonDTO) {
                $buttonObject = Button::make($button->type);
                if (!is_null($button->confirm)) {
                    $buttonObject->confirm($button->confirm);
                }
                $buttonObject->class('btn btn-sm')
                    ->name($button->title)
                    ->method($button->methodName)
                    ->type($button->color)
                    ->novalidate($button->novalidate)
                    ->parameters($button->parameters)
                    ->icon($button->icon);
                $groupButtons[] = $buttonObject;
            } else {
                $groupButtons[] = $button;
            }
        }
        return Group::make($groupButtons)->autoWidth();
    }
}
