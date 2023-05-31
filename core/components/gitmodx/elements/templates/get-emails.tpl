<!DOCTYPE html>
<html lang="{$_modx->config['cultureKey']}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>{$_modx->resource.longtitle ?: $_modx->resource.pagetitle}</title>
    <meta name="description" content="{$_modx->resource.og_description ?: $_modx->resource.description}" />
    <base href="{$_modx->config.site_url}">
    <link rel="icon" href="/assets/tpl/favicon/favicon.ico">
    <link rel="icon" href="/assets/tpl/favicon/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/assets/tpl/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/assets/tpl/favicon/manifest.json">
    <script src="/assets/tpl/js/vendor/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/assets/tpl/css/new.css">
</head>

<body>
    <header>
        <img src="/assets/tpl/img/get-emails-header.jpeg"
            alt="Дарим промокод на скидку 15% на все за подписку на новости Autentiments" loading="lazy">
    </header>
    <main>
        <div class="wrapper">
            <h1>{$_modx->resource.longtitle ?: $_modx->resource.pagetitle}</h1>
            <p>{$_modx->resource.description}</p>
            {'!AjaxForm' | snippet : [
            'hooks' => 'FormItSaveForm,addEmailToMaxma',
            'formFields' => 'email,phone',
            'fieldNames' => 'email==Email,phone==Телефон',
            'formName' => 'Сборщик почты',
            'validate' => 'email:email:required,phone:required',
            'form' => 'get-emails.form',
            'validationErrorMessage' => 'Ошибка ввода данных',
            'successMessage' => 'Данные успешно отправлены'
            ]}
        </div>
    </main>
</body>

</html>