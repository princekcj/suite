<?php 
return [
  'maxRetriesToolTip' => 'Максимальное количество неудачных попыток, разрешенных до блокировки',
  'extendLockoutToolTip' => 'Продлить время блокировки после первой блокировки',
  'emailNotificationToolTip' => 'После (количества локаутов) локаутов. 0, чтобы отключить уведомление по электронной почте',
  'ipToolTip' => 'Введите один IP в строке',
  'loginExpiry' => 'Срок действия вашей учетной записи истек. Пожалуйста, свяжитесь с администратором.',
  'sessionDriverRequired' => 'Пожалуйста, установите драйвер сеанса в базу данных. В противном случае эта функция не будет работать. Вы можете перейти на базу данных с :setting.',
  'maxRetries' => 'Вы достигли максимального количества повторов. Пожалуйста, повторите попытку после :time.',
  'ipRequired' => 'Пожалуйста, введите IP-адрес, если вы хотите включить проверку IP.',
  'blacklistEmail' => 'Ваш адрес электронной почты занесен в черный список. Пожалуйста, свяжитесь с администратором.',
  'blacklistIp' => 'Ваш IP занесен в черный список. Пожалуйста, свяжитесь с администратором.',
  'infoBox' => [
    'lockoutForMinutes' => 'Пользователь будет заблокирован после :maxRetries неудачных попыток в течение :lockoutTime минут.',
    'extendedLockout' => 'Время блокировки будет увеличено до х1 часа после первой блокировки.',
    'maxLockoutsAvailable' => 'Максимально допустимое количество блокировок составляет :maxLockouts.',
    'resetRetries' => 'Повторные попытки будут сброшены через 1 час.',
    'alertAfterLockouts' => 'Уведомление по электронной почте будет отправлено после блокировки :alertAfterLockouts на :email.',
    'sendEmailDifferentIp' => 'Отправлять уведомление по электронной почте, если вы входите в систему с другого IP-адреса :ip.',
    'notSendEmailDifferentIp' => 'Не отправлять уведомление по электронной почте, если вы входите в систему с другого IP-адреса.',
  ],
];