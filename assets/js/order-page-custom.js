document.addEventListener('DOMContentLoaded', () => {
    checkCartInfo()
    const cartForm = document.getElementById('cartform')
    if (!!cartForm) {
        const orderField = cartForm.querySelector('input[name="order"]'),
            priceField = cartForm.querySelector('input[name="price"]'),
            promoField = cartForm.querySelector('input[name="promo"]')
        if (!!orderField && !!priceField) {
                setValue(orderField, getOrderItems())
                setValue(priceField, getTotalCost())
            miniShop2.Callbacks.add('Cart.change.ajax.done', 'change_cart', function() {
                setValue(orderField, getOrderItems())
                setValue(priceField, getTotalCost())
            })
            miniShop2.Callbacks.add('Cart.remove.ajax.done', 'remove_cart', function() {
                setValue(orderField, getOrderItems())
                setValue(priceField, getTotalCost())
            })
            miniShop2.Callbacks.add('Cart.add.ajax.done', 'add_cart', function() {
                setValue(orderField, getOrderItems())
                setValue(priceField, getTotalCost())
            })
            miniShop2.Callbacks.add('Cart.clean.ajax.done', 'clean_cart', function() {
                setValue(orderField, getOrderItems())
                setValue(priceField, getTotalCost())
            })
        }
        if (!!promoField) {
          getPromocode(promoField)
        }
        const phoneField = cartForm.querySelector('input[type=tel]')
        disableSubmitBtn(phoneField)
    }
})
document.addEventListener('input', (e) => {
  const target = e.target
  if (!target.matches('input[type=tel]')) return
  disableSubmitBtn(target)
})
$(document).on('submit', '#cartform', function(e) {
    const submitter = this.querySelector('input[type="submit"]')
    submitter.disabled = true
    submitter.value = 'Заказ создается...'
})
$(document).on('af_complete', function (e, res) {
    const form = res.form
    if (form.attr('id') == 'cartform') {
        if (res.success) {
            ym(86113805, 'reachGoal', 'customCartOrder')
            window.location.href = "https://autentiments.com/success"
        } else {
            const submitter = form.querySelector('input[type="submit"]')
            submitter.disabled = false
            submitter.value = 'Оформить заказ'
        }        
    }
})
function setValue(field, value) {
    field.setAttribute('value', value)
}
function getOrderItems() {
    const itemNodes = document.querySelectorAll('.auten-cart-item')
    if (!!itemNodes) {
        const orderItemsArr = []
        itemNodes.forEach(item => {
            const paramsNodes = item.querySelector('.auten-cart-item__params').children,
            title = item.querySelector('.auten-cart-item__title').textContent.trim(),
            paramsArr = []
            Array.from(paramsNodes).forEach(param => {
                param.hasAttribute('title') ?
                    paramsArr.push(param.getAttribute('title')) :
                    paramsArr.push(param.textContent)
            })
            const params = paramsArr.join('/')
            orderItemsArr.push(`${title} (${params})`)
        })
        if (orderItemsArr.length > 0) {
            const orderItems = orderItemsArr.join('\n')
            return orderItems
        }
    }
    return false
}
function getTotalCost() {
    const costNode = document.querySelector('.ms2_total_cost')
    if(!!costNode) {
        const totalCost = costNode.textContent.replace(/[^0-9]/g, '')
        return totalCost
    }
    return false
}
function getPromocode(field) {
    const promocodeNode = document.querySelector('.auten-promo__field')
    if (!!promocodeNode) {
      const promocodeObserver = new MutationObserver(mR => {
        const target = mR[0].target,
        oldValue = mR[0].oldValue
        if (!!oldValue && !oldValue.includes('inactive') && target.className.includes('inactive')) {
          const value = target.childNodes[3].value
          field.value = value
        }
        if (!!oldValue && oldValue.includes('inactive') && !target.className.includes('inactive')) {
          field.value = ''
        }
      })
      const observerOptions = {
        attributes: true,
        attributeFilter: ['class'],
        attributeOldValue: true,
        childList: false,
        subtree: false,
        characterData: false
      }
      promocodeObserver.observe(promocodeNode, observerOptions)
    }
}
function checkCartInfo() {
    const mOverlay = document.querySelector(".au-modal-overlay"),
    mCartInfo = document.querySelector(".au-modal-customcart")
    if (!!mOverlay && !!mCartInfo) {
        const ls = localStorage.getItem("customCart")
        if (!ls) {
            setTimeout(() => {
                mOverlay.classList.add("active")
                mCartInfo.classList.add("active")
            }, 5000)
        }
    }
}
function disableSubmitBtn(target) {
  const submitButton = target.closest('form').querySelector('input[type=submit]')
  target.value.length < 12 ? submitButton.disabled = true : submitButton.disabled = false 
}