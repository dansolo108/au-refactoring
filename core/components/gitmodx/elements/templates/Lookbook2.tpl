{extends 'template:1'}

{block 'header'}
    <div class="au-lookbook__gallery">
        <img class="au-lookbooks__gallery-img" src="">
        <button class="au-close" aria-label="{'stik_modal_close' | lexicon}"></button>
    </div>
    {parent}
{/block}

{block 'main'}
    <main class="au-lookbook">
            {if $_modx->resource.video || $_modx->resource.img}
            <div class="au-lookbook__cover">
                {if $_modx->resource.video}
                    <video class="au-lookbooks__img" webkit-playsinline playsinline autoplay loop muted poster="">
                        <source src="{$_modx->resource.video}" type="video/mp4">
                    </video>
                {else}
                    {include 'picture' img=$_modx->resource.img height=417 width=1000 class='au-lookbooks__img'}
                {/if}
            </div>
            {/if}
        <div class="au-lookbook__content">
            <h1 class="au-h1  au-lookbook__title">{$_modx->resource.pagetitle}</h1>
            <p class="au-lookbook__text">{$_modx->resource.introtext}</p>

            {set $lookbook = json_decode($_modx->resource.lookbook2, true)}

            {set $snippetIsNeed = false}

            {if $lookbook}
            <div class="au-lookbook__items">
                {foreach $lookbook as $item}
                    {set $photo = $item.photo}
                    <div class="au-lookbook__item au-lookbook__item-{$item.size}">
                        <img src="{$photo}" loading="lazy">
                        {if $item.ids}
                            <div class="au-lookbook__button" onclick="return toggleLBProducts(this)">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10 12.4999C11.5259 12.4999 12.75 11.2835 12.75 9.79883H13.75C13.75 11.85 12.0639 13.4999 10 13.4999C7.93612 13.4999 6.25 11.85 6.25 9.79883H7.25C7.25 11.2835 8.47403 12.4999 10 12.4999Z" fill="#fff"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.88752 5C3.62625 5 2.5625 5.93951 2.40667 7.19112L1.28612 16.1911C1.10039 17.6828 2.26371 19 3.76697 19H16.2331C17.7363 19 18.8997 17.6828 18.7139 16.1911L17.5934 7.19112C17.4375 5.93951 16.3738 5 15.1125 5H13.4646C13.222 3.30385 11.7633 2 10 2C8.23679 2 6.77808 3.30385 6.53547 5H4.88752ZM4.88752 6C4.13075 6 3.49251 6.5637 3.39901 7.31467L2.27846 16.3147C2.16702 17.2097 2.86501 18 3.76697 18H16.2331C17.135 18 17.833 17.2097 17.7216 16.3147L16.601 7.31467C16.5075 6.5637 15.8693 6 15.1125 6H4.88752ZM10 3C8.79054 3 7.78167 3.85888 7.55003 5H12.45C12.2184 3.85888 11.2095 3 10 3Z" fill="#fff"/>
                                </svg>
                                <span>Изделия на фото</span>
                            </div>
                            <div class="au-lookbook__products" data-swipe-threshold="80">
                                {'!pdoPage' | snippet : [
                                    'element' => 'getProducts2',
                                    'parents' => 0,
                                    'resources' => $item.ids,
                                    'tpl' => 'lookbook.product',
                                ]}
                            </div>
                            {set $snippetIsNeed = true}
                        {/if}
                    </div>

                {/foreach}
            </div>
            {/if}

            {if $snippetIsNeed}
                {$_modx->runSnippet('!msOptionsPrice.initialize')}
            {/if}
            
            {set $intresting = 'pdoResources' | snippet : [
                'includeTVs' => 'img,video',
                'prepareTVs' => 'img,video',
                'processTVs' => 'img,video',
                'parents' => $_modx->resource.parent,
                'resources' => -$_modx->resource.id,
                'tpl' => 'tpl.lookbookSlider',
                'sortby' => 'menuindex',
                'sortdir' => 'ASC',
                'limit' => 3,
            ]}
    
            {if $intresting}
                <section class="au-lookbook__aside">
                    <h2 class="au-h2  au-lookbook__aside-title">{'stik_lookbook_see_also' | lexicon}</h2>
                    <div class="au-lookbook__slider-box">
                        <div class="au-lookbook__slider  swiper-container">
                            <div class="swiper-wrapper">
                                {$intresting}
                            </div>
                        </div>
                        <div class="au-swiper-buttons  au-desktop_xl">
                            <div class="au-lookbook__prev  au-swiper-button-prev  swiper-button-prev"></div>
                            <div class="au-lookbook__next  au-swiper-button-next  swiper-button-next"></div>
                        </div>
                        <div class="au-lookbook__pagination  au-swiper-pagination  swiper-pagination"></div>
                    </div>
                </section>
            {/if}
        </div>
    </main>
{'<script src="/assets/tpl/js/swiped-events.js"></script>' | jsToBottom}
{'
<script>
    function toggleLBProducts(target) {

        const products = target.closest(".au-lookbook__item")
            .querySelector(".au-lookbook__products"),
        childNodesArr = Array.from(products.getElementsByTagName("*"))

        target.classList.toggle("isActivated")
        document.body.classList.toggle("isFreezed")
        products.classList.toggle("isShown")

        products.addEventListener("swiped-down", (e) => {
                target.classList.toggle("isActivated")
                document.body.classList.toggle("isFreezed")
                products.classList.toggle("isShown")
        }, {capture: true, once: true, passive: false})

    }
</script>
' | jsToBottom : true}
{/block}