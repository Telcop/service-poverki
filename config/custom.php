<?php

return [

    // Стартовый номер для заявки
    'verification_number_request_start' => env('VERIFICATION_NUMBER_REQUEST_START', 0),
    // Идентификатор дополняющий номер заявки
    'verification_number_request_mask' => env('VERIFICATION_NUMBER_REQUEST_MASK', '-ЗП'),
    // Щаблон названия письма о поверке
    'name_template_poverki' => env('NAME_TEMPLATE_POVERKI', 'Письмо № #1 от #2 о поверке, прибор #3 (серийные номера #4-#5)'),

];
