<?php

return [

    // Стартовый номер для заявки
    'verification_number_request_start' => env('VERIFICATION_NUMBER_REQUEST_START', 0),
    // Идентификатор дополняющий номер заявки
    'verification_number_request_mask' => env('VERIFICATION_NUMBER_REQUEST_MASK', '-ЗП'),
    // Щаблон названия письма о поверке
    'name_template_poverki' => env('NAME_TEMPLATE_POVERKI', 'Письмо № #1 от #2 о поверке, прибор #3 (серийные номера #4-#5)'),
    // Пути в файловое хранилище
    'path_ftp' => env('PATH_FTP'),
    'ftp_root_poverki' => env('FTP_ROOT_POVERKI'),
    'ftp_root_requests' => env('FTP_ROOT_REQUESTS'),
    // Количество записей на одной странице при пагинации
    'paginate' => env('PAGINATE', 50),
];
