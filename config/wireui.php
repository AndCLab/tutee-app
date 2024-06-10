<?php

use WireUi\View\Components;

return [
    /*
        |--------------------------------------------------------------------------
        | Icons
        |--------------------------------------------------------------------------
        |
        | The icons config will be used in icon component as default
        | https://heroicons.com
        |
    */
    'icons' => [
        'style' => env('WIREUI_ICONS_STYLE', 'outline'),
    ],

    /*
        |--------------------------------------------------------------------------
        | Modal
        |--------------------------------------------------------------------------
        |
        | The default modal preferences
        |
    */
    'modal' => [
        'zIndex'   => env('WIREUI_MODAL_Z_INDEX', 'z-50'),
        'maxWidth' => env('WIREUI_MODAL_MAX_WIDTH', '2xl'),
        'spacing'  => env('WIREUI_MODAL_SPACING', 'p-4'),
        'align'    => env('WIREUI_MODAL_ALIGN', 'start'),
        'blur'     => env('WIREUI_MODAL_BLUR', false),
    ],

    /*
        |--------------------------------------------------------------------------
        | Card
        |--------------------------------------------------------------------------
        |
        | The default card preferences
        |
    */
    'card' => [
        'padding' => env('WIREUI_CARD_PADDING', 'px-2 py-5 md:px-4'),
        'shadow'  => env('WIREUI_CARD_SHADOW', 'shadow-md'),
        'rounded' => env('WIREUI_CARD_ROUNDED', 'rounded-lg'),
        'color'   => env('WIREUI_CARD_COLOR', 'bg-white dark:bg-secondary-800'),
    ],

    /*
        |--------------------------------------------------------------------------
        | Components
        |--------------------------------------------------------------------------
        |
        | List with WireUI components.
        | Change the alias to call the component with a different name.
        | Extend the component and replace your changes in this file.
        | Remove the component from this file if you don't want to use.
        |
     */
    'components' => [
        'avatar' => [
            'class' => Components\Avatar::class,
            'alias' => 'wui-avatar',
        ],
        'icon' => [
            'class' => Components\Icon::class,
            'alias' => 'wui-icon',
        ],
        'icon.spinner' => [
            'class' => Components\Icons\Spinner::class,
            'alias' => 'wui-icon.spinner',
        ],
        'color-picker' => [
            'class' => Components\ColorPicker::class,
            'alias' => 'wui-color-picker',
        ],
        'input' => [
            'class' => Components\Input::class,
            'alias' => 'wui-input',
        ],
        'textarea' => [
            'class' => Components\Textarea::class,
            'alias' => 'wui-textarea',
        ],
        'label' => [
            'class' => Components\Label::class,
            'alias' => 'wui-label',
        ],
        'error' => [
            'class' => Components\Error::class,
            'alias' => 'wui-error',
        ],
        'errors' => [
            'class' => Components\Errors::class,
            'alias' => 'wui-errors',
        ],
        'inputs.maskable' => [
            'class' => Components\Inputs\MaskableInput::class,
            'alias' => 'wui-inputs.maskable',
        ],
        'inputs.phone' => [
            'class' => Components\Inputs\PhoneInput::class,
            'alias' => 'wui-inputs.phone',
        ],
        'inputs.currency' => [
            'class' => Components\Inputs\CurrencyInput::class,
            'alias' => 'wui-inputs.currency',
        ],
        'inputs.number' => [
            'class' => Components\Inputs\NumberInput::class,
            'alias' => 'wui-inputs.number',
        ],
        'inputs.password' => [
            'class' => Components\Inputs\PasswordInput::class,
            'alias' => 'wui-inputs.password',
        ],
        'badge' => [
            'class' => Components\Badge::class,
            'alias' => 'wui-badge',
        ],
        'badge.circle' => [
            'class' => Components\CircleBadge::class,
            'alias' => 'wui-badge.circle',
        ],
        'button' => [
            'class' => Components\Button::class,
            'alias' => 'wui-button',
        ],
        'button.circle' => [
            'class' => Components\CircleButton::class,
            'alias' => 'wui-button.circle',
        ],
        'dropdown' => [
            'class' => Components\Dropdown::class,
            'alias' => 'wui-dropdown',
        ],
        'dropdown.item' => [
            'class' => Components\Dropdown\DropdownItem::class,
            'alias' => 'wui-dropdown.item',
        ],
        'dropdown.header' => [
            'class' => Components\Dropdown\DropdownHeader::class,
            'alias' => 'wui-dropdown.header',
        ],
        'notifications' => [
            'class' => Components\Notifications::class,
            'alias' => 'wui-notifications',
        ],
        'datetime-picker' => [
            'class' => Components\DatetimePicker::class,
            'alias' => 'wui-datetime-picker',
        ],
        'time-picker' => [
            'class' => Components\TimePicker::class,
            'alias' => 'wui-time-picker',
        ],
        'card' => [
            'class' => Components\Card::class,
            'alias' => 'wui-card',
        ],
        'native-select' => [
            'class' => Components\NativeSelect::class,
            'alias' => 'wui-native-select',
        ],
        'select' => [
            'class' => Components\Select::class,
            'alias' => 'wui-select',
        ],
        'select.option' => [
            'class' => Components\Select\Option::class,
            'alias' => 'wui-select.option',
        ],
        'select.user-option' => [
            'class' => Components\Select\UserOption::class,
            'alias' => 'wui-select.user-option',
        ],
        'toggle' => [
            'class' => Components\Toggle::class,
            'alias' => 'wui-toggle',
        ],
        'checkbox' => [
            'class' => Components\Checkbox::class,
            'alias' => 'wui-checkbox',
        ],
        'radio' => [
            'class' => Components\Radio::class,
            'alias' => 'wui-radio',
        ],
        'modal' => [
            'class' => Components\Modal::class,
            'alias' => 'wui-modal',
        ],
        'modal.card' => [
            'class' => Components\ModalCard::class,
            'alias' => 'wui-modal.card',
        ],
        'dialog' => [
            'class' => Components\Dialog::class,
            'alias' => 'wui-dialog',
        ],
    ],
];
