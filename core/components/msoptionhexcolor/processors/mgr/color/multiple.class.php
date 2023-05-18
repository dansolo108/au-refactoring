<?php

class msHexColorMultipleProcessor extends modProcessor
{


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);
        if (empty($ids)) {
            return $this->success();
        }

        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('mgr/color/' . $method, array('id' => $id),
                            array('processors_path' => MODX_CORE_PATH . 'components/msoptionhexcolor/processors/'));
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }

}

return 'msHexColorMultipleProcessor';