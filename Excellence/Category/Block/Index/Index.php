<?php
declare(strict_types=1);

namespace Excellence\Category\Block\Index;

class Index extends \Magento\Framework\View\Element\Template
{
        /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    protected $_categoryFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collecionFactory,

        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryFactory = $collecionFactory;
    }

    public function getWebsites() {
        return $this->_storeManager->getWebsites();
    }
    public function getCurrentWebsiteId()
    {
        return $this->_storeManager->getWebsite()->getId();
    }
    public function getCategoryCollection() {
        $collection = $this->_categoryFactory->create();
        $collection->addAttributeToSelect('*');
        return $collection;
    }
    public function getCategoryAttribute($id)
    {
        $collection = $this->_categoryFactory
                        ->create()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('entity_id',['eq'=>$id])
                        ->setPageSize(1);
    
       return $collection->getFirstItem();
    
    }
}

