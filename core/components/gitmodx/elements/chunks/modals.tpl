{if $_modx->resource.id != 15}
<div class="au-modal-cookie">
    <div class="au-modal-cookie__content">
        <button class="au-close-light  au-modal-cookie__close" aria-label="{'stik_modal_close' | lexicon}"></button>
        <span class="au-modal-cookie__title">{'stik_modal_cookie_title' | lexicon}</span>
        <p class="au-modal-cookie__text">{'stik_modal_cookie_text' | lexicon}</p>
    </div>
</div>
{/if}
{if !$_modx->isAuthenticated('web')}
<div class="au-modal au-modal-sale modal " style="max-width:510px">
    <div class="au-info-size__wrapper">
        <button class="au-close au-modal-size__close" onclick="localStorage.discountClose = true;" aria-label="Закрыть"></button>
        <div class="au-modal__content  au-info-size__content">
            <h3 class="au-info-discount__title">Дарим скидку 10% на первую покупку</h3>
            <h2 class="au-info-discount__sub-title">новым участникам программы лояльности</h2>
            <div style="margin-bottom:60px; display:flex;flex-direction:column;padding:0 20px;">
                {'!smsLoad' | snippet : ['js' => '/assets/components/sms/js/web/custom.js?v=0.1']}
                {$_modx->lexicon->load('sms:default')}
                <form method="post" class="smsLoginForm">
                    <div class="custom-form__group js_sms_phone" style="flex-direction:column">
                        <label for="sms_phone" class="au-info-discount__info-text">авторизируйтесь чтобы получить скидку</label>
                        <input type="tel" id="sms_phone" class="custom-form__input sms_phone_input" name="phone" data-val="" placeholder="{'sms_web_phone' | lexicon}">
                        <span class="int-tel-error error_field"></span>
                    </div>
                    <div class="custom-form__group sms_code js_sms_code" style="display:none;">
                        <input type="text" class="custom-form__input sms_code_input" name="code" maxlength="{$_modx->config.sms_code_length}" data-val="" placeholder="{'sms_web_code' | lexicon}">
                        <span class="error error-code" style="display:none;">{'stik_profile_sms_code_error' | lexicon}</span>
                        <div class="input-tel-text count-seconds-wrapper" style="display:none;">
                            {'stik_profile_sms_code_time' | lexicon} <span class="count-seconds"><span>60</span> {'stik_profile_sms_code_sec' | lexicon}</span>
                        </div>
                    </div>
                    <div class="js_sms_buttons_group">
                        <button type="button" class="au-btn  au-login__submit  au-login__submit-register js_sms_code_send" disabled>{'sms_web_btn_code_send' | lexicon}</button>
                        <span class="custom-code-link-box" style="display:none;">
                            <a class="au-btn  au-login__submit  au-login__submit-register js_sms_resend_code" href="#">{'stik_profile_sms_code_resend' | lexicon}</a>
                        </span>
                        <button type="button" class="btn btn-primary sms_code_btn js_sms_code_check">{'sms_web_btn_code_check' | lexicon}</button>
                    </div>
                </form>
                <button style="color:#999;margin-top:20px" onClick="closeForModal();localStorage.discountClose = 1;">Продолжить без скидки</button>
            </div>
            
        </div>
    </div>
</div>
<!--script>
    setTimeout(()=>{
        if(localStorage.discountClose){
        }
        else{
            openModalАdditionally($('.au-modal-overlay'));
            $('.au-modal-sale').addClass('active');
        }
    },8000);
</script-->
{/if}
<div class="au-modal au-modal-sale modal " style="max-width:510px">
    <div class="au-info-size__wrapper">
        <button class="au-close au-modal-size__close" onclick="localStorage.discountClose = true;" aria-label="Закрыть"></button>
        <div class="au-modal__content  au-info-size__content">
            <h3 class="au-info-discount__title">Дарим скидку 10% на первую покупку</h3>
            <h2 class="au-info-discount__sub-title">новым участникам программы лояльности</h2>
            <div style="margin-bottom:60px; display:flex;flex-direction:column;padding:0 20px;">
                {'!smsLoad' | snippet : ['js' => '/assets/components/sms/js/web/custom.js?v=0.1']}
                {$_modx->lexicon->load('sms:default')}
                <form method="post" class="smsLoginForm">
                    <div class="custom-form__group js_sms_phone" style="flex-direction:column">
                        <label for="sms_phone" class="au-info-discount__info-text">авторизируйтесь чтобы получить скидку</label>
                        <input type="tel" id="sms_phone" class="custom-form__input sms_phone_input" name="phone" data-val="" placeholder="{'sms_web_phone' | lexicon}">
                        <span class="int-tel-error error_field"></span>
                    </div>
                    <div class="custom-form__group sms_code js_sms_code" style="display:none;">
                        <input type="text" class="custom-form__input sms_code_input" name="code" maxlength="{$_modx->config.sms_code_length}" data-val="" placeholder="{'sms_web_code' | lexicon}">
                        <span class="error error-code" style="display:none;">{'stik_profile_sms_code_error' | lexicon}</span>
                        <div class="input-tel-text count-seconds-wrapper" style="display:none;">
                            {'stik_profile_sms_code_time' | lexicon} <span class="count-seconds"><span>60</span> {'stik_profile_sms_code_sec' | lexicon}</span>
                        </div>
                    </div>
                    <div class="js_sms_buttons_group">
                        <button type="button" class="au-btn  au-login__submit  au-login__submit-register js_sms_code_send" disabled>{'sms_web_btn_code_send' | lexicon}</button>
                        <span class="custom-code-link-box" style="display:none;">
                            <a class="au-btn  au-login__submit  au-login__submit-register js_sms_resend_code" href="#">{'stik_profile_sms_code_resend' | lexicon}</a>
                        </span>
                        <button type="button" class="btn btn-primary sms_code_btn js_sms_code_check">{'sms_web_btn_code_check' | lexicon}</button>
                    </div>
                </form>
                <button style="color:#999;margin-top:20px" onClick="closeForModal();localStorage.discountClose = 1;">Продолжить без скидки</button>
            </div>
            
        </div>
    </div>
</div>
{*
{'!AjaxForm' | snippet : [
    'snippet' => 'newsletterSubscribe',
    'form' => 'welcomeSubscribe.form',
    'emailTo' => $_modx->config.ms2_email_manager,
    'subject' => 'stik_newsletter_form_subject' | lexicon,
    'subjectConfirm' => 'stik_newsletter_form_subject_confirm' | lexicon,
    'tpl' => 'newsletterSubscribe.email',
    'tplConfirm' => 'newsletterSubscribeEmailTplConfirm',
    'submitVar' => 'welcomeform',
]}
*}

{if !$_modx->hasSessionContext('web')}
    <div class="au-modal  au-modal-login  modal">
        <div class="au-modal__wrapper  au-login__wrapper">
            <button class="au-close  au-modal-login__close  au-desktop" aria-label="{'stik_modal_close' | lexicon}"></button>
            <div class="au-modal__content  au-modal-login__content">
                <div class="au-login__img-box  au-desktop">
                    <picture>
                        <!-- <source type="image/webp" srcset=""> -->
                        <img width="400" height="550" class="au-login__img" src="/assets/tpl/img/au-login-img_login.jpg" alt="">
                    </picture>
                    <picture>
                        <!-- <source type="image/webp" srcset=""> -->
                        <img width="400" height="550" class="au-login__img  au-login__img_register" src="/assets/tpl/img/au-login-img_register.jpg" alt="">
                    </picture>
                </div>
    
                <div class="au-login__tabs">
                    <div class="au-login__tab  au-tab-login-content  active" data-tab="login">
                        {if $_modx->resource.template != 8}
                            {include 'login'}
                        {/if}
                    </div>
    
                    {*<div class="au-login__tab  au-tab-login-content" data-tab="register">
                        {if $_modx->resource.template != 13}
                            {include 'register'}
                        {/if}
                    </div>
    
                    <div class="au-login__tab  au-tab-login-content" data-tab="recovery">
                        <a class="au-login__recovery-back  au-tab-login-title" href="login">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.93411 16.7176L6.64122 17.4247L14.0659 10L6.64117 2.5754L5.93407 3.2825L12.6516 10.0001L5.93411 16.7176Z" fill="#1A1714"/>
                            </svg>
                        </a>
                        {if $_modx->resource.template != 10}
                            {include 'reset_pw'}
                        {/if}
                    </div>*}
                </div>
            </div>
        </div>
    </div>
{/if}

<div class="au-modal au-modal-cart  modal" id="ms2_cart_modal">
    {* содержимое в чанке stik.msCart.ajax *}
</div>

<div class="au-modal-text-page  container  modal">
    {* содержимое в чанке ajaxModalInfo *}
</div>

<div class="au-modal au-modal-customcart modal " style="max-width:700px">
    <div class="au-info-size__wrapper">
        <div class="au-modal__content  au-customcart__content">
            <div class="modal-content__banner">
                <picture>
                    <source media="(max-width: 769px)" srcset="/assets/tpl/img/customcart-xs.png">
                    <source media="(min-width: 770px)" srcset="/assets/tpl/img/customcart.png">
                    <img data-src="/assets/tpl/img/customcart.png" alt="Улучшаем сайт для вас">
                </picture>
            </div>
            <div class="modal-content__text">
                <h3 class="au-info-customcart__title">Дорогие покупатели!</h3>
                <p>В связи с техническими работами на сайте, заказы временно оформляются через менеджеров Autentiments.</p>
                <p>Вы так же можете добавить товары в корзину и использовать промокоды и бонусные баллы.</p>
                <p>До 31.05.2023 действует <span>бесплатная доставка по России при любой сумме заказа</span></p>
                <input type="checkbox" id="agreeCheck" name="agreeCheck">
                <label for="agreeCheck">
                  <span>Я подтверждаю, что ознакомлен(а) с условиями оформления заказа на сайте autentiments.com</span>
                </label>
                <button class="au-close au-modal-customcart__button" onclick="closeForModal();localStorage.customCart=1;" aria-label="Закрыть" disabled>Все ясно</button>
            </div>
        </div>
    </div>
</div>
{'
<script>
  const cb = document.getElementById("agreeCheck"),
  lbl = document.querySelector("label[for=agreeCheck]"),
  btn = document.querySelector(".au-modal-customcart__button")

  if (cb && lbl && btn) {
    cb.addEventListener("change", function() {
      if (this.checked) {
        lbl.classList.contains("unchecked") ? 
          lbl.classList.remove("unchecked") : null
        lbl.classList.add("checked")
        btn.disabled = false
      } else {
        lbl.classList.contains("checked") ? 
          lbl.classList.remove("checked") : null
        lbl.classList.add("unchecked")
        btn.disabled = true
      }
    })
  }
</script>
' | jsToBottom}