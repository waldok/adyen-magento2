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

namespace Adyen\Payment\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Helper\Session\CurrentCustomerAddress;

class ApplePay implements SectionSourceInterface
{

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var CurrentCustomerAddress
     */
    protected $_currentCustomerAddress;

    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @var \Adyen\Payment\Logger\AdyenLogger
     */
    protected $_adyenLogger;

    protected $_localeLists;

    /**
     * ApplePay constructor.
     * @param CurrentCustomer $currentCustomer
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        CurrentCustomer $currentCustomer,
        CurrentCustomerAddress $currentCustomerAddress,
        \Magento\Customer\Model\Session $customerSession,
        \Adyen\Payment\Logger\AdyenLogger $adyenLogger,
        \Magento\Framework\Locale\ListsInterface $localeLists
    ) {
        $this->_customerSession = $customerSession;
        $this->_currentCustomerAddress = $currentCustomerAddress;
        $this->currentCustomer = $currentCustomer;
        $this->_adyenLogger = $adyenLogger;
        $this->_localeLists = $localeLists;
    }
    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {

        $this->_adyenLogger->error("START SECIOTN DATA");

        $customerId = null;
        if ($this->_customerSession->isLoggedIn()) {

            $this->_adyenLogger->error("CUSSTOMER LOGIN!");

            $customerId = $this->_customerSession->getCustomerId();

            $customer = $this->currentCustomer->getCustomer();

            $this->_adyenLogger->error("CUSSTOMER LOGIN2!");

            $billingAddress = $this->_currentCustomerAddress->getDefaultBillingAddress();

            $this->_adyenLogger->error("CUSSTOMER LOGIN3!");

            if ($billingAddress) {
                $lastName = trim($billingAddress->getMiddlename() . " " . $billingAddress->getLastName());
                $countryName = $this->_localeLists->getCountryTranslation($billingAddress->getCountryId());

                $billingContract = [
                    'emailAddress' => $customer->getEmail(),
                    'phoneNumber' => $billingAddress->getTelephone(),
                    'familyName' => $lastName,
                    'givenName' => $billingAddress->getFirstname(),
                    'addressLines' =>  $billingAddress->getStreet(),
                    'locality' => $billingAddress->getCity(),
                    'postalCode' => $billingAddress->getPostcode(),
                    'administrativeArea' => $billingAddress->getRegionId(), // state
                    'country' => $countryName,
                    'countryCode' => $billingAddress->getCountryId()
                ];
            }

            $this->_adyenLogger->error("CUSSTOMER LOGIN4!");

            $shippingAddress = $this->_currentCustomerAddress->getDefaultShippingAddress();

            if ($shippingAddress) {
                $lastName = trim($shippingAddress->getMiddlename() . " " . $shippingAddress->getLastName());
                $countryName = $this->_localeLists->getCountryTranslation($shippingAddress->getCountryId());

                $shippingContract = [
                    'emailAddress' => $customer->getEmail(),
                    'phoneNumber' => $billingAddress->getTelephone(),
                    'familyName' => $lastName,
                    'givenName' => $billingAddress->getFirstname(),
                    'addressLines' =>  $billingAddress->getStreet(),
                    'locality' => $billingAddress->getCity(),
                    'postalCode' => $billingAddress->getPostcode(),
                    'administrativeArea' => $billingAddress->getRegionId(), // state
                    'country' => $countryName,
                    'countryCode' => $billingAddress->getCountryId()
                ];
            }

            $lastName = trim($customer->getMiddlename() . " " . $customer->getLastName());

            $this->_adyenLogger->error(print_r($billingContract, true));
            $this->_adyenLogger->error(print_r($shippingContract, true));


            return [
                'customerId' => $customerId,
                'firstname' => $customer->getFirstname(),
                'givenName' => $customer->getFirstname(),
                'familyName' => $lastName,
                'emailAddress' => $customer->getEmail(),
                'billingContact' => $billingContract,
                'shippingContact' => $shippingContract
            ];

        }

        return [];
    }

}