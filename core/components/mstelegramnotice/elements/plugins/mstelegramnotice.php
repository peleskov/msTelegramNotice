<?php

/** @var modX $modx */
switch ($modx->event->name) {
    case 'msOnCreateOrder':
        //Получить chat_id https://api.telegram.org/botXXXXXXXXX:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/getUpdates
        $chat_ids = $modx->getOption('site_start') ? explode(',', $modx->getOption('mstelegramnotice_telegram_recivers')) : [];
        $token = $modx->getOption('mstelegramnotice_telegram_token');
        
        $payment = $modx->getObject('msPayment', ['id' => $msOrder->get('payment')]);
        $delivery = $modx->getObject('msDelivery', ['id' => $msOrder->get('delivery')]);

        $message = [
            0 => 'Сайт: ' . $modx->getOption('site_name'),
            1 => 'Клиент: ' . $msOrder->get('receiver'),
            2 => 'Email: ' . $msOrder->get('email'),
            3 => 'Номер заказа: ' . $msOrder->get('num'),
            4 => 'Сумма: ' . $msOrder->get('cost'),
            5 => 'Оплата: ' . $payment->get('name'),
            6 => 'Доставка: ' . $delivery->get('name')
        ];
        $url_msg = "https://api.telegram.org/bot" . $token . "/sendMessage?text=" . urlencode(implode(PHP_EOL, $message));
        if (count($chat_ids) > 0 && $token != '') {
            foreach ($chat_ids as $chat_id) {
                $msg = $url_msg . "&chat_id=" . $chat_id;
                file_get_contents($msg);
            }
        }
        break;
}
