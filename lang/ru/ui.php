<?php

return [
    'throttle' => 'Слишком много попыток. Пожалуйста, попробуйте еще раз через :seconds секунд(ы)',
    'menu' => [
        'prizes' => 'Призы',
        'weekly_prizes' => 'Еженедельные призы',
        'instant_prizes' => 'Моментальные призы',
        'vouchers' => 'Ваучеры',
        'prize_drawing_calendars' => 'Календарь розыгрыша',
        'receipt_statuses' => 'Статусы чеков',
        'receipts' => 'Чеки',
        'settings' => 'Настройки'
    ],
    'fields' => [
        'name_ru' => 'Наименование на русском',
        'name_kk' => 'Наименование на казахском',
        'name_uz' => 'Наименование на узбекском',
        'bal' => 'Балл',
        'number' => 'Количество',
        'code' => 'Код',
        'count' => 'Количество',
        'codes' => 'Коды',
        'winning_date' => 'Дата и время выигрыша',
        'prize' => 'Приз',
        'prizes' => 'Призы',
        'user' => 'Пользователь',
        'spent_balls' => 'Потраченные баллы',
        'is_approved' => 'Подтверждение выигрыша',
        'drawing_at' => 'Дата и время проведения',
        'started_at' => 'Начало',
        'is_finished' => 'Проведён?',
        'start_date' => 'Дата и время начало',
        'repeat_weeks' => 'Сколько недель?',
        'color' => 'Цвет',
        'image' => 'Фото',
        'created_at' => 'Дата создания',
        'status' => 'Статус',
        'game_max_coins' => 'Количество коинов за прохождение одной игры',
        'receipt_life' => 'Количество жизней при загрузке чека',
        'referal_life' => 'Количество жизней для рефералки',
        'promotion_date' => 'Дата начало и окончания акции',
        'winner' => 'Победитель',
        'receipt_id' => 'Номер чека'
    ],
    'hints' => [
        'bal' => 'Стоимость в баллах',
        'number' => 'Общее количество приза',
    ],
    'buttons' => [
        'showbox_add' => 'Добавить Шоубокс',
        'balance_add' => 'Добавить Баланс',
        'add' => 'Добавить',
        'winned' => 'Выигранные',
        'free' => 'Свободные',
        'import' => 'Импорт',
        'export' => 'Экспорт',
        'approve' => 'Подтвердить',
        'reject' => 'Отклонить'
    ],
    'messages' => [
        'added' => 'Добавлено',
        'added_with_count' => 'Добавлено :count записей',
        'signUp_error' => 'Не удалось зарегистрироваться, попробуйте позже!',
        'sms_message' => 'Ваш код :code',
        'verify_error' => 'Неправильный код!',
        'auth_failed' => 'Неверный номер телефона или код!',
        'sms_limit' => 'Слишком много попыток. Пожалуйста, попробуйте позже!',
        'email_limit' => 'Слишком много попыток. Пожалуйста, попробуйте позже!',
        'finish_registration' => 'Вы не завершили регистрацию!',
        'saved' => 'Сохранено!',
        'life_error' => 'У вас нет жизней!',
        'error_finished_game' => 'Ошибка при завершение игры',
        'qr_not_found' => 'Qr код не найден!',
        'orbit_not_found' => 'Орбит не найдено в чеке!',
        'not_enough_coins' => 'Не хватает коинов!',
        'approve_body' => 'Вы действительно хотите подтвердить наличие позиции "Орбит"?',
        'reject_body' => 'Вы действительно отклонить наличие позиции "Орбит"?',
        'error' => 'Ошибка'
    ],
    'mail' => [
        'subject' => 'Ваш пароль для Orbit promo',
        'title' => 'Здравствуйте, :fullName !',
        'code' => 'Ваш пароль для входа в систему: :code'
    ]
];
