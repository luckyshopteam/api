<?php
/**
 * @link luckyshop.ru
 * @copyright Copyright LuckyShop 2018
 * @version 1.0.0
 */

namespace luckyshopteam\api;

/**
 * Used for data validation.
 *
 * @author LuckyShop
 * @since 1.0
 * @see https://luckyshop.ru/webmaster/help/faq.html
 */
class Lead implements LeadInterface
{
    /**
     * @var string Array list of attributes in key-value pairs format.
     * @note array must contain 'name', 'phone', 'ip', 'userAgent', 'campaignHash'
     */
    public $attributes = [];
    /**
     * @var array Array list of errors in key-value pairs format where the key is parameter name and value is the error message.
     */
    public $errors;
    /**
     * @var array Array of phone masks for using in validation.
     */
    protected $phoneMasks = [
        'BY' => '/(38)0\d{9}/',
        'RU' => '/[78][3489]\d{9}/',
        'KZ' => '/[78]7\d{9}/',
        'GE' => '/995\d{8,10}/',
        'AM' => '/374\d{8,9}/',
        'KG' => '/996\d{9,10}/',
    ];

    /**
     * Initializes the object with the given configuration `$attributes`.
     * @param array $attributes Array list in a key-value pairs format.
     */
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Validates all model attributes.
     * @return boolean
     */
    public function validate()
    {
        $this->validateName();
        $this->validatePhone();
        $this->validateIp();
        $this->validateUserAgent();
        $this->validateCampaignHash();

        return empty($this->errors);
    }

    /**
     * Validates buyer name.
     * @return boolean true if validation is success.
     */
    public function validateName()
    {
        if ($this->isEmpty('name')) return false;

        if (strlen($this->attributes['name']) < 2) {
            $this->errors['name'] = 'Name must contain more then 2 letters.';
            return false;
        }
        return true;
    }
    /**
     * Validates buyer phone number.
     * @return boolean true if validation is success.
     */
    public function validatePhone()
    {
        if ($this->isEmpty('phone')) return false;

        $phone = $this->attributes['phone'];
        foreach ($this->phoneMasks as $phoneMask) {
            if (preg_match($phoneMask, $phone)) {
                return true;
            }
        }

        $this->errors['phone'] = 'Phone number does not match any mask.';
        return false;
    }

    /**
     * Validates buyer IP address.
     * @return boolean true if validation is success.
     */
    public function validateIp()
    {
        if ($this->isEmpty('ip')) return false;

        if (filter_var($this->attributes['ip'], FILTER_VALIDATE_IP)) {
            return true;
        }

        $this->errors['ip'] = 'Invalid IP address.';
        return false;
    }

    /**
     * Validates user userAgent
     * @return boolean true if validation is success.
     */
    public function validateUserAgent()
    {
        if ($this->isEmpty('userAgent')) return false;

        return true;
    }

    /**
     * Validates campaignHash
     * @return boolean true if validation is success.
     */
    public function validateCampaignHash()
    {
        if ($this->isEmpty('campaignHash')) return false;

        return true;
    }

    /**
     * Checks whether attribute is empty and adds error message.
     * @param string $attributeName
     * @return boolean true if empty.
     */
    protected function isEmpty($attributeName)
    {
        if (!isset($this->attributes[$attributeName])) {
            $this->errors[$attributeName] = 'Attribute ' . $attributeName . ' is empty.';
            return true;
        }

        return false;
    }
}