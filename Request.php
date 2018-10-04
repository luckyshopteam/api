<?php
/**
 * @link luckyshop.ru
 * @copyright Copyright LuckyShop 2018
 * @version 1.0.0
 */

namespace luckyshopteam\api;

use Exception;
use app\luckyshop\Lead;

/**
 * Class for working with LuckyShop API
 *
 * @author LuckyShop
 * @since 1.0
 * @see https://luckyshop.ru/webmaster/help/faq.html
 */
class Request
{
    /**
     * @var string Personal API key.
     * @see https://luckyshop.ru/webmaster/profile.html
     */
    public $apiKey;
    /**
     * @var array Array list of utm marks and subid in key-value pairs format.
     */
    public $requestParams;
    /**
     * @var mixed Result of the last request.
     */
    public $response;
    /**
     * @var boolean Whether to decode response.
     */
    private $decodeResponse = true;
    /**
     * @var string Url used to send lead.
     */
    private $leadUrl = 'https://luckyshop.ru/api-webmaster/lead.html';
    /**
     * @var string Url used to get lead statuses.
     */
    private $statusUrl = 'https://luckyshop.ru/api-webmaster/lead-status-batch.html';


    /**
     * Initializes the object with the given configuration `$params`.
     * @param array $params Array list in a key-value pairs where key is an attribute name.
     * @throws Exception If properties "apiKey" is not set.
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $name => $value) {
                $this->$name = $value;
            }
        }
        if (!$this->apiKey) {
            throw new Exception('Attribute "apiKey" is required.');
        }
    }

    /**
     * Sends lead.
     * @param array|Lead $lead Array list of required lead params or instance of class [[Lead]].
     * @return mixed Returns response.
     */
    public function sendLead($lead, $params = [])
    {
        if (is_object($lead)) {
            $lead = $lead->attributes;
        }

        return $this->request($this->leadUrl, $params, $lead);
    }

    /**
     * Returns click statuses.
     * @param string|array $ids Click ids separated by comma or array list.
     * @return mixed Returns response.
     */
    public function getStatuses($ids)
    {
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }

        $params['click_id'] = $ids;

        return $this->request($this->statusUrl, $params);
    }

    /**
     * Creates request.
     * @param string $url Url for request.
     * @param array $params Url params. Use this parameter for sending utm marks and subid.
     * @param array $data Data for sending in POST request. Use this parameter for sending buyer information e.g name, phone etc.
     * @param string $method Request method.
     *
     * @return mixed Returns response.
     */
    public function request($url, $params = [], $data = [], $method = 'POST')
    {
        $url .= '?api_key=' . $this->apiKey;
        $requestParams = $params ? $params : $this->requestParams;

        if ($requestParams) {
            $url .= '&' . http_build_query($requestParams);
        }

        $curl = curl_init($url);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $this->response = $this->decodeResponse && $response ? json_decode($response) : $response;

        return $this->response;
    }

}