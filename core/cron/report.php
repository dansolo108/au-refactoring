<?php

require_once '../../config.core.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('web');
$modx->getService('error','error.modError', '', '');
$modx->addPackage('formit', $modx->getOption('core_path').'components/formit/model/');

$form = 'Антикризисное оформление заказа';
$date00 = strtotime('00:00:00');
$date24 = strtotime('23:59:59');

$where = array(
    array(
       'date:>' => $date00,
       'AND:date:<' => $date24,
    ),
    array(
       'AND:form:>=' => $form,
    )
);

$data = array(
	array(
		'Имя',
		'Телефон',
		'Заказ',
		'Цена',
		'Промокод',
	),
);
    
$result = $modx->getCollection('FormItForm', $where);

if (!empty($result)) {
    foreach($result as $row){
        $data[] = (json_decode($row->get('values')));
    };
    
    $data[] = array(
        'Всего',
        count($result),
    );
} else {
    $data[] = array(
        'Всего',
        '0',
    );
}

function create_csv_file($create_data, $file = null, $col_delimiter = ';', $row_delimiter = "\r\n"){

	if(!is_array($create_data)){
		return false;
	}

	if($file && !is_dir(dirname($file))){
		return false;
	}

	// строка, которая будет записана в csv файл
	$CSV_str = '';

	// перебираем все данные
	foreach($create_data as $row){
		$cols = array();

		foreach($row as $col_val){
			// строки должны быть в кавычках ""
			// кавычки " внутри строк нужно предварить такой же кавычкой "
			if($col_val && preg_match('/[",;\r\n]/', $col_val)){
				// поправим перенос строки
				if($row_delimiter === "\r\n"){
					$col_val = str_replace([ "\r\n", "\r" ], [ '\n', '' ], $col_val);
				}
				elseif($row_delimiter === "\n"){
					$col_val = str_replace([ "\n", "\r\r" ], '\r', $col_val);
				}

				$col_val = str_replace('"', '""', $col_val); // предваряем "
				$col_val = '"'. $col_val .'"'; // обрамляем в "
			}

			$cols[] = $col_val; // добавляем колонку в данные
		}

		$CSV_str .= implode($col_delimiter, $cols) . $row_delimiter; // добавляем строку в данные
	}

	$CSV_str = rtrim($CSV_str, $row_delimiter);

	if($file){
		// создаем csv файл и записываем в него строку
		$done = file_put_contents($file, $CSV_str);
		return $done ? $CSV_str : false;
	}

	return $CSV_str;

}

$file = MODX_CORE_PATH .'/cron/order-report-'.date("Y-m-d", $date00).'.csv';
create_csv_file($data, $file);

if($modx->getService('mail', 'mail.modPHPMailer')) {
    $modx->mail->set(modMail::MAIL_FROM, $modx->getOption('emailsender'));
    $modx->mail->set(modMail::MAIL_FROM_NAME, $modx->getOption('site_name'));
    $modx->mail->address('to', 'alex2611@mail.ru');
    $modx->mail->address('to', 'shumnox@gmail.com');
    $modx->mail->set(modMail::MAIL_SUBJECT, 'Отчет о заказах');
    $modx->mail->set(modMail::MAIL_BODY,'Отчет о заказах ' . date("d.m.Y", $date00));
    $modx->mail->attach($file);
    
    if (!$modx->mail->send()) {
        $modx->log(modX::LOG_LEVEL_ERROR, date("d.m.Y", $date00).': произошла ошибка при попытке отправить электронное письмо: '.$modx->mail->mailer->ErrorInfo);
    }
    
    $modx->mail->reset();
}