;(function (window, document, $, msMultiCurrencyConfig) {

    var MsMultiCurrency = function (options) {
        this.options = {
            actionUrl: '/assets/components/msmulticurrency/action.php',
            mode: 'normal',
            symbol: 'right',
            clsLoading: 'msmc-loading',
        };
        $.extend(true, this.options, options || {});
        this.init();
    };
    MsMultiCurrency.prototype.init = function () {
        this.currency = {};
        this.$doc = $(document);
        this.$body = $('body');
        this.bindEvents();
    };

    MsMultiCurrency.prototype.bindEvents = function () {
        var self = this;
        this.$doc.on('click', '.msmc-toggle', function (e) {
            e.preventDefault();
            self.menuToggle($(this).closest('.msmc'));
        });
        this.$doc.on('click', '.msmc-dropdown-item', function (e) {
            e.preventDefault();
            self.switchCurrency($(this).attr('data-id'));
        });

       /* if (miniShop2 != undefined && parseInt(this.options.cartUserCurrency) == 0) {
            miniShop2.Callbacks.add('Order.getcost.response.success', 'msmc_getcost', function (response) {
                if (parseInt(self.options.userCurrencyId) != parseInt(self.options.baseCurrencyId)) {
                    var newCost = parseFloat(response.data.cost) / parseFloat(self.options.course);
                    $(miniShop2.Order.orderCost, miniShop2.Order.order).text(miniShop2.Utils.formatPrice(newCost));
                }
            });
        }*/
    };

    MsMultiCurrency.prototype.menuToggle = function ($menu) {
        if ($menu.hasClass('msmc--opened')) {
            this.menuClose($menu);
        } else {
            this.menuOpen($menu);
        }
    };

    MsMultiCurrency.prototype.menuOpen = function ($menu) {
        $menu.addClass('msmc--opened');
    };

    MsMultiCurrency.prototype.menuClose = function ($menu, cb) {
        cb = cb || $.noop();
        $menu
            .removeClass('msmc--opened')
            .addClass('msmc--closed');

        setTimeout(function () {
            $menu.removeClass('msmc--closed');
            cb($menu);
        }, 500);
    };

    MsMultiCurrency.prototype.updateMenu = function (id) {
        var $toggle = $('.msmc-toggle'),
            $item = $('.msmc-dropdown-item[data-id="' + id + '"]'),
            $menu = $item.closest('.msmc');

        this.menuClose($menu, function () {
            $('.msmc-dropdown-item--active').removeClass('msmc-dropdown-item--active');
            $toggle.html($item.html());
            $item.addClass('msmc-dropdown-item--active');
        });
    };

    MsMultiCurrency.prototype.switchCurrency = function (id) {
        var ids = [],
            mode = 'normal',
            self = this;

        if (this.options.mode == 'ajax' || this.options.mode == 'auto') {
            mode = 'ajax';
            ids = this.findProductIds();

            if (this.options.mode == 'auto' && !ids.length) {
                mode = 'normal';
            }
        }

        this.updateMenu(id);
        this.$body.addClass(this.options.clsLoading);
        $.ajax({
            dataType: 'json',
            type: 'POST',
            cache: false,
            url: this.options.actionUrl,
            data: {
                id: id,
                ids: ids,
                ctx: this.options.ctx || '',
                cultureKey: this.options.cultureKey || '',
                controller: 'toggle',
                action: 'toggle',
                mode: mode
            },
            success: function (r) {
                if (r.result == true) {
                    if (mode == 'ajax') {
                        self.currency = r.data.currency || {};
                        self.setProductsSymbol();
                        self.setProductsPrice(r.data.products);
                    } else {
                        location.href = location.href;
                    }
                }
            },
            complete: function () {
                self.$body.removeClass(self.options.clsLoading);
            },
            error: function (e) {
                console.error('error set currency', e);
            }
        });
    };

    MsMultiCurrency.prototype.findProductIds = function () {
        var ids = [];
        $('[msmc-pid]').each(function () {
            var id = $(this).attr('msmc-pid');
            if (id) ids.push(id);
        });
        return ids;
    };

    MsMultiCurrency.prototype.setProductsSymbol = function () {
        var symbol = this.currency['symbol_' + this.options.symbol];
        $('[msmc-symbol]').html(symbol);
    };

    MsMultiCurrency.prototype.setProductsPrice = function (products) {
        $.each(products || {}, function (key, data) {
            var $pid = $('[msmc-pid="' + key + '"]');
            if (!$pid.length) return true;
            $('[msmc-price]', $pid).html(data.price);
            $('[msmc-oldprice]', $pid).html(data.old_price);
        });
    };

    $(document).ready(function () {
        var msMultiCurrency = new MsMultiCurrency(msMultiCurrencyConfig || {});
    });


})(window, document, jQuery, msMultiCurrencyConfig);