<?php

namespace Model;

use Model\Core\Database;
use PDO;

/**
 * Class Charts
 * @package Model
 */
class Charts extends Database
{


    /**
     * Timers
     * @var int
     */
    private $_timeHour;
    private $_timeDay;
    private $_timeWeek;
    private $_timeMonth;
    private $_timerYear;

    /**
     * Charts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $time = time();
        $this->_timeHour = $time - 60 * 60;
        $this->_timeDay = $time - 24 * 60 * 60;
        $this->_timeWeek = $time - 7 * 24 * 60 * 60;
        $this->_timeMonth = $time - 30 * 24 * 60 * 60;
        $this->_timerYear = $time - 12 * 30 * 24 * 60 * 60;
    }


    /**
     * Get click chart area
     *
     * @param $email
     * @param $code
     * @param $date
     * @return array
     */
    public function getClickChartArea($email, $code, $date)
    {
        /** @var PDO $pdo */
        $pdo = Database::getPDO();
        $time = time();
        $result = [];

        // Push date in result
        $result['date'] = $date;

        if ($date == 'day') {
            $arrayHours = [];
            $arrayTime = [];

            for ($i = 23; $i >= 0; $i--) {

                // Time before and after
                $time_hours_before = $time - 60 * 60 * ($i + 1);
                $time_hours_after = $time - 60 * 60 * $i;

                // All links
                if ($code == 'all') {
                    //Requête all day of week
                    $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
                    $req->execute(array($email, $time_hours_before, $time_hours_after));
                } else {
                    //Requête all day of week of one code
                    $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                    $req->execute(array($email, $code, $time_hours_before, $time_hours_after));
                }
                $req_day_count = $req->rowCount();

                array_push($arrayHours, $req_day_count);
                array_push($arrayTime, $time_hours_before);
            }

            // Valeurs per hours
            $result['hours'] = $arrayHours;
            $result['time'] = $arrayTime;
            // Req true
            $result['req'] = true;
            $result['code'] = $code;

        } else {
            if ($date == 'week') {
                $arrayDays = [];
                $arrayTime = [];

                for ($i = 6; $i >= 0; $i--) {

                    //Time before and after
                    $time_Days_before = $time - 24 * 60 * 60 * ($i + 1);
                    $time_Days_after = $time - 24 * 60 * 60 * $i;

                    // All links
                    if ($code == 'all') {
                        // Requet all day of week
                        $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
                        $req->execute(array($email, $time_Days_before, $time_Days_after));
                    } else {
                        /// Requet all day of week of one code
                        $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                        $req->execute(array($email, $code, $time_Days_before, $time_Days_after));
                    }
                    $req_week_count = $req->rowCount();

                    //Mettre dans le tableau
                    array_push($arrayDays, $req_week_count);
                    array_push($arrayTime, $time_Days_before);
                }

                //Valeurs per hours
                $result['days'] = $arrayDays;
                //Time
                $result['time'] = $arrayTime;

                //Req true
                $result['req'] = true;
                $result['code'] = $code;

            } else {
                if ($date == 'month') {
                    $arrayweek = [];
                    $arrayTime = [];

                    for ($i = 29; $i >= 0; $i--) {

                        //Time before and after
                        $time_week_before = $time - 24 * 60 * 60 * ($i + 1);
                        $time_week_after = $time - 24 * 60 * 60 * $i;

                        // All links
                        if ($code == 'all') {
                            // Requet all day of month
                            $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
                            $req->execute(array($email, $time_week_before, $time_week_after));
                        } else {
                            // Request all day of month of one code
                            $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                            $req->execute(array($email, $code, $time_week_before, $time_week_after));
                        }
                        $req_week_count = $req->rowCount();

                        // Push in array
                        array_push($arrayweek, $req_week_count);
                        array_push($arrayTime, $time_week_before);
                    }

                    // Values per hours
                    $result['month'] = $arrayweek;
                    // Time
                    $result['time'] = $arrayTime;

                    // Req true
                    $result['req'] = true;
                    $result['code'] = $code;

                } else {
                    if ($date == 'year') {
                        $arraymonth = [];
                        $arrayTime = [];

                        for ($i = 11; $i >= 0; $i--) {

                            // Time before and after
                            $time_month_before = $time - 30 * 24 * 60 * 60 * ($i + 1);
                            $time_month_after = $time - 30 * 24 * 60 * 60 * $i;

                            // All links
                            if ($code == 'all') {
                                // Request all day of month
                                $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND clicks_time BETWEEN ? AND ?');
                                $req->execute(array($email, $time_month_before, $time_month_after));
                            } else {
                                // Request all day of month of one code
                                $req = $pdo->prepare('SELECT id FROM clicks WHERE owner_email = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                                $req->execute(array($email, $code, $time_month_before, $time_month_after));
                            }
                            $req_month_count = $req->rowCount();

                            // Push array
                            array_push($arraymonth, $req_month_count);
                            array_push($arrayTime, $time_month_before);
                        }

                        // Values per hours
                        $result['year'] = $arraymonth;
                        //Time
                        $result['time'] = $arrayTime;

                        // Req true
                        $result['req'] = true;
                        $result['code'] = $code;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param $email
     * @param $code
     * @param $date
     */
    public function getDeviceClicks($email, $code, $date)
    {
        $pdo = Database::getPDO();
        $time = time();
        $arrayPie = [];
        $result = [];

        // Push date in result array
        $result['date'] = $date;

        if ($date == 'day') {
            if ($code == 'all') {

                // Is phone
                $req_day_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_phone->execute(array($email, 1, $this->_timeDay, $time));
                $req_day_phone_count = $req_day_phone->rowCount();
                array_push($arrayPie, $req_day_phone_count);

                //Is phone
                $req_day_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_tablet->execute(array($email, 1, $this->_timeDay, $time));
                $req_day_tablet_count = $req_day_tablet->rowCount();
                array_push($arrayPie, $req_day_tablet_count);

                //Is phone
                $req_day_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_desktop->execute(array($email, 1, $this->_timeDay, $time));
                $req_day_desktop_count = $req_day_desktop->rowCount();
                array_push($arrayPie, $req_day_desktop_count);

                //Req true
                $result['req'] = true;
            } else {

                //Is phone
                $req_day_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                $req_day_phone->execute(array($email, 1, $code, $this->_timeDay, $time));
                $req_day_phone_count = $req_day_phone->rowCount();
                array_push($arrayPie, $req_day_phone_count);

                //Is phone
                $req_day_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_tablet->execute(array($email, 1, $code, $this->_timeDay, $time));
                $req_day_tablet_count = $req_day_tablet->rowCount();
                array_push($arrayPie, $req_day_tablet_count);

                //Is phone
                $req_day_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                $req_day_desktop->execute(array($email, 1, $code, $this->_timeDay, $time));
                $req_day_desktop_count = $req_day_desktop->rowCount();
                array_push($arrayPie, $req_day_desktop_count);

                //Req true
                $result['req'] = true;
            }
            // Time
            $result['time'] = $this->_timeDay;

        } else {
            if ($date == 'week') {
                if ($code == 'all') {

                    //Is phone
                    $req_week_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                    $req_week_phone->execute(array($email, 1, $this->_timeWeek, $time));
                    $req_week_phone_count = $req_week_phone->rowCount();
                    array_push($arrayPie, $req_week_phone_count);

                    //Is phone
                    $req_week_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                    $req_week_tablet->execute(array($email, 1, $this->_timeWeek, $time));
                    $req_week_tablet_count = $req_week_tablet->rowCount();
                    array_push($arrayPie, $req_week_tablet_count);

                    //Is phone
                    $req_week_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                    $req_week_desktop->execute(array($email, 1, $this->_timeWeek, $time));
                    $req_week_desktop_count = $req_week_desktop->rowCount();
                    array_push($arrayPie, $req_week_desktop_count);

                    //Req true
                    $result['req'] = true;
                } else {

                    //Is phone
                    $req_week_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                    $req_week_phone->execute(array($email, 1, $code, $this->_timeWeek, $time));
                    $req_week_phone_count = $req_week_phone->rowCount();
                    array_push($arrayPie, $req_week_phone_count);

                    //Is phone
                    $req_week_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                    $req_week_tablet->execute(array($email, 1, $code, $this->_timeWeek, $time));
                    $req_week_tablet_count = $req_week_tablet->rowCount();
                    array_push($arrayPie, $req_week_tablet_count);

                    //Is phone
                    $req_week_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                    $req_week_desktop->execute(array($email, 1, $code, $this->_timeWeek, $time));
                    $req_week_desktop_count = $req_week_desktop->rowCount();
                    array_push($arrayPie, $req_week_desktop_count);

                    //Req true
                    $result['req'] = true;
                }
                // Time
                $result['time'] = $this->_timeWeek;

            } else {
                if ($date == 'month') {
                    if ($code == 'all') {

                        //Is phone
                        $req_month_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                        $req_month_phone->execute(array($email, 1, $this->_timeMonth, $time));
                        $req_month_phone_count = $req_month_phone->rowCount();
                        array_push($arrayPie, $req_month_phone_count);

                        //Is phone
                        $req_month_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                        $req_month_tablet->execute(array($email, 1, $this->_timeMonth, $time));
                        $req_month_tablet_count = $req_month_tablet->rowCount();
                        array_push($arrayPie, $req_month_tablet_count);

                        //Is phone
                        $req_month_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                        $req_month_desktop->execute(array($email, 1, $this->_timeMonth, $time));
                        $req_month_desktop_count = $req_month_desktop->rowCount();
                        array_push($arrayPie, $req_month_desktop_count);

                        //Req true
                        $result['req'] = true;
                    } else {

                        //Is phone
                        $req_month_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                        $req_month_phone->execute(array($email, 1, $code, $this->_timeMonth, $time));
                        $req_month_phone_count = $req_month_phone->rowCount();
                        array_push($arrayPie, $req_month_phone_count);

                        //Is phone
                        $req_month_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                        $req_month_tablet->execute(array($email, 1, $code, $this->_timeMonth, $time));
                        $req_month_tablet_count = $req_month_tablet->rowCount();
                        array_push($arrayPie, $req_month_tablet_count);

                        //Is phone
                        $req_month_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                        $req_month_desktop->execute(array($email, 1, $code, $this->_timeMonth, $time));
                        $req_month_desktop_count = $req_month_desktop->rowCount();
                        array_push($arrayPie, $req_month_desktop_count);

                        //Req true
                        $result['req'] = true;
                    }

                    // Time
                    $result['time'] = $this->_timeMonth;

                } else {
                    if ($date == 'year') {
                        if ($code == 'all') {

                            //Is phone
                            $req_year_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND clicks_time BETWEEN ? AND ?');
                            $req_year_phone->execute(array($email, 1, $this->_timerYear, $time));
                            $req_year_phone_count = $req_year_phone->rowCount();
                            array_push($arrayPie, $req_year_phone_count);

                            //Is phone
                            $req_year_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND clicks_time BETWEEN ? AND ?');
                            $req_year_tablet->execute(array($email, 1, $this->_timerYear, $time));
                            $req_year_tablet_count = $req_year_tablet->rowCount();
                            array_push($arrayPie, $req_year_tablet_count);

                            //Is phone
                            $req_year_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND clicks_time BETWEEN ? AND ?');
                            $req_year_desktop->execute(array($email, 1, $this->_timerYear, $time));
                            $req_year_desktop_count = $req_year_desktop->rowCount();
                            array_push($arrayPie, $req_year_desktop_count);

                            //Req true
                            $result['req'] = true;

                        } else {

                            //Is phone
                            $req_year_phone = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isPhone = ? AND code = ? clicks_time BETWEEN ? AND ?');
                            $req_year_phone->execute(array($email, 1, $code, $this->_timerYear, $time));
                            $req_year_phone_count = $req_year_phone->rowCount();
                            array_push($arrayPie, $req_year_phone_count);

                            //Is phone
                            $req_year_tablet = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isTablet = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                            $req_year_tablet->execute(array($email, 1, $code, $this->_timerYear, $time));
                            $req_year_tablet_count = $req_year_tablet->rowCount();
                            array_push($arrayPie, $req_year_tablet_count);

                            //Is phone
                            $req_year_desktop = $pdo->prepare('SELECT * FROM clicks WHERE owner_email = ? AND isDesktop = ? AND code = ? AND clicks_time BETWEEN ? AND ?');
                            $req_year_desktop->execute(array($email, 1, $code, $this->_timerYear, $time));
                            $req_year_desktop_count = $req_year_desktop->rowCount();
                            array_push($arrayPie, $req_year_desktop_count);

                            //Req true
                            $result['req'] = true;

                        }

                        // Time
                        $result['time'] = $this->_timerYear;
                    }
                }
            }
        }

        // Push in result array
        $result['pie'] = $arrayPie;
        $result['timeNow'] = $time;

        return $result;
    }

    /**
     * Get location click
     *
     * @param $email
     * @param $code
     * @return array
     */
    public function getLocationClick($email, $code)
    {
        /** @var PDO $pdo */
        $pdo = Database::getPDO();
        $result = [];

        if ($code == "all") {
            $req = $pdo->prepare('SELECT country_code, COUNT(DISTINCT id) AS nb FROM clicks WHERE owner_email = ? GROUP BY country_code');
            $req->execute(array($email));

            // Fetch
            foreach ($req->fetchAll() as $country) {
                if ($country['country_code'] == null) {
                    continue;
                }
                $result[$country['country_code']] = $country['nb'];
            }


        } else {
            $req = $pdo->prepare('SELECT country_code, COUNT(DISTINCT id) AS nb FROM clicks WHERE code = ? AND owner_email = ? GROUP BY country_code');
            $req->execute(array($code, $email));

            // Fetch
            foreach ($req->fetchAll() as $country) {
                if ($country['country_code'] == null) {
                    continue;
                }
                $result[$country['country_code']] = $country['nb'];
            }
        }
        return $result;
    }


    /**
     * Get browser click
     *
     * @param $email
     * @param $code
     * @return array
     */
    public function getBrowserClick($email, $code)
    {
        /** @var PDO $pdo */
        $pdo = Database::getPDO();
        $browser = [];

        if ($code == "all") {
            $req = $pdo->prepare('SELECT browser, COUNT(DISTINCT id) AS nb FROM clicks WHERE owner_email = ? GROUP BY browser');
            $req->execute(array($email));

            // Fetch
            foreach ($req->fetchAll() as $click) {
                if ($click['browser'] == null) {
                    continue;
                }
                $browser[$click['browser']] = $click['nb'];
            }

        } else {

            $req = $pdo->prepare('SELECT browser, COUNT(DISTINCT id) AS nb FROM clicks WHERE code = ? AND owner_email = ? GROUP BY browser');
            $req->execute(array($code, $email));

            // Fetch
            foreach ($req->fetchAll() as $click) {
                if ($click['browser'] == null) {
                    continue;
                }
                $browser[$click['browser']] = $click['nb'];
            }
        }
        return $browser;
    }

    /**
     * Check if code exist
     *
     * @param $code
     * @return bool
     */
    public function checkIfCodeExist($code)
    {
        $req = Database::getPDO()->prepare('SELECT * FROM links_table WHERE code = ?');
        $req->execute(array($code));
        if ($req->rowCount() > 0) {
            return true;
        }
        return false;
    }

}