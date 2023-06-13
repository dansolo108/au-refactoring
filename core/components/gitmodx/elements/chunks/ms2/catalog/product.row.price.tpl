<span class="au-card__price js_card_price">
    <span>{'!msMultiCurrencyPrice' | snippet : ['price' => $price]} {$_modx->getPlaceholder('msmc.symbol_right')}</span>
</span>
{if $old_price > $price}
    <span class="au-card__price  au-card__price_old js_card_old_price">
        <span>{'!msMultiCurrencyPrice' | snippet : ['price' => $old_price]} {$_modx->getPlaceholder('msmc.symbol_right')}</span>
    </span>
{/if}