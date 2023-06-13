<form id="checkpromocode-form">
    <label class="input-parent auten-field">
        <div class="auten-field__title" style="position: relative;">Промокод</div>
        <input type="text" name="promocode" value="{'!getPromocodeName'|snippet}" class="auten-field__input" required style="margin-top: 10px;">
    </label>
    <input type="hidden" name="product" value="{$product}">
    <input type="hidden" name="discount" value="{'!getPromocodeDiscount'|snippet}">
    <input type="submit" name="promocodeChecker" value="Применить" class="auten-button">
</form>