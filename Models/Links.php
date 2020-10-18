<?php
namespace Model;
use Model\Core\Database;
use PDO;

/**
 * Class Links
 * @package Model
 */
class Links extends Database
{

    /**
     * Lenght of code
     */
    const CODE_LENGHT = 5;

    /**
     * Create new link
     *
     * @param $url
     * @param $title
     * @param $user
     * @return bool
     */
    public function createNewLink($url, $title, $user)
    {
        // Check if URL is valide
        if ($this->_verifUrl($url)) {
            // Check if is https URL
            $HTTPS = explode(':', $url)[0] == 'https' ? 1 : 0;
            // Generate code & check if his alreadey exist
            $codeExist = true;
            $code = "";
            while ($codeExist) {
                $code = $this->_generaterandomCode(self::CODE_LENGHT);
                $codeExist = $this->_codeExist($code);
            }

            $req = Database::getPDO()->prepare("INSERT INTO links_table (links_origin, owner_username, owner_email, title, isHTTPS, code, date_link) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $req->execute(array($url, $user['username'], $user['email'], $title, $HTTPS, $code, time()));

            if ($req->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Verif URL
     *
     * @param $link
     * @return bool
     */
    private function _verifUrl($link)
    {
        return filter_var($link, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Generate code
     *
     * @param $lenght
     * @return string
     */
    private function _generaterandomCode($lenght)
    {
        // Caracts of code
        $characts = 'abcdefghijklmnopqrstuvwxyz';
        $characts .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characts .= '1234567890';
        $code = '';

        // Generate code
        for($i=0; $i < $lenght; $i++) {
            $code .= substr($characts, rand() % (strlen($characts)),1);
        }

        return $code;
    }

    /**
     * Check is code exist
     *
     * @param $code
     * @return bool
     */
    private function _codeExist($code)
    {
        // First try
        if ($code === false) {
            return false;
        }

        $req = Database::getPDO()->prepare("SELECT * FROM links_table WHERE code = ?");
        $req->execute(array($code));
        if ($req->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Get links with email
     *
     * @param $email
     * @return array
     */
    public function getLinks($email)
    {
        $array = [];
        $req = Database::getPDO()->prepare('SELECT * FROM links_table WHERE owner_email = ? ORDER BY id DESC');
        $req->execute(array($email));
        foreach ($req->fetchAll() as $link) {
            $array[] = [
                'link' => $link,
                'clickCount' => $this->getClicksCountFromCode($link['code'])
            ];
        }
        return $array;
    }

    /**
     * Get click count
     * For this & last week
     *
     * @param $email
     * @return array
     */
    public function getClicksCount($email)
    {
        $time = time();
        $thisWeek = $time - 7 * 24 * 60 * 60;
        $lastWeek = $time - 2 * 7 * 24 * 60 * 60;

        /** @var PDO $pdo */
        $pdo = Database::getPDO();

        // This week
        $req = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
        $req->execute(array($email, $thisWeek, $time));
        $thisWeekClicksCount = $req->rowCount();

        // Last week
        $req = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
        $req->execute(array($email, $lastWeek, $thisWeek));
        $lastWeekClicksCount = $req->rowCount();

        // Best link
        $req = $pdo->prepare("SELECT code, COUNT(DISTINCT id) AS clickCount FROM clicks WHERE owner_email = ? GROUP BY code");
        $req->execute(array($email));
        $links = $req->fetchAll();

        // Get best link
        $bestLink = $this->_getBestLink($links);

        // Get diff Percent
        $percent = $this->_getDiffPourcent($thisWeekClicksCount, $lastWeekClicksCount);

        return [
            'thisWeek' => $thisWeekClicksCount,
            'lastWeek' => $lastWeekClicksCount,
            'bestLink' => $bestLink,
            'percent' => $percent
        ];
    }

    /**
     * Get best link
     *
     * @param $links
     * @return array
     */
    private function _getBestLink($links)
    {
        if (count($links) > 0) {
            $array = [];
            foreach ($links as $link) {
                $array[$link['code']] = $link['clickCount'];
            }
            arsort($array);
            $keyOfMax = key($array);
            $sum = max($array) * 100;
            $count = round($sum / array_sum($array));

            $result = [
                'count' =>  $count,
                'code' => $keyOfMax
            ];

        } else {
            $result = [
                'count' =>  "0",
                'code' => "No links"
            ];
        }
        return $result;
    }

    /**
     * Get click count form code
     *
     * @param $code
     * @return int
     */
    private function getClicksCountFromCode($code)
    {
        $req = Database::getPDO()->prepare("SELECT * FROM links_table WHERE code = ?");
        $req->execute(array($code));
        return $req->rowCount();
    }

    /**
     * Get percent diff from 2 values
     *
     * @param $first
     * @param $second
     * @return false|float
     */
    private function _getDiffPourcent($first, $second)
    {
        $diff = $first - $second;
        $result = $diff * 100;
        return round($result / ($second + 1));
    }
}