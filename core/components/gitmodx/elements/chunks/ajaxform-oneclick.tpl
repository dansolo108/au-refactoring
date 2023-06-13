<form id="oneclickForm">
    <label>
        <span>Имя</span>
        <input type="text" name="name" required>
    </label>
    <label>
        <span>Телефон</span>
        <input type="tel" name="phone" placeholder="+7 XXX XXX XX XX" required>
    </label>
    <input id="oneclickOrder" type="hidden" name="order" value="{$order}">
    <input id="oneclickPrice" type="hidden" name="price" value="{$price}">
    <input id="oneclickPromocode" type="hidden" name="promo">
    <input id="oneclickSubmitter" type="submit" name="oneclickSubmitter" value="Оформить заказ ({$price} ₽)">
</form>