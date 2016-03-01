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
class EasyMage_PriceFilter_Model_Catalog_Layer_Filter_Price
extends Mage_Catalog_Model_Layer_Filter_Price
{
    /**
     *  Path to price filter enable config
     */
    const XML_PRICE_CUSTOM_ENABLED = 'pricefilter_options/general/enable';
    /**
     *  Path to price ranges config
     */
    const XML_PRICE_CUSTOM_RANGES = 'pricefilter_options/general/price_ranges';
    /**
     *  Path to price subtraction config
     */
    const XML_PRICE_CUSTOM_SUBTRACTION = 'pricefilter_options/general/price_subtraction';

    /**
     * Get data for building price filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        if (!Mage::app()->getStore()->getConfig(self::XML_PRICE_CUSTOM_ENABLED)) {
            return parent::_getItemsData();
        } else {
            $data = array();
            $ranges = $this->buildPriceRanges();
            $count_array   = $this->_getResource()->getCount($this, $ranges);;
            foreach ($ranges as $index => $range) {
                if($count_array[$index] > 0) {
                    $data[] = array(
                        'label' => $this->_renderRangeLabel($range[0], $range[1]),
                        'value' => $range[0] . '-' . $range[1],
                        'count' => $count_array[$index],
                    );
                }
            }
            return $data;
        }
    }

    /**
     * Prepare text of range labels
     *
     * @param float|string $fromPrice
     * @param float|string $toPrice
     * @return string
     */
    protected function _renderRangeLabel($fromPrice, $toPrice)
    {
        if (!Mage::app()->getStore()->getConfig(self::XML_PRICE_CUSTOM_ENABLED)) {
            return parent::_renderRangeLabel($fromPrice, $toPrice);
        }
        else {
            $store = Mage::app()->getStore();
            $formattedFromPrice  = $store->formatPrice($fromPrice);
            if ($toPrice === '') {
                return Mage::helper('catalog')->__('%s and above', $formattedFromPrice);
            } elseif ($fromPrice == $toPrice && Mage::app()->getStore()->getConfig(self::XML_PATH_ONE_PRICE_INTERVAL)) {
                return $formattedFromPrice;
            } else {
                if ($fromPrice != $toPrice && Mage::app()->getStore()->getConfig(self::XML_PRICE_CUSTOM_SUBTRACTION)) {
                    $toPrice -= .01;
                }
                return Mage::helper('catalog')->__('%s - %s', $formattedFromPrice, $store->formatPrice($toPrice));
            }
        }
    }

    /**
     * Build price ranges array from config
     *
     * @return array
     */
    public function buildPriceRanges() {
        $result = array();
        $rawData = Mage::app()->getStore()->getConfig(self::XML_PRICE_CUSTOM_RANGES);
        $rawData = explode(';', $rawData);
        foreach ($rawData as $tmp_item) {
            $result[] = explode('-', $tmp_item);
        }
        return $result;
    }
}
