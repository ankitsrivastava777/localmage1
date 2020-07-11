<?php

namespace Excellence\Category\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class CheckLoginPersistentObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;
    protected $customerRepositoryInterface;
    protected $_urlInterface;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $http;

    /** @var \Magento\Customer\Model\Session */
    protected $customerSession;
    protected $_storeManager;

    /**
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\Response\Http $http
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\Http $http,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\UrlInterface $urlInterface

    ) {
        $this->url = $url;
        $this->http = $http;
        $this->customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * Manages redirect
     */
    public function execute(Observer $observer)
    {
        $customerId = $this->customerSession->getId();
        if ($customerId) {
            $customer = $this->customerRepositoryInterface->getById($customerId);
            $customerAttr = $customer->getCustomAttribute('accesswebsite')->getValue();
            $currentUrl = $this->_urlInterface->getCurrentUrl() . "/";
            /**
             * Check if user logged in
             */
            if ($this->customerSession->isLoggedIn() && $customerAttr == "0") {

                if ($currentUrl != $this->url->getUrl('access-denied')) {
                    $this->http->setRedirect($this->url->getUrl('access-denied'), 301);
                }
            }
        }
        if (!$customerId) {
            $currentUrl = $this->_urlInterface->getCurrentUrl();
            /**
             * Check if user logged in
             */

            if (!$this->customerSession->isLoggedIn()) {

                if ($currentUrl != $this->url->getUrl('customer/account/login')) {
                    $this->http->setRedirect($this->url->getUrl('customer/account/login'), 301);
                }
            }
        }
    }
}
