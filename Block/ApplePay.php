<?php
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

namespace Adyen\Payment\Block;

use Magento\Framework\View\Element\Template;

class ApplePay extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Adyen\Payment\Helper\Data
     */
    protected $_adyenHelper;

    /**
     * @var \Adyen\Payment\Helper\ApplePay
     */
    protected $_adyenApplePayHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * Customer session
     *
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $_currentCustomer;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Adyen\Payment\Helper\Data $adyenHelper,
        \Adyen\Payment\Helper\ApplePay $adyenApplePayHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Block\Product\Context $productContext,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        Template\Context $context,
        array $data = []
    ) {
        $this->_adyenHelper = $adyenHelper;
        $this->_adyenApplePayHelper = $adyenApplePayHelper;
        $this->_customerSession = $customerSession;
        $this->httpContext = $httpContext;

        $this->_productRepository = $productRepository;

        if ($productContext) {
            $this->_coreRegistry = $productContext->getRegistry();
        }

        $this->_currentCustomer = $currentCustomer;

        parent::__construct($context, $data);
//        $this->_isScopePrivate = true;
    }


    /**
     * @return bool
     */
    public function hasApplePayEnabled()
    {
        if (!$this->_adyenHelper->getAdyenApplePayConfigDataFlag("active")) {
            return false;
        }

        // if user is not logged in and quest checkout is not enabled don't show the button
        if ($this->_customerSession->isLoggedIn() &&
            !$this->_adyenHelper->getAdyenApplePayConfigData('allow_quest_checkout')) {
            return false;
        }

        return true;
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if ($this->_coreRegistry) {
            if (!$this->_coreRegistry->registry('product') && $this->getProductId()) {
                $product = $this->_productRepository->getById($this->getProductId());
                $this->_coreRegistry->register('product', $product);
            }

            $product = $this->_coreRegistry->registry('product');
            print_r(get_class($product));die();
            return $this->_coreRegistry->registry('product');
        }
        return null;
    }

    /**
     * @return array
     */
    public function getShippingMethods()
    {
        echo 'getShippingMethods';

        $product = $this->getProduct();


//        print_r($this->_customerSession->getId());
//die();
        if ($this->_customerSession->isLoggedIn()) {
            echo "LOGGED IN CORRECT WAY";
        }

        if ($this->isLoggedIn()) {

            // logged in
echo 'loggedin';
            $customerId = $this->_currentCustomer->getCustomerId();
echo 'customerid:'.$customerId;

            echo 'customerid2:'. $this->_customerSession->getCustomerId();

            echo 'test';



            echo 'end';

            die();


//            $defaultBilling =  $this->getCustomerSession()->getCustomer()->getDefaultBilling();

        }
    }

    /**
     * @return mixed
     */
    public function getMerchantIdentifier()
    {
        return $this->_adyenHelper->getApplePayMerchantIdentifier();
    }


    /**
     * Only possible if quest checkout is turned off
     *
     * @return bool
     */
    public function optionToChangeAddress()
    {
        if (
        !$this->_adyenHelper->getAdyenApplePayConfigData('allow_quest_checkout')
        ) {
            return $this->_adyenHelper->getAdyenApplePayConfigData('change_address');
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getShippingType()
    {
        return $this->_adyenHelper->getAdyenApplePayConfigData('shipping_type');
    }

    /**
     * Is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * retrieve customer session
     */
    public function getCustomerSession()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH)->getSession();
    }


    /**
     * Renders captcha HTML (if required)
     *
     * @return string
     */
//    protected function _toHtml()
//    {
////        $data = $this->_adyenApplePayHelper->getApplePayData();
////        print_r($data);die();
//
////        if ($this->_customerSession->isLoggedIn()) {
////            echo "LOGGED IN CORRECT WAY";
////        }
//
////        die();
//
//////        $blockPath = $this->_captchaData->getCaptcha($this->getFormId())->getBlockName();
//////        $block = $this->getLayout()->createBlock($blockPath);
//////        $block->setData($this->getData());
//////        return $block->toHtml();
//    }

}