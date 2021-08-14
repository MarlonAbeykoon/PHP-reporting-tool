<?php

class MysqlSource extends ReportSourceBase {

    public static string $default_driver = 'mysql';

    public static function init(&$report) {
        $environments = Config::get()['environments'];

        if(!isset($environments[$report->options['Environment']][$report->options['Database']])) {
            throw new Exception("No ".$report->options['Database']." info defined for environment '".$report->options['Environment']."'");
        }
    }

    public static function openConnection(&$report) {
        if(isset($report->conn)) return;

        $environments = Config::get()['environments'];
        $config = $environments[$report->options['Environment']][$report->options['Database']];

        if(isset($config['dsn'])) {
            $dsn = $config['dsn'];
        }
        else {
            $host = $config['host'];
            if(isset($report->options['access']) && $report->options['access']==='rw') {
                if(isset($config['host_rw'])) $host = $config['host_rw'];
            }

            $driver = $config['driver'] ?? static::$default_driver;

            if(!$driver) {
                throw new Exception("Must specify database `driver` (e.g. 'mysql')");
            }

            $dsn = $driver.':host='.$host;

            if(isset($config['database'])) {
                $dsn .= ';dbname='.$config['database'];
            }
        }

        //the default is to use a user with read only privileges
        $username = $config['user'];
        $password = $config['pass'];

        $report->conn = new PDO($dsn,$username,$password);

        $report->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function closeConnection(&$report) {
        if(!isset($report->conn)) return;
        $report->conn = null;
        unset($report->conn);
    }

    public static function run(&$report): void
    {
        self::init($report);
        self::openConnection($report);

        $sql = $report->raw_query;

        $report->options['Query'] = $sql;

        $query = trim($sql);

        //skip empty queries
        if(!$query){
            return;
        }

        $result = $report->conn->query($query);

        $dataset = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dataset[] = $row;
        }

        self::closeConnection($report);
        $report->options['DataSet'] = $dataset;
    }
}
