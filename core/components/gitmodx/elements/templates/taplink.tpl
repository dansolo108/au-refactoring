<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{$_modx->resource.pagetitle}</title>
    <meta name="author" content="Autentiments">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Autentiments">
    <meta property="og:title"
          content="{$_modx->resource.og_title ? $_modx->resource.og_title: $_modx->resource.pagetitle}">
    <meta property="og:description"
          content="{$_modx->resource.og_description ? $_modx->resource.og_description : $_modx->resource.description}">
    <meta property="og:url" content="{$_modx->resource.id | url : ['scheme' => 'full']}">
    {if $_modx->resource.img}
        {* set $tv_img = '/assets/uploads/' ~ $_modx->resource.img *}
        {set $tv_img = $_modx->resource.img}
    {/if}
    {if $_modx->resource.og_image}
        {set $tv_og_image = '/' ~ $_modx->resource.og_image}
    {/if}
    {set $og_image = ($tv_og_image ?: $_modx->resource.image) ?: $tv_img}
    {if $og_image}
        <meta property="og:image" content="{$_modx->config.site_url | preg_replace : '#/$#' : ''}{$og_image}">
    {/if}
        <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>{$_modx->resource.longtitle ?: $_modx->resource.pagetitle}</title>
    <meta name="description" content="{$_modx->resource.og_description ?: $_modx->resource.description}"/>
    <base href="{$_modx->config.site_url}">
     <link rel="icon" href="/assets/tpl/favicon/favicon.ico">
    <link rel="icon" href="/assets/tpl/favicon/icon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/assets/tpl/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/assets/tpl/favicon/manifest.json">
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="/assets/taplink/css/style.css">
</head>
<body>
    <header class="container header">
        <a href="/" class="header__logo">
            autentiments
        </a>
        <div class="header__text only-mobile">
            <p>
                Новое прочтение современной женственности.
            </p>
            <p>Одежда, отражающая образ современной женщины, живущей в ритме большого города: чувственной, сильной, изящной и уверенной в себе.</p>
        </div>
        <nav class="nav">
            <a href="{884 | url}" class="nav-item">Новинки</a>
            <a href="{7 | url}" class="nav-item only-pc">Каталог</a>
            <a href="{545 | url}" class="nav-item only-mobile">bestsellers</a>
            <a href="{23 | url}" class="nav-item">Sale</a>
            <a href="{3| url}" class="nav-item only-pc">Контакты</a>
            <a href="{812| url }" class="nav-item only-pc">Шоу-румы</a>
        </nav>
    </header>

    <main class="container main">
        <div class="main-banner">
            <div class="main-banner__images">
                <img src="/assets/taplink/images/banner.png" class="only-pc" alt="">
                <img src="/assets/taplink/images/banner-mobile.png" class="only-mobile" alt="">
            </div>
            <div class="main-banner__buttons">
                <a href="{7 | url}" class="button white main-banner__button only-pc">Каталог</a>
                <a href="{7 | url}" class="button main-banner__button only-mobile">Каталог</a>
                <a href="https://wa.me/79215702113?text=%20%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%20%D0%BE%D1%82%D0%BF%D1%80%D0%B0%D0%B2%D1%8C%D1%82%D0%B5%20%D1%8D%D1%82%D0%BE%20%D1%81%D0%BE%D0%BE%D0%B1%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%B8%20%D0%B4%D0%BE%D0%B6%D0%B4%D0%B8%D1%82%D0%B5%D1%81%D1%8C%20%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D0%B0.%20%D0%92%D0%B0%D1%88%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80:%20roi-442998" target="_blank" class="button outline white main-banner__button only-pc">Связаться в whatsapp</a>
                <a href="https://wa.me/79215702113?text=%20%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%20%D0%BE%D1%82%D0%BF%D1%80%D0%B0%D0%B2%D1%8C%D1%82%D0%B5%20%D1%8D%D1%82%D0%BE%20%D1%81%D0%BE%D0%BE%D0%B1%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%B8%20%D0%B4%D0%BE%D0%B6%D0%B4%D0%B8%D1%82%D0%B5%D1%81%D1%8C%20%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D0%B0.%20%D0%92%D0%B0%D1%88%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80:%20roi-442998" target="_blank" class="button outline main-banner__button only-mobile">Связаться в whatsapp</a>
            </div>
        </div>

        <div class="main-slider">
            <div class="main-slider__title">
                Новинки месяца
            </div>
            <div class="swiper__outer">
                <!-- Slider main container -->
                <div class="swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        {"!getProductsWithOffers" | snippet : [
                            "tpl"=>"taplink.product",
                            'details'=> ['color'],
                            'sortby'=>['Modification.id','Modification.sort_index'=>"DESC"],
                            'groupby'=>["Modification.product_id",'color'],
                            'parents'=>884,
                            'limit'=>15,
                        ]}
                    </div>

                </div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>
            <form action="#" method="POST" class="footer__subscribe only-mobile">
                <p>Подпишитесь на наши новости, чтобы первыми узнавать о новых коллекциях, уникальных предложениях и секретных акциях</p>
                <label class="field">
                    <span class="field__title">
                        Email
                    </span>
                    <input type="email" name="email" class="input field__input">
                </label>
                <button class="button outline">
                    Подписаться
                </button>
            </form>
        </div>
    </main>
    <footer class="container footer">
        <div class="footer__title">Контакты</div>
        <div class="footer__inner">
            <div class="footer__contacts">
                <div class="footer-contact">
                    <div class="footer-contact__title">Autentiments Москва</div>
                    <div class="footer-contact__address">Пятница ул., 7, стр. 5</div>
                    <a href="tel:+79263022528" class="footer-contact__phone">+ 7 926 302 25-28</a>
                </div>
                <div class="footer-contact">
                    <div class="footer-contact__title">Autentiments Санкт-Петербург</div>
                    <div class="footer-contact__address">Басков пер., 26</div>
                    <a href="tel:+79215702113" class="footer-contact__phone">+ 7 921 570 21-13</a>
                </div>
            </div>
            <div class="footer__work-time">
                График&nbsp;работы&nbsp;шоу-румов: ежедневно с&nbsp;11:00&nbsp;до&nbsp;22:00
            </div>
            <div class="footer__help">
                <p>Если вам нужна помощь в оформлении заказа или консультация по изделиям, пожалуйста, свяжитесь с нами в WhatsApp</p>
                <a href="https://wa.me/79215702113?text=%20%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%20%D0%BE%D1%82%D0%BF%D1%80%D0%B0%D0%B2%D1%8C%D1%82%D0%B5%20%D1%8D%D1%82%D0%BE%20%D1%81%D0%BE%D0%BE%D0%B1%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%B8%20%D0%B4%D0%BE%D0%B6%D0%B4%D0%B8%D1%82%D0%B5%D1%81%D1%8C%20%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D0%B0.%20%D0%92%D0%B0%D1%88%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80:%20roi-442998" target="_blank" class="button outline">
                    Связаться
                </a>
            </div>
            <form action="#" method="POST" class="footer__subscribe only-pc">
                <p>Подпишитесь на наши новости, чтобы первыми узнавать о новых коллекциях, уникальных предложениях и секретных акциях</p>
                <label class="field">
                    <span class="field__title">
                        Email
                    </span>
                    <input type="email" name="email" class="input field__input">
                </label>
                <button class="button outline">
                    Подписаться
                </button>
            </form>
            <div class="footer__messengers">
                <a href="{$_modx->config.vk}" target="_blank" class="footer-messenger">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 0C7.16333 0 0 6.96308 0 15.5527C0 24.1424 7.16333 31.1054 16 31.1054C24.8367 31.1054 32 24.1424 32 15.5527C32 6.96308 24.8367 0 16 0ZM22.1533 17.547C22.1533 17.547 23.5683 18.9047 23.9167 19.5349C23.9267 19.5478 23.9317 19.5608 23.935 19.5673C24.0767 19.7989 24.11 19.9788 24.04 20.1132C23.9233 20.3368 23.5233 20.447 23.3867 20.4567H20.8867C20.7133 20.4567 20.35 20.413 19.91 20.1181C19.5717 19.888 19.2383 19.5106 18.9133 19.1428C18.4283 18.5952 18.0083 18.1222 17.585 18.1222C17.5312 18.1221 17.4778 18.1303 17.4267 18.1465C17.1067 18.2469 16.6967 18.6908 16.6967 19.8735C16.6967 20.2428 16.3967 20.4551 16.185 20.4551H15.04C14.65 20.4551 12.6183 20.3222 10.8183 18.477C8.615 16.217 6.63167 11.684 6.615 11.6419C6.49 11.3486 6.74833 11.1915 7.03 11.1915H9.555C9.89167 11.1915 10.0017 11.3907 10.0783 11.5673C10.1683 11.7731 10.4983 12.5912 11.04 13.5114C11.9183 15.0116 12.4567 15.6208 12.8883 15.6208C12.9693 15.6198 13.0488 15.5998 13.12 15.5624C13.6833 15.2579 13.5783 13.3057 13.5533 12.9007C13.5533 12.8245 13.5517 12.0274 13.2633 11.6451C13.0567 11.3681 12.705 11.2628 12.4917 11.2239C12.578 11.1081 12.6918 11.0142 12.8233 10.9501C13.21 10.7622 13.9067 10.7346 14.5983 10.7346H14.9833C15.7333 10.7443 15.9267 10.7913 16.1983 10.8577C16.7483 10.9857 16.76 11.3308 16.7117 12.5118C16.6967 12.8472 16.6817 13.2263 16.6817 13.6734C16.6817 13.7706 16.6767 13.8743 16.6767 13.9845C16.66 14.5855 16.64 15.2676 17.0767 15.5479C17.1336 15.5826 17.1994 15.6011 17.2667 15.6013C17.4183 15.6013 17.875 15.6013 19.1117 13.539C19.4931 12.8752 19.8245 12.1854 20.1033 11.475C20.1283 11.4329 20.2017 11.3033 20.2883 11.253C20.3523 11.2213 20.4232 11.2052 20.495 11.2061H23.4633C23.7867 11.2061 24.0083 11.253 24.05 11.3745C24.1233 11.5673 24.0367 12.1554 22.6817 13.9391L22.0767 14.7151C20.8483 16.2801 20.8483 16.3595 22.1533 17.547Z" fill="#157BFB"/>
                    </svg>
                </a>
                <a href="https://t.me/autentiments_official" target="_blank" class="footer-messenger">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 0C7.168 0 0 6.96762 0 15.5527C0 24.1378 7.168 31.1054 16 31.1054C24.832 31.1054 32 24.1378 32 15.5527C32 6.96762 24.832 0 16 0ZM23.424 10.5759C23.184 13.0332 22.144 19.0054 21.616 21.7583C21.392 22.9247 20.944 23.3135 20.528 23.3602C19.6 23.438 18.896 22.7692 18 22.1937C16.592 21.2917 15.792 20.7318 14.432 19.8608C12.848 18.8499 13.872 18.29 14.784 17.3879C15.024 17.1547 19.12 13.5309 19.2 13.2043C19.2111 13.1548 19.2096 13.1034 19.1957 13.0547C19.1818 13.0059 19.1558 12.9611 19.12 12.9243C19.024 12.8466 18.896 12.8777 18.784 12.8932C18.64 12.9243 16.4 14.3707 12.032 17.2324C11.392 17.6523 10.816 17.8701 10.304 17.8545C9.728 17.839 8.64 17.5435 7.824 17.2791C6.816 16.968 6.032 16.7969 6.096 16.2526C6.128 15.9726 6.528 15.6927 7.28 15.3972C11.952 13.422 15.056 12.1156 16.608 11.4935C21.056 9.68935 21.968 9.37829 22.576 9.37829C22.704 9.37829 23.008 9.4094 23.2 9.56493C23.36 9.68935 23.408 9.86043 23.424 9.98485C23.408 10.0782 23.44 10.3581 23.424 10.5759Z" fill="#3DA2D2"/>
                    </svg>
                </a>
                <a href="https://wa.me/79215702113?text=%20%D0%9E%D0%B1%D1%8F%D0%B7%D0%B0%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%20%D0%BE%D1%82%D0%BF%D1%80%D0%B0%D0%B2%D1%8C%D1%82%D0%B5%20%D1%8D%D1%82%D0%BE%20%D1%81%D0%BE%D0%BE%D0%B1%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%B8%20%D0%B4%D0%BE%D0%B6%D0%B4%D0%B8%D1%82%D0%B5%D1%81%D1%8C%20%D0%BE%D1%82%D0%B2%D0%B5%D1%82%D0%B0.%20%D0%92%D0%B0%D1%88%20%D0%BD%D0%BE%D0%BC%D0%B5%D1%80:%20roi-439467" target="_blank" class="footer-messenger">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.00642141 31.1054L2.16962 23.3788C0.744242 21.0037 -0.00462501 18.3024 2.14916e-05 15.5527C2.14916e-05 6.96295 7.16322 0 16 0C24.8368 0 32 6.96295 32 15.5527C32 24.1425 24.8368 31.1054 16 31.1054C13.1725 31.1099 10.3947 30.3825 7.95202 28.998L0.00642141 31.1054ZM10.2256 8.25538C10.019 8.26785 9.81709 8.32075 9.63202 8.41091C9.45845 8.50645 9.30001 8.62588 9.16162 8.76551C8.96961 8.94126 8.86082 9.09367 8.74402 9.24142C8.15267 9.9895 7.83459 10.9078 7.84002 11.8512C7.84322 12.6133 8.04802 13.3551 8.36802 14.0488C9.02242 15.4516 10.0992 16.9369 11.5216 18.3133C11.864 18.6446 12.1984 18.9774 12.5584 19.2869C14.3238 20.7978 16.4276 21.8874 18.7024 22.469L19.6128 22.6043C19.9088 22.6199 20.2048 22.5981 20.5024 22.5841C20.9684 22.5607 21.4234 22.4381 21.8352 22.2248C22.0447 22.1199 22.2492 22.0057 22.448 21.8827C22.448 21.8827 22.5168 21.8391 22.648 21.7427C22.864 21.5872 22.9968 21.4767 23.176 21.2948C23.3088 21.161 23.424 21.0039 23.512 20.8251C23.6368 20.5716 23.7616 20.0879 23.8128 19.6851C23.8512 19.3771 23.84 19.2092 23.8352 19.105C23.8288 18.9385 23.6864 18.7659 23.5312 18.6928L22.6 18.2869C22.6 18.2869 21.208 17.6974 20.3584 17.3211C20.2688 17.2831 20.1728 17.2615 20.0752 17.2573C19.9657 17.2464 19.8551 17.2583 19.7508 17.2924C19.6465 17.3265 19.5509 17.3818 19.4704 17.4548V17.4517C19.4624 17.4517 19.3552 17.5404 18.1984 18.9028C18.132 18.9895 18.0406 19.055 17.9357 19.091C17.8308 19.1271 17.7173 19.1319 17.6096 19.105C17.5054 19.0779 17.4032 19.0436 17.304 19.0023C17.1056 18.9214 17.0368 18.8903 16.9008 18.8328L16.8928 18.8297C15.9775 18.4412 15.13 17.9165 14.3808 17.2744C14.1792 17.1033 13.992 16.9167 13.8 16.7363C13.1705 16.1503 12.622 15.4874 12.168 14.7642L12.0736 14.6164C12.0058 14.5172 11.951 14.4101 11.9104 14.2976C11.8496 14.069 12.008 13.8855 12.008 13.8855C12.008 13.8855 12.3968 13.4718 12.5776 13.2478C12.7282 13.0617 12.8686 12.868 12.9984 12.6677C13.1872 12.3722 13.2464 12.0689 13.1472 11.8341C12.6992 10.7703 12.2352 9.71111 11.7584 8.65975C11.664 8.45134 11.384 8.30204 11.1296 8.27249C11.0432 8.26316 10.9568 8.25383 10.8704 8.2476C10.6555 8.23723 10.4402 8.24086 10.2256 8.25538Z" fill="#36CE6B"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
    <script src="/assets/taplink/js/script.js" async></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="/assets/taplink/js/slider.js"></script>
</body>
</html>