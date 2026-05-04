
define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote'
], function (Component, quote) {
    'use strict';
        
    return Component.extend({
        defaults: {
            template: 'Elsnertech_Multidiscount/summary/discount'
        },
        totals: quote.getTotals(),

        /**
         * @return {*|Boolean}
         */
        isDisplayed: function() {
            return this.getPureValue() != 0; //eslint-disable-line eqeqeq
        },

        /**
         * @return {*}
         */
        getCouponCode: function () {
            if (!this.totals()) {
                return null;
            }

            return this.totals()['coupon_code'];
        },

        /**
         * @return {*}
         */
        getCouponLabel: function () {
            if (!this.totals()) {
                return null;
            }

            return this.totals()['coupon_label'];
        },

        /**
         * Get discount title
         *
         * @returns {null|String}
         */
        getTitle: function () {
            var discountSegments;

            if (!this.totals()) {
                return null;
            }

            discountSegments = this.totals()['total_segments'].filter(function (segment) {
                return segment.code.indexOf('discount') !== -1;
            });

            return discountSegments.length ? discountSegments[0].title : null;
        },

        /**
         * Get discount title
         *
         * @returns {null|String}
         */
        getMultiDiscountValue: function () {
            var multiDiscountSegments;

            if (!this.totals() || !this.totals()['total_segments']) {
                return window.checkoutConfig &&
                    window.checkoutConfig.quoteData &&
                    window.checkoutConfig.quoteData.multilineDiscount ?
                    window.checkoutConfig.quoteData.multilineDiscount :
                    null;
            }
            
            multiDiscountSegments = this.totals()['total_segments'].filter(function (segment) {
                return segment.code === 'multiline_discount' || segment.code === 'multilineDiscount';
            });
            
            if (multiDiscountSegments.length) {
                return multiDiscountSegments[0].area;
            }

            return window.checkoutConfig &&
                window.checkoutConfig.quoteData &&
                window.checkoutConfig.quoteData.multilineDiscount ?
                window.checkoutConfig.quoteData.multilineDiscount :
                null;
        },

        /**
         * @return {Number}
         */
        getPureValue: function () {
            var price = 0;

            if (this.totals() && this.totals()['discount_amount']) {
                price = parseFloat(this.totals()['discount_amount']);
            }

            return price;
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        },

        /**
         * @return {*|Boolean}
         */
        isMultiLineEnable: function () {
            return window.checkoutConfig && window.checkoutConfig.multidiscountEnabled;
        },

        /**
         * @return {*}
         */
        getDiscountsDetails: function () {
            var discounts = [];
            var multiDiscountValue = this.getMultiDiscountValue();
            
            if (multiDiscountValue) {
                try {
                     discounts = JSON.parse(multiDiscountValue);
                } catch (e) {
                     discounts = [];
                }
            }
            
            if (Array.isArray(discounts)) {
                for(var i = 0; i < discounts.length; i++){
                    var price = discounts[i].value;
                    price = this.getFormattedPrice(price);
                    discounts[i].value = '-'+price;
                }
            }

            return discounts;
        }
    });
});
