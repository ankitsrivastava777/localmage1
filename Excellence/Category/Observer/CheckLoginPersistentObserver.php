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
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
        $this->url = $url;
        $this->http = $http;
        $this->customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    /**
     * Manages redirect
     */
    public function execute(Observer $observer)
    {
        $currentWebsiteId = $this->_storeManager->getStore()->getWebsiteId();

        if ($currentWebsiteId != "1") {
            /**
             * Check if user logged in
             */
            if ($this->customerSession->isLoggedIn()) {
                return;
            }
            if ($observer->getRequest()->getFullActionName() == 'cms_index_index') {
                /**
                 * Redirect to login
                 */
                $this->http->setRedirect($this->url->getUrl('customer/account/login'), 301);
            }
        }
    }
}
