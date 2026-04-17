<?php
namespace Webkul\BlogManager\Model;

class Comment extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';
    
    /**
     * Entity Id
     */
    const ENTITY_ID = 'entity_id';
    
    /**
     * BlogManager Comment cache tag.
     */
    const CACHE_TAG = 'webkul_blogmanager_comment';
    
    /**
     * @var string
     */
    protected $_cacheTag = 'webkul_blogmanager_comment';

    /**
     * @var string
     */
    protected $_eventPrefix = 'webkul_blogmanager_comment';
    
    /**
     * Dependency Initilization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Webkul\BlogManager\Model\ResourceModel\Comment::class);
    }
    
    /**
     * Load object data.
     *
     * @param int $id
     * @param string|null $field
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRoute();
        }
        return parent::load($id, $field);
    }
    
    /**
     * No Route
     *
     * @return $this
     */
    public function noRoute()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Get Identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get Id
     *
     * @return int|null
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set Id
     *
     * @param int $id
     * @return \Webkul\BlogManager\Model\Blog
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}