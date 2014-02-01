<?php
class Hackathon_MailQueue_Model_EmailTemplate extends Mage_Core_Model_Email_Template
{
    public function getMail()
    {
        $helper = Mage::helper('hmq');
        if (! $helper->isEnabled()) {
            return parent::getMail();
        }

        if (is_null($this->_mail)) {
            $this->_mail = new Hackathon_MailQueue_Model_Mail('utf-8');
        }
        return $this->_mail;
    }

}