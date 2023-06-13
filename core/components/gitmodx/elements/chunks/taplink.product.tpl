{if $offers}
<form class="swiper-slide product-card" data-offers='[
{foreach $offers as $key => $offer index=$idx}
    {
        "image":"{$offer.image | phpthumbon : "w=350&h=448&zc=1"}",
        "price":{$offer.price},
        "color":"{$offer.color}"
    }{if !$value@last},{/if}
{/foreach}
]'>
    <a href="{$id | url}" class="product-card__image-wrapper">
        <img class="product-card__image" src="{$offers[0].image}" alt="{$pagetitle} {$offers[0]["color"]}">
    </a>
    <div class="product-card__colors">
        {foreach $offers as $key => $offer index=$idx}
            {set $hex = 'msoGetColor' | snippet : ['input' => $offer["color"]]}
            <label class="input-parent product-card__color {$idx == 0?"active":""} {if $hex == "#ffffff"}is-white{/if}" style="--color: {$hex};">
                <input type="radio" name="color" {$idx == 0?"checked":""} value="{$offer["color"]}">
            </label>
        {/foreach}
    </div>
    <div class="product-card__name">{$pagetitle}</div>
    <div class="product-card__price price">
        {$offers[0].price | preg_replace : "/(\D00)/" : "" | preg_replace : "/(\d)(?=(\d{3})+$)/" : "$1 "}
    </div>
</form>
{/if}
