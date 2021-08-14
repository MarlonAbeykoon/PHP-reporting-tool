<?php

class Report {

    public $report;
    public $options = array();
    public $raw_query;

    protected $raw;

    public function __construct($report, ReportFilter $reportFilter, $environment = null, $use_cache = null) {

        $this->report = $report;

        //get the raw report file
        $this->raw = self::getReportFileContents($report);

        $this->raw_query = $reportFilter->apply($this->raw);
        var_dump($this->raw_query);

        $this->options['Environment'] = $environment;

        $this->initDb();
    }

    /**
     * @throws Exception
     */
    public static function getFileLocation($report): string
    {

        //make sure the report path doesn't go up a level for security reasons
        if(str_contains($report, "..")) {
            $reportdir = realpath(Config::get()['reportDir']).'/';
            $reportpath = substr(realpath(Config::get()['reportDir'].'/'.$report),0,strlen($reportdir));

            if($reportpath !== $reportdir) throw new Exception('Invalid report - '.$report);
        }

        $reportDir = Config::get()['reportDir'];
        return $reportDir.'/'.$report;
    }

    public static function getReportFileContents($report)
    {
        $contents = file_get_contents(self::getFileLocation($report));

        //convert EOL to unix format
        return str_replace(array("\r\n","\r"),"\n",$contents);
    }


    protected function initDb() {
        //if the database isn't set, use the first defined one from config
        $environments = Config::get()['environments'];
        if(!$this->options['Environment']) {
            $this->options['Environment'] = current(array_keys($environments));
        }

        //set database options
        $environment_options = array();
        foreach($environments as $key=>$params) {
            $environment_options[] = array(
                'name'=>$key,
                'selected'=>$key===$this->options['Environment']
            );
        }
        $this->options['Environments'] = $environment_options;

        $tmp = explode('.',$this->report);
        $file_type = array_pop($tmp);

        $this->options['Type'] = Config::get()['default_file_extension_mapping'][$file_type];

        $classname = $this->options['Type'].'Source';
        var_dump($classname);
        if(!class_exists($classname)) {
            throw new exception("Unknown report type '".$this->options['Type']."'");
        }
        if(!isset($this->options['Database'])) $this->options['Database'] = strtolower($this->options['Type']);

        if(!isset($this->options['Name'])) $this->options['Name'] = $this->report;

        $classname::init($this);
    }

}
