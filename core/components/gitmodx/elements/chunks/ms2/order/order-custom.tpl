<main class="auten-main">
    <a href="#" class="auten-back">Назад</a>
    <a href="#" class="auten-help">Помощь</a>
    <div class="auten-ordering">
        {'!msCart' | snippet : [
        'tpl' => 'cart',
        'includeThumbs' => 'cart',
        ]}
        <form class="maxma_form auten-loyalty" action="promocode">
            <div class=" auten-promo ">
                <label class="auten-field maxma_field auten-promo__field {if $form['promocode']}inactive{/if}">
                    <div class="auten-field__title">
                        Промокод
                    </div>
                    <input type="text" class="auten-field__input" name="promocode" value="{$form['promocode']}">
                </label>
                <button type="submit" data-action="promocode" class="auten-button auten-promo__button">
                    Применить
                </button>
                <button type="submit" data-action="promocode" data-value="" class="auten-button auten-promo__button">
                    отменить
                </button>
            </div>
        </form>
        {$_modx->runSnippet('!AjaxForm', [
            'form' => 'ajaxform-cartform',
            'formName' => 'Антикризисное оформление заказа',
            'formFields' => 'name,phone,order,price,promo',
            'fieldNames' => 'name==Имя,phone==Телефон,order==Заказ,price==Цена,promo==Промокод',
            'amoFields' => 'name==425633||phone==432037||order==432043||promo==432045',
            'hooks' => 'FormItSaveForm,stikAmoCRM,cleanCart',
            'validate' => 'phone:required:minLength=^11^',
            'validationErrorMessage' => 'Неверные данные',
            'successMessage' => 'Заказ успешно отправлен',
            'submitVar' => 'cartSubmitter'
        ])}
    </div>
    <aside class="auten-order-aside">
        <div class="auten-order-aside__title">
            Ваш заказ
        </div>
        <div class="auten-order-aside__inner">
            <div class="auten-order-aside__field">
                Количество
                <div>
                    <span class="ms2_total_count">{$cart.total_count}</span>&nbsp;товара
                </div>
            </div>
            <div class="auten-order-aside__field">
                Всего
                <span class="ms2_total_cost">
                    {'!msMultiCurrencyPrice' | snippet : ['price' => $cart.real_total_cost]}
                    {$_modx->getPlaceholder('msmc.symbol_right')}
                </span>
            </div>
            {if $order.discount_cost != 0}
            <div class="auten-order-aside__field">
                {'stik_order_info_discount' | lexicon}
                <span class="ms2_total_discount">
                    - {'!msMultiCurrencyPrice' | snippet : ['price' => $order.discount_cost]}
                    {$_modx->getPlaceholder('msmc.symbol_right')}
                </span>
            </div>
            {/if}
            <div class="auten-order-aside__field" {if !$order.promocode_discount}style="display:none;"{/if}>
                {'stik_order_info_promocode_discount' | lexicon}
                <span class="ms2_order_promocode_discount">
                    {'!msMultiCurrencyPrice' | snippet : ['price' => $order.promocode_discount]}
                    {$_modx->getPlaceholder('msmc.symbol_right')}
                </span>
            </div>
            <div class="auten-order-aside__field">
                {'stik_order_info_delivery_cost' | lexicon}
                <span class="ms2_order_delivery_cost">
                    {if $order.delivery_cost == 0}
                        {"stik_order_delivery_free" | lexicon}
                    {else}
                        {'!msMultiCurrencyPrice' | snippet : ['price' => $order.delivery_cost]}
                        {$_modx->getPlaceholder('msmc.symbol_right')}
                    {/if}
                </span>
            </div>
            <div class="auten-order-aside__field auten-order-aside__final">
                {'stik_order_info_total_cost' | lexicon}
                <span class="ms2_order_cost">
                   {if $order.cost == 0}
                       {"stik_order_delivery_free" | lexicon}
                   {else}
                       {'!msMultiCurrencyPrice' | snippet : ['price' => $order.cost]} {$_modx->getPlaceholder('msmc.symbol_right')}
                   {/if}
                </span>
            </div>
        </div>
    </aside>
</main>