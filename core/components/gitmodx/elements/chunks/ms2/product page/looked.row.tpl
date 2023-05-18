<script>
    PageInfo.products['{$id}'] = {$id | getJSONPageInfo};
</script>
<form class="au-card  swiper-slide" id="product-{$id}-{$idx}" product-id="{$id}">
    <a class="au-card__link" href="{$id | url}?{$color?'color='~$color:''}">
        <div class="au-card__img-box">
            <div class="au-card__gallery js_card-img">
                {'!msGallery' | snippet : [
                'product' => $id,
                'tpl' => 'stik.msGallery.card',
                ]}
            </div>
            {if $new || $sale || $soon}
                <div class="au-card__marks">
                    {if $new?}
                        <span class="au-card__mark">New</span>
                    {/if}
                    {if $sale?}
                        {set $discount = $old_price | discount : $price}
                        {if $discount > 0}
                            <span class="au-card__mark">Sale -{$discount}%</span>
                        {/if}
                    {/if}
                    {if $soon?}
                        <span class="au-card__mark">Soon</span>
                    {/if}
                </div>
            {/if}
        </div>
        <div class="au-card__meta">
            <div class="au-card__title">{$pagetitle}</div>
            <div class="au-card__color-box">
                <div class="au-card__colors">
                    {'!getProductDetails' | snippet : [
                    'details'=>'color',
                    'id'=>$id,
                    'productidx'=>$idx,
                    'active'=>$color,
                    'tpl'=>'product.row.color',
                    ]}
                </div>
            </div>
            <div class="au-card__price-box js_card-prices">
                {$_modx->getChunk('product.row.price',['old_price'=>$old_price,'price'=>$price])}
            </div>
            <a class="au-card__like msfavorites" href="" aria-label="Добавить в избранное" data-click data-data-list="default" data-data-type="resource" data-data-key="{$id}" {if $_modx->resource.template == 5}data-msfavorites-mode="list"{/if}></a>
        </div>
    </a>
</form>