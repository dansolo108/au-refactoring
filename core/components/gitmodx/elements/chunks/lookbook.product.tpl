{set $colors = 'getProductDetails' | snippet : [
'id'=>$product_id,
'details'=>['color'],
]}
{set $activeColor = $colors[0]['value']?:false}
<script>
    PageInfo.products['{$product_id}'] = {$product_id | getJSONPageInfo};
</script>
<form class="au-card ms2_form msoptionsprice-product au-lookbook__products-item" method="post" id="product-{$product_id}_{rand(100,999)}" product-id="{$product_id}">
    <div class="au-card__img-box">
        <div class="au-card__gallery js_card-img">
            {'!msGallery' | snippet : [
                'product' => $product_id,
                'tpl' => 'stik.msGallery.card',
                'where' => [
                'description' => $color,
            ],
            ]}
        </div>
    </div>
    <div class="au-card__meta">
        <a class="au-card__link" href="{$product_id | url}?{$color?'color='~$color:''}">
            {$pagetitle}
        </a>
        <div class="au-card__color-box">
            <div class="au-card__colors">
                {'!getProductDetails' | snippet : [
                    'details'=>'color',
                    'id'=>$product_id,
                    'productidx'=>$idx,
                    'active'=>$color,
                    'tpl'=>'product.row.color2',
                ]}
            </div>
        </div>
        <div class="au-card__price-box js_card-prices">
            {$_modx->getChunk('product.row.price',['old_price'=>$old_price,'price'=>$price])}
        </div>
        <input type="hidden" name="id">
        <input type="hidden" name="count" value="1">
        <button type="submit" name="ms2_action" value="cart/add" style="display: none;"></button>
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.00005 9.61825C9.39781 9.61825 10.5191 8.50401 10.5191 7.14404H11.4351C11.4351 9.02293 9.89063 10.5343 8.00005 10.5343C6.10946 10.5343 4.56494 9.02293 4.56494 7.14404H5.48096C5.48096 8.50401 6.6022 9.61825 8.00005 9.61825Z" fill="#2E48A3"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.31684 2.74807C2.16149 2.74807 1.18707 3.60868 1.04433 4.75518L0.017881 12.9994C-0.152252 14.3658 0.913375 15.5724 2.29039 15.5724H13.7096C15.0866 15.5724 16.1523 14.3658 15.9821 12.9994L14.9557 4.75518C14.8129 3.60868 13.8385 2.74807 12.6831 2.74807H11.1736C10.9514 1.19436 9.61521 0 7.99999 0C6.38485 0 5.04864 1.19436 4.8264 2.74807H3.31684ZM3.31684 3.66409C2.62362 3.66409 2.03898 4.18045 1.95333 4.86835L0.926886 13.1126C0.824805 13.9324 1.46418 14.6564 2.29039 14.6564H13.7096C14.5358 14.6564 15.1752 13.9324 15.0731 13.1126L14.0466 4.86835C13.961 4.18045 13.3764 3.66409 12.6831 3.66409H3.31684ZM7.99999 0.916022C6.89209 0.916022 5.96795 1.70277 5.75576 2.74807H10.2442C10.0321 1.70277 9.10791 0.916022 7.99999 0.916022Z" fill="#2E48A3"/>
        </svg>
        <button type="button" class="get-color-sizes au-btn" data-pid="{$product_id}">
            Добавить в корзину
        </button>
    </div>
    <a class="au-card__like msfavorites" href="" aria-label="Добавить в избранное" data-click data-data-list="default" data-data-type="resource" data-data-key="{$product_id}"></a>
</form>