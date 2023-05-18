<form id="cartform">
    <label class="input-parent auten-field">
        <div class="auten-field__title" style="position: relative;">Имя</div>
        <input type="text" name="name" class="auten-field__input" required style="margin-top: 10px;">
    </label>
    <label class="input-parent auten-field" style="margin-top: 20px;">
        <div class="auten-field__title" style="position: relative; margin-bottom: 10px;">Телефон</div>
        <input type="tel" name="phone" placeholder="+7 XXX XXX XX XX" class="auten-field__input" required>
    </label>
    <input type="hidden" name="order">
    <input type="hidden" name="price">
    <input type="hidden" name="promo">
    <input type="submit" name="cartSubmitter" value="Оформить заказ" class="auten-button" style="margin-top: 20px;">
</form>