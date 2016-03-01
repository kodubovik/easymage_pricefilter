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
class EasyMage_PriceFilter_Model_Catalog_Resource_Layer_Filter_Price
extends Mage_Catalog_Model_Resource_Layer_Filter_Price
{
    /**
     *  Path to price filter enable config
     */
    const XML_PRICE_CUSTOM_ENABLED = 'pricefilter_options/general/enable';

    /**
     * Retrieve array with products counts per price range
     *
     * @param Mage_Catalog_Model_Layer_Filter_Price $filter
     * @param int $ranges
     * @return array
     */
    public function getCount($filter, $ranges)
    {
        if (!Mage::app()->getStore()->getConfig(self::XML_PRICE_CUSTOM_ENABLED)) {
            return parent::getCount($filter, $ranges);
        }
        else {
            $result = array();
            foreach ($ranges as $range) {
                $select = $this->_getSelect($filter);
                if ($range[0]) $select->where($this->_getPriceExpression($filter, $select) . " > {$range[0]}");
                if ($range[1]) $select->where($this->_getPriceExpression($filter, $select) . " <= {$range[1]}");
                $countExpr = new Zend_Db_Expr('COUNT(*)');
                $select->columns(array(
                    'count' => $countExpr
                ));
                $query_result = $this->_getReadAdapter()->fetchCol($select);
                $result[] = $query_result[0];
            }
            return $result;
        }
    }
}
