<?php
/**
 * EasyMage_PriceFilter
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so you can be sent a copy immediately.
 *
 * Original code copyright (c) 2006-2016 X.commerce, Inc. (http://www.magento.com)
 *
 * @package    EasyMage_PriceFilter
 * @author     Konstantin Dubovik
 * @contact    kodubovik@gmail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class EasyMage_PriceFilter_Model_System_Config_Backend_Priceranges extends Mage_Core_Model_Config_Data
{
    /**
     * Validate and save price ranges config
     *
     * @return Mage_Core_Model_Abstract
     * @throws Exception
     * @throws Mage_Core_Exception
     */
    public function save()
    {
        $pattern = '#^(\d+)?\-\d+;(\d+\-\d+;)*\d+\-(\d+)?$#';
        $value = $this->getValue();
        if (('' !== $value) && !preg_match($pattern, $value)) {
            $message = Mage::helper('easymage_pricefilter')->__("Provided Layered Navigation Price Ranges are incorrect.");
            Mage::throwException($message);
        }
        return parent::save();
    }
}
