<?php
class PolylangPolylangFieldCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'PolylangField';
    public $languageTopics = array('polylang:default');
    /** @var Polylang $polylang  */
    public $polylang ;

    public function initialize()
    {
        $this->polylang = $this->modx->getService('polylang', 'Polylang');
        return parent::initialize();
    }


    public function afterSave()
    {
        $cacheKey = $this->polylang->getTools()->getCacheKey($this->object->getCacheKey());
        $this->modx->cacheManager->delete($cacheKey);
        $this->modx->cacheManager->refresh(array('resource' => array()));
        return parent::afterSave();
    }
}
return 'PolylangPolylangFieldCreateProcessor';