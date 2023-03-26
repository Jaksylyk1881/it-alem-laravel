<?php

return [
    'http_errors' => [
        '200' => 'Успешно',
        '201' => 'Успешно создан',
        '202' => 'Принято',
        '404' => 'Не найдено',
        '403' => 'Доступ запрещен',
        '404-2' => ':attr не найден!',
        '401' => 'Не авторизован',
        '400' => 'Неверный запрос',
        '500' => 'Ошибка сервера. Пожалуйста, попробуйте повторить позже!',
    ],

    'try_again' => 'Ошибка, попробуйте еще раз',

    'order' => [],
    'executor' => [],

    'push' => [
        'executorResponded' => [
            'title' => 'На ваш заказ откликнулись!',
            'body' => 'Исполнитель откликнулся на ваш заказ: :order',
        ],
        'executorChosen' => [
            'title' => 'Пользователь выбрал вас!',
            'body' => 'Пользователь выбрал вас в заказе ":order"',
        ],
        'ChatAdminResponded' => [
            'title' => 'Вам ответили в чате поддержки!',
            'body' => ':chatMessage',
        ],
    ],

    'Code sending 30 seconds limit has not expired yet' => 'Срок отправки кода в 30 секунд еще не истек',
    'sendCodeSuccessfully' => 'Код подтверждения был успешно отправлен на ваш электронную почту',
    'email_ignore' => 'Если Вы ранее не предпринимали никаких действий в приложений :app_name, игнорируйте данное письмо.',
    'email_dont_answer' => 'Данное письмо сформировано автоматически, отвечать на него не надо.',
    'object' => '[0] объектов|[1, 4] объект|[5,9] объектов',
];
