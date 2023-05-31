{set $hex = 'msoGetColor' | snippet : ['input' => $value]}
{set $valueId = 'msoGetColor' | snippet : ['input' => $value, 'return_id' => true]}
{set $rand = rand(100,999)}
<div class="au-card__color-item">
        {set $unique_id = 'color_'~$valueId~'_'~$productidx~'_'~$idx}
        <input class="au-card__color-input" hidden type="radio" name="color" value="{$value}" id="{$unique_id}_{$rand}" data-product="{$product_id}" {if $active == $value}checked{/if}>
        <label class="au-card__color" style="background: {$hex};" for="{$unique_id}_{$rand}" {if $hex == '#ffffff'}data-color="white"{/if} title="{('stik_color_'~$valueId) | lexicon}">
        </label>
</div>