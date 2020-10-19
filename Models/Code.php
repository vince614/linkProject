<?php
namespace Model;

use Mobile_Detect;
use Model\Core\Database;

/**
 * Class Code
 * @package Model
 */
class Code extends Database
{

    /**
     * Code
     * @var string
     */
    private $_code;

    /**
     * @var Mobile_Detect
     */
    private $_mobileDetectModel;

    /**
     * Devices
     * @var int
     */
    private $_isMobile;
    private $_isTablet;
    private $_isDesktop;

    /**
     * Browsers
     * @var array
     */
    private $_browsers = ["Opera", "Edge", "Chrome", "Safari", "Firefox", "MSIE", "Trident"];

    /**
     * IP infos
     * @var string
     */
    private $_country;
    private $_countryCode;
    private $_state;
    private $_city;

    /**
     * Code constructor.
     * @param $code
     */
    public function __construct($code)
    {
        if (!$code) {
            return;
        }
        $this->_code = $code;
        parent::__construct();
        $this->_process();
    }

    /**
     * Init classes
     */
    private function _initClasses()
    {
        $this->_mobileDetectModel = new Mobile_Detect();
    }

    /**
     * Process
     *
     * @return bool
     */
    private function _process()
    {
        if ($link = $this->checkIfCodeExist($this->_code)) {
            $this->_initClasses();

            // Get device
            $this->_isMobile = $this->_mobileDetectModel->isMobile() && !$this->_mobileDetectModel->isTablet() ? 1 : 0;
            $this->_isTablet = $this->_mobileDetectModel->isTablet() && !$this->_mobileDetectModel->isMobile() ? 1 : 0;
            $this->_isDesktop = !$this->_mobileDetectModel->isTablet() && !$this->_mobileDetectModel->isMobile() ? 1 : 0;

            // Ip Infos
            $this->_country =  $this->getIpInfos("Visitor", "Country");
            $this->_countryCode =  $this->getIpInfos("Visitor", "Country Code");
            $this->_state =  $this->getIpInfos("Visitor", "State");
            $this->_city =  $this->getIpInfos("Visitor", "City");

            // User browser
            $userBrowser = $this->getUserBrowser();

            // Insert
            $req = Database::getPDO()->prepare('INSERT INTO clicks (code, owner_username, owner_email, isPhone, isTablet, isDesktop, browser, country, country_code, states, city, clicks_time) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $req->execute(array($this->_code, $link['owner_username'], $link['owner_email'], $this->_isMobile, $this->_isTablet, $this->_isDesktop, $userBrowser, $this->_country, $this->_countryCode, $this->_state, $this->_city, time()));

            // Redirect
            header('Location: ' . $link['links_origin']);
            return true;
        }
        header('Location: ./404');
        return false;
    }

    /**
     * Get IP informations
     *
     * @param null $ip
     * @param string $purpose
     * @param bool $deep_detect
     * @return array|string|null
     */
    private function getIpInfos($ip = null, $purpose = "location", $deep_detect = true)
    {
        $output = null;
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
        }
        $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), null, strtolower(trim($purpose)));
        $support = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city" => @$ipdat->geoplugin_city,
                            "state" => @$ipdat->geoplugin_regionName,
                            "country" => @$ipdat->geoplugin_countryName,
                            "country_code" => @$ipdat->geoplugin_countryCode,
                            "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1) {
                            $address[] = $ipdat->geoplugin_regionName;
                        }
                        if (@strlen($ipdat->geoplugin_city) >= 1) {
                            $address[] = $ipdat->geoplugin_city;
                        }
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "region":
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    /**
     * Get user browser
     *
     * @return mixed|string
     */
    private function getUserBrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $userBrowser = "";

        foreach ($this->_browsers as $browser) {
            if (strpos($agent, $browser) !== false) {
                $userBrowser = $browser;
                break;
            }
        }
        switch ($userBrowser) {
            case 'Trident':
            case 'MSIE':
                $userBrowser = 'Internet Explorer';
                break;

            case 'Edge':
                $userBrowser = 'Microsoft Edge';
                break;
        }
        return $userBrowser;
    }

    /**
     * Check if code exist
     *
     * @param $code
     * @return bool
     */
    private function checkIfCodeExist($code)
    {
        $req = Database::getPDO()->prepare('SELECT * FROM links_table WHERE code = ?');
        $req->execute(array($code));
        if ($req->rowCount() > 0) {
            return $req->fetch();
        }
        return false;
    }

}