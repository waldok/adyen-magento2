/**
 *                       ######
 *                       ######
 * ############    ####( ######  #####. ######  ############   ############
 * #############  #####( ######  #####. ######  #############  #############
 *        ######  #####( ######  #####. ######  #####  ######  #####  ######
 * ###### ######  #####( ######  #####. ######  #####  #####   #####  ######
 * ###### ######  #####( ######  #####. ######  #####          #####  ######
 * #############  #############  #############  #############  #####  ######
 *  ############   ############  #############   ############  #####  ######
 *                                      ######
 *                               #############
 *                               ############
 *
 * Adyen Payment module (https://www.adyen.com/)
 *
 * Copyright (c) 2015 Adyen BV (https://www.adyen.com/)
 * See LICENSE.txt for license details.
 *
 * Author: Adyen <magento@adyen.com>
 */

// define(['uiComponent'], function(Component) {
//
//     return Component.extend({
//         initialize: function () {
//             this._super();
//             this.sayHello = "Hello this is content populated with KO!";
//         }
//     });
// });

define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery'
], function (Component, customerData, $) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.sayHello = "Hello this is content populated with KO!";
            this.applePayData = customerData.get('adyen-apple-pay');
            // this.applePay = {customerId: 'test' };
            // test2

            var productId = this.productId;

            // TODO: add observer on qty
            var qty = $('qty');

            // qty.subscribe(function (event) {
            //     alert("DF");
            // });


        },
        processApplePay: function () {

            var paymentRequest = {
                currencyCode: ''
            };

            alert("DF");
        },
    });
});