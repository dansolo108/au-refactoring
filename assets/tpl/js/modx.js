function calcRealTotalCost() {
    var real_total_cost = 0;
    $(miniShop2.Cart.cart + ' .au-cart__card').each(function () {
        var price = $(this).find('input[name=price]').val(),
            count = $(this).find('input[name=count]').val();
        real_total_cost = real_total_cost + (price * count);
    });
    $('.real_total_cost').text(miniShop2.formatPrice(real_total_cost));
}

function chooseVisibleDelivery(delivery) {
    if ($('input[name="delivery"]:checked').parent().is(':visible') === false) {
        $('.au-ordering__delivery-row:visible').first().find('input[name="delivery"]').click();
    }
}

function declension(n, titles) {
    return titles[(n % 10 === 1 && n % 100 !== 11) ? 0 : n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20) ? 1 : 2];
}

function checkDeliveryFields() {
    if ($('#country').val() && $('#city').val() && $('#index').val()) {
        miniShop2.Order.getcost();
        $('.au-ordering').addClass('next-step');
    } else {
        $('.au-ordering').removeClass('next-step');
    }
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return re.test(String(email).toLowerCase());
}

function msmcGetPrice(price, floor = false) {
    price = price.toString().replace(/\s+/g, '');

    let cource = parseFloat(msMultiCurrencyConfig.course),
        currencyId = msMultiCurrencyConfig.userCurrencyId,
        priceFloat = parseFloat(price);

    if (currencyId != 1) {
        price = priceFloat / cource;
        if (floor === true) {
            return Math.floor(price);
        } else {
            return Math.ceil(price);
        }
    } else {
        return price;
    }
}

// Сохраняем в скрытое поле сроки доставки
function setOrderRates() {
    $('input[name="order_rates"]').val($('input[name="delivery"]:checked').siblings('label').find('.delivery_rate').text())
}

// Сохраняем в скрытое поле скидку на заказ
function setOrderDiscount() {
    $('input[name="order_discount"]').val($('.ms2_total_discount_custom').text().replace(" ", ""))
}

function promocodeApplied() {
    $('.au-promo-code__submit').removeClass('active');
    $('.au-promo-code__form').addClass('applied-code');
    $('.au-ordering__bonuses').addClass('disabled-bonuses');
}

function promocodeRemoved() {
    $('.au-promo-code__submit').addClass('active');
    $('.au-promo-code__form').removeClass('applied-code');
    $('.au-ordering__bonuses').removeClass('disabled-bonuses');
}

function showLoading() {
    $('.ajax-loader').addClass('enabled');
    $('.ajax-loader-block').addClass('loading');
}

function hideLoading() {
    $('.ajax-loader').removeClass('enabled');
    $('.ajax-loader-block').removeClass('loading');
}

function selectFirstSize() {
    if ($('#msProduct label.au-product__size:not(.not-size)').length) {
        $('#msProduct label.au-product__size:not(.not-size)').first().trigger('click');
    }
    else {
        $('#msProduct label.au-product__size').first().trigger('click');
    }
    $('#msProduct label.au-product__size').first().addClass('active')
}

// Переключение цветов в галерее товара
function reloadMsGallery(color, id) {
    if ($('#msGallery').length) {
        $.post("/assets/components/autentiments/getAjaxMsGallery.php", { color: color, product_id: id }, function (data) {
            if (data) {
                $('#msGallery').replaceWith(data);
                productGalleryInit();
            }
        });
    }
}

// Переключение цветов в карточке товарв
function changeCardColor(color, $input) {
    let $card = $input.closest('.au-card');
    if ($card) {
        let $img = $card.find('.js_card-img');
        if ($img.length) {
            $img.addClass('fade');
            let payload = { color: color, product_id: $card.attr('product-id'), mode: 'card' };
            $.post("/assets/components/autentiments/getAjaxMsGallery.php", payload, function (data) {
                if (data) {
                    $img.html(data);
                }
                $img.removeClass('fade');
            });
            $.post("/assets/components/autentiments/getAjaxColorPrice.php", payload, function (data) {
                if (data) {
                    $card.find('js_card-prices').html(data);
                }
            });
        } else {
            console.log('Не найден блок .js_card-img');
        }

    }
}

$(document).on('mse2_load', function (e, data) {
    $('.au-card .get-color-sizes').off('click')
    $('.au-card .get-color-sizes').on('click', (e) => {
        const target = e.target
        fastAddToCart(target)
    })
})

$(document).ready(function () {
    if (window.miniShop2) {

        $('.au-card .get-color-sizes').on('click', (e) => {
            const target = e.target
            fastAddToCart(target)
        })

        $(document).on('mspc_set mspc_freshen', function (e, response) { // событие mspc_freshen добавлено в кастомном js-файле
            if (response.mspc.discount_amount > 0) {
                $('.mspc_discount_amount span').text(miniShop2.formatPrice(msmcGetPrice(response.mspc.discount_amount)));
                promocodeApplied();
            } else {
                $('.mspc_discount_amount span').text("-");
                promocodeRemoved();
            }
        });

        $(document).on('mspc_remove', function (e, response) {
            if (response.mspc.discount_amount == 0) {
                $('.mspc_discount_amount span').text("-");
                promocodeRemoved();
            }
        });
    }


    $('.au-bonuses__cancel').click(function (e) {
        e.preventDefault();
        miniShop2.Order.add('msloyalty', '');
    });

    $('#join_loyalty_visible').on('click', function () {
        if ($(this).prop('checked')) {
            $('.msOrder #join_loyalty_order').prop('checked', true);
        } else {
            $('.msOrder #join_loyalty_order').prop('checked', false);
        }
    });


    // Сохраняем поле в сессию при изменениии select
    if ($('.msOrder').length) {
        $(document).on('change', '.msOrder select', function () {
            var $this = $(this);
            var key = $this.attr('name');
            var value = $this.find('option:selected').val();
            miniShop2.Order.add(key, value);
        });
    }

    $(document).on('click', '.au-cart__minus', function () {
        var $input = $(this).siblings('span').find('input[type="number"]');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });

    $('.header__link-basket').click(function () {
        showAjaxCart();
    });

    $('#order_submit').click(function () {
        $('button#submitbtn').click();
    });

    // Предотвращаем показ предыдущей стоимости доставки СДЭК при неверно указанных данных
    $("#city").bind("paste keyup", function () {
        if ($('input[name="cdek_id"]').val() !== '') {
            miniShop2.Order.add('cdek_id', '');
        }
    });

    if (typeof msFavorites != 'undefined') {
        msFavorites.addMethodAction('success', 'name_action', function (r) {
            var self = this;
            if (self.data && self.data.method == 'add') {
                // dataLayer.push({'event': 'favorite'});
                // Facebook Conversions API
                // $.ajax({
                //     method: "POST",
                //     url: document.location.href,
                //     data: { fb_conversions: "favorites" }
                // });
            }
        });
    }
});

// переключение цвета на странице товара
$('#msProduct input.au-product__color-input').on('change', function () {
    let product_id = $('#msProduct .ms2_form input[name=product_id]').val();
    let $this = this;
    $.post("/assets/components/autentiments/getAjaxColorSizes.php", {
        product_id: product_id,
        color: $(this).val()
    }, function (data) {
        if (data) {
            $('#ajax_sizes').html(data);
            selectFirstSize();
        }
    });
    $('.au-product__add-entrance').removeClass('active');
    $('.au-product__add-entrance').removeClass('end').prop('disabled', false);
    reloadMsGallery($($this).val(), product_id);
    let params = { 'color': $($this).val() };
    window.history.replaceState('', '', updateURLParameter(window.location.href, params));
});
function updateURLParameter(url, params) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (params[tempArray[i].split('=')[0]] === undefined) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }
    var rows_txt = "";
    for (let param in params) {
        rows_txt += temp + "" + param + "=" + params[param];
        temp = "&";
    }

    return baseURL + "?" + newAdditionalURL + rows_txt;
}
// цена оффера
$(document).on('click', '#msProduct .au-product__size', function () {

    $('.au-product__size.active').removeClass('active')
    $(this).addClass('active')
    let active = $(`input.au-product__size-input[id="${$(this).attr('for')}"]`),
        id = active.val(),
        size = active.data('value'),
        params = { 'size': size },
        thisPrice = $(this).closest('.au-product').find('.js_card_price span'),
        thisOldPrice = $(this).closest('.au-product').find('.js_card_old_price')

    window.history.replaceState('', '', updateURLParameter(window.location.href, params));
    $.post('/assets/components/autentiments/getAjaxModificationPrice.php', {
        'id': id,
    }, function (data) {
        data = JSON.parse(data);
        if (data) {
            thisPrice.html(miniShop2.formatPrice(msmcGetPrice(data.price)));
            if (data.old_price > 0) {
                thisOldPrice.show();
                thisOldPrice.find('span').html(miniShop2.formatPrice(msmcGetPrice(data.old_price)));
            } else {
                thisOldPrice.hide();
            }
        }
    });

});

// Переключение цвета в карточке товара
$(document).on('change', 'input.au-card__color-input', function () {
    let $this = $(this),
        color = $($this).val();
    changeCardColor(color, $this);
    $($this).closest('.au-card').find('a').each(function () {
        let href = $(this).attr('href').split('?')[0];
        $(this).attr('href', href + '?color=' + color)
    });
});

$(document).on('af_complete', function (event, response) {
    if (response.success) {
        var form = response.form;

        switch (form.attr('id')) {
            case 'contacts_form':
                $('.au-contacts__form-box').addClass('hide');
                $('.au-contacts__message-info').addClass('show');
                // dataLayer.push({'event': 'message'});
                break;
            case 'newsletter_subscribe_form':
            case 'greeting_subscribe_form':
                $('.au-subscribe').addClass('subscribe_submit-end');
                // dataLayer.push({'event': 'email'});
                break;
            case 'welcome_subscribe_form':
                $('.au-welcome__col').addClass('welcome_submit-end');
                break;
            case 'size_subscribe_form':
                $('.au-close').trigger('click');
                $('.au-product__add-entrance').addClass('end').prop('disabled', true);
                break;
            case 'join_loyalty':
                $('.au-ordering__loyalty_start').removeClass('active');
                $('.au-ordering__loyalty_end').addClass('active');
                break;
            case 'join_loyalty_profile':
                location.reload();
                break;
        }
    }
});

function fastAddToCart(target) {
    const pid = target.dataset.pid,
        cardClass = `.au-card[product-id="${pid}"]`,
        $card = target.closest(cardClass),
        cid = $card.getAttribute('id'),
        $link = $card.querySelector('.au-card__link'),
        tmpArr = $link.href.split('='),
        color = decodeURIComponent(tmpArr[1])

    $.post('/assets/components/autentiments/getAjaxColorSizes.php', {
        product_id: pid,
        color: color
    }, function (data) {
        if (data) {

            target.disabled = true
            target.style.display = 'none'

            const $sizes = document.createElement('div')
            $sizes.classList.add('au-product__sizes')
            $sizes.insertAdjacentHTML('beforeend', data)
            target.parentNode.append($sizes)

            $(`#${cid} .au-product__size`).on('click', (e) => {
                if (e.target.classList.contains('not-size')) return false
                const $submitter = target.closest(cardClass).querySelector('button[type="submit"]'),
                    $pid = $card.querySelector('input[name="id"]'),
                    mid = e.target.previousElementSibling.value

                $pid.value = mid

                $submitter.click()
                $sizes.remove()

                const $span = document.createElement('span')

                miniShop2.Callbacks.add('Cart.add.response.success', `add_to_cart_success`, function (response) {
                    $span.textContent = 'Товар добавлен'
                })

                miniShop2.Callbacks.add('Cart.add.response.error', 'add_to_cart_false', function (response) {
                    $span.textContent = 'Товар не добавлен'
                })

                target.parentNode.append($span)

                setTimeout(() => {
                    $span.remove()
                    target.disabled = false
                    target.style.display = 'block'
                }, 3000)

                $(`#${cid} .au-product__size`).off('click')
            })
        }
    })
} 
