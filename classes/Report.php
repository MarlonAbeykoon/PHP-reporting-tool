<?php

class Report {

    public $report;
    public $options = array();
    public $raw_query;

    protected $raw;

    public function __construct($report, ?ReportFilter $reportFilter = null, $environment = null) {

        $this->report = $report;

        //get the raw report file
        $this->raw = self::getReportFileContents($report);

        if($reportFilter !== null){
            $this->raw_query = $reportFilter->apply($this->raw);
        }

        $this->options['Environment'] = $environment;

        $this->initDb();
    }

    /**
     * @throws ReportException
     */
    public static function getFileLocation(string $report): string
    {

        //make sure the report path doesn't go up a level for security reasons
        if(str_contains($report, "..")) {
            $reportdir = realpath(Config::get()['reportDir']).'/';
            $reportpath = substr(realpath(Config::get()['reportDir'].'/'.$report),0,strlen($reportdir));

            if($reportpath !== $reportdir) throw new ReportException('Invalid report - '.$report);
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

        if(!isset($this->options['Database'])) $this->options['Database'] = strtolower($this->options['Type']);

        if(!isset($this->options['Name'])) $this->options['Name'] = $this->report;
    }

}
