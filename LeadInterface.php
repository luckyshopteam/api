<?php
/**
 * @link luckyshop.ru
 * @copyright Copyright LuckyShop 2018
 * @version 1.0.0
 */

namespace luckyshopteam\api;


/**
 * Use this interface in your own Lead class.
 *
 * @author LuckyShop
 * @since 1.0
 * @see https://luckyshop.ru/webmaster/help/faq.html
 */
interface LeadInterface
{
    /**
     * Whether is model validate.
     * @return boolean
     */
    public function validate();
}