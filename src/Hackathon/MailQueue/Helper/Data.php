<?php
class Hackathon_MailQueue_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_logfilename = 'Hackathon_MailQueue.log';

    public function isEnabled()
    {
        return Mage::getStoreConfig('system/hmq/enabled');
    }

    public function isDebug()
    {
        return Mage::getStoreConfig('system/hmq/debug');
    }

    public function getRetryTimeout()
    {
        return Mage::getStoreConfig('system/hmq/retry_timeout');
    }

    public function log() {
        $args = func_get_args();
        $formattedMsg = call_user_func_array('sprintf', $args);
        Mage::log($formattedMsg, null, $this->_logfilename, $this->isDebug());
    }
}