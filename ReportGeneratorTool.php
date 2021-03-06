<?php

//sets up autoloading of composer dependencies
include 'vendor/autoload.php';

session_start();


class ReportGeneratorTool {

    private ReportOutput $reportOutput;
    private ?ReportFilter $reportFilter;
    private ReportSource $reportSource;

    public function __construct(ReportOutput $reportOutput, ReportSource $reportSource, ?ReportFilter $reportFilter = null) {
        $this->reportOutput = $reportOutput;
        $this->reportFilter = $reportFilter;
        $this->reportSource = $reportSource;
    }

    public function generate(array $filters): int {
        try {
            $report = $this->reportOutput->prepareReport($this->reportFilter->setFilters($filters));
        }
        catch(Exception $e) {
            fwrite(STDERR, $e->getMessage());
            fwrite(STDERR, "==> An error occurred while preparing the report");
            return (-1);
        }

        try{
            $this->reportSource::run($report);
        }
        catch(Exception $e) {
            fwrite(STDERR, $e->getMessage());
            fwrite(STDERR, "==> An error occurred while running the report");
            return (-1);
        }

        try{
            $this->reportOutput->generate($report);
        }
        catch(Exception $e) {
            fwrite(STDERR, $e->getMessage());
            fwrite(STDERR, "==> An error occurred while generating the report");
            return (-1);
        }

        return 0;
    }

    public static function listAvailableReports() {
        foreach(glob(Config::get()['reportDir'].'/*/*') as $file) {
            echo substr($file, strpos($file, '/', 1)). "\n";
        }
    }
}


// Only run this when executed on the commandline
if (php_sapi_name() == 'cli') {
    $_SESSION['environment'] = 'main';

    $config = 'configs/conf.php';
    if(!file_exists($config)) {
        throw new Exception("Cannot find config file");
    }
    $functionVar = getopt(null, ["function:"])['function'];

    //    ReportGeneratorTool::init();
    if($functionVar == 'generate'){
        try{
            $reportVar = getopt(null, ["report:"])['report'];
            $outputVar = getopt(null, ["output:"])['output'];
            $typeVar = getopt(null, ["type:"])['type'];
            $filterVar = getopt(null, ["filter:"])['filter'];
        }
        catch (Exception $e) {
            fwrite(STDERR, "==> Error in input list");
        }

        $filterArray = explode(',', $filterVar);
        $filters = array();
        foreach ($filterArray as $filter){
            $val = explode(':', $filter);
            $filters['{{'.$val[0].'}}'] = $val[1];
        }

        $outputTypeClassname = ucfirst(strtolower($outputVar)).'OutputType';

        $filterClassName = ucfirst(strtolower($typeVar)).'Filter';

        $sourceClassName = ucfirst(strtolower($typeVar)).'Source';

        if(!class_exists($sourceClassName)) {
            fwrite(STDERR, "==> Unknown report type ". $sourceClassName);
        }

        if(!class_exists($outputTypeClassname)) {
            fwrite(STDERR, "==> Unknown report format ". $outputTypeClassname);
            return (-1);
        }

        $reportDir = Config::get()['reportDir'];
        if(!file_exists($reportDir.'/'.$reportVar)) {
            fwrite(STDERR, "==> Report not found ". $reportVar);
            throw new Exception('Report not found - '.$reportVar);
        }

        (new ReportGeneratorTool(new $outputTypeClassname($reportVar), new $sourceClassName(), new $filterClassName()))->generate($filters);  // Uses Strategy design pattern to switch between algorithms for report outputs

    }
    elseif($functionVar === 'listReports'){
        ReportGeneratorTool::listAvailableReports();
    }
    else{
        fwrite(STDERR, "Use generate, listReports commands");
    }

}
