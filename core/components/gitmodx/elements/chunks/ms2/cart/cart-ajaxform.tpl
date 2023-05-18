<div class="auten-ordering">
    <section class="auten-cart">
        <div class="auten-cart__title">
            Корзина (<span class="ms2_total_count">{$total.count}</span>)
        </div>
        <div class="auten-cart__items">
            {foreach $products as $product}
            {$_modx->getChunk('cart.item', $product)}
            {set $productIds[] = $product.id}
            {/foreach}
        </div>
    </section>
    <form class="maxma_form auten-loyalty">
            <div class=" auten-promo ">
                <label class="auten-field maxma_field auten-promo__field {if $form["promocode"]}inactive{/if}">
                    <div class="auten-field__title">
                        Промокод
                    </div>
                    <input type="text" class="auten-field__input" name="promocode" value="{$form["promocode"]}">
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
                <span class="ms2_total_count">{$total.count}</span>&nbsp;товара
            </div>
        </div>
        <div class="auten-order-aside__field">
            Всего
            <span class="ms2_total_cost">
                {$total.cost}
                {$_modx->getPlaceholder('msmc.symbol_right')}
            </span>
        </div>
        <div class="auten-order-aside__field" {if !$order.promocode_discount}style="display:none;"{/if}>
                {'stik_order_info_promocode_discount' | lexicon}
                <span class="ms2_order_promocode_discount">
                    {'!msMultiCurrencyPrice' | snippet : ['price' => $order.promocode_discount]}
                    {$_modx->getPlaceholder('msmc.symbol_right')}
                </span>
        </div>
    </div>
</aside>