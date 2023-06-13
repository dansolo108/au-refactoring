document.addEventListener('DOMContentLoaded', () => {

  const ocBtn = document.querySelector('.au-product__oneclick'),
    ocPopup = document.querySelector('.oneclick'),
    ocMeta = document.querySelector('.au-product__description')

  if (ocBtn && ocPopup && ocMeta) {

    const ocProduct = document.getElementById('product'),
      ocPriceNode = ocPopup.querySelector('.oneclick-price'),
      ocColorNode = ocPopup.querySelector('.oneclick-color'),
      ocSizeNode = ocPopup.querySelector('.oneclick-size'),
      ocImg = ocPopup.querySelector('.oneclick-img img')

    const ocOrderField = document.getElementById('oneclickOrder'),
      ocPriceField = document.getElementById('oneclickPrice'),
      ocPromocodeField = document.getElementById('oneclickPromocode'),
      ocSubmitter = document.getElementById('oneclickSubmitter')

    let ocOrderFieldValue = ocOrderField.value

    ocBtn.addEventListener('click', () => {

      const priceNode = ocMeta.querySelector('.au-card__price span'),
        colorNode = ocMeta.querySelector('.au-product__color.active'),
        sizeNode = ocMeta.querySelector('.au-product__size.active'),
        imgNode = document.querySelector('.au-product__img'),
        tmp = ocProduct.value.split(',')

      tmp[1] = priceNode.textContent.replace(/[^0-9]/g, '')
      ocProduct.value = tmp.join()
      ocProduct.setAttribute('value', tmp.join())

      ocPriceNode.textContent = priceNode.textContent
      ocColorNode.style.background = colorNode.style.background
      ocSizeNode.textContent = sizeNode.textContent
      ocImg.src = imgNode.src

      ocPriceField.setAttribute('value', tmp[1])
      ocOrderField.setAttribute('value', `${ocOrderFieldValue} (${colorNode.getAttribute('title')}/${sizeNode.textContent.trim()}/1 шт.)`)

      ocPopup.classList.add('active')
      openModalАdditionally($('.au-modal-overlay'))

    })

    $(document).on('submit', '#checkpromocode', function (e) {
      e.preventDefault()
      const data = new FormData(this),
      options = {
        method: 'POST',
        body: data
      }
      ;(async () => {
        const rawResponse = await fetch(this.action, options),
        response = await rawResponse.json(),
        promocode = document.getElementById('promocode'),
        step = document.getElementById('step'),
        submitter = this.querySelector('input[type="submit"]')
        if (response.success) {

          const price = ocPriceNode.textContent.replace(/[^0-9]/g, '')

          if (response.discount) {

            promocode.setAttribute('value', data.get('promocode'))
            promocode.readOnly = true

            step.setAttribute('value', 'cancel')
            submitter.setAttribute('value', 'Отменить')

            ocPromocodeField.setAttribute('value', data.get('promocode'))
            ocPriceField.setAttribute('value', (+price - +response.discount))

            ocSubmitter.setAttribute('value', `Оформить заказ (${(+price - +response.discount)} ₽)`)
            
          } else {

            promocode.setAttribute('value', '')
            promocode.value = ''
            promocode.readOnly = false
            step.setAttribute('value', 'check')
            submitter.setAttribute('value', 'Применить')

            ocPromocodeField.setAttribute('value', '')
            ocPriceField.setAttribute('value', price)

            ocSubmitter.setAttribute('value', `Оформить заказ (${price} ₽)`)

          }

          AjaxForm.Message.success(response.message)
        } else {
          promocode.value = ''
          AjaxForm.Message.error(response.message)
        }
      })()
    })

    $(document).on('submit', '#oneclickForm', function(e) {
      ocSubmitter.disabled = true
      ocSubmitter.value = 'Заказ создается...'
    })
    $(document).on('af_complete', function (e, r) {
        const form = r.form
        if (form.attr('id') == 'oneclickForm') {
            if (r.success) {

                const content = document.querySelector('.oneclick-content'),
                title = document.createElement('div'),
                text = document.createElement('div'),
                href = document.createElement('a')

                title.classList.add('oneclick-subtitle')
                text.classList.add('oneclick-text')
                href.classList.add('oneclick-href')

                title.textContent = 'Заказ успешно создан'
                text.textContent = 'Менеджер Autentiments свяжется с вами в ближайшее рабочее время (9:00 – 21:00) для подтверждения заказа'
                href.textContent = 'В каталог'

                href.href = '/catalog'

                content.innerHTML = ''
                content.append(title, text, href)
                
                ym(86113805, 'reachGoal', 'oneClickOrder')

            } else {
                ocSubmitter.disabled = false
                ocSubmitter.value = 'Оформить заказ'
            }        
        }
    })
  }
})