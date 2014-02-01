<?php
class Hackathon_MailQueue_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnabled()
    {
        return Mage::getStoreConfig('system/hmq/enabled');
    }
}