<?php

interface ReportOutput {
    public function generate(Report $report): void;
    public function prepareReport(ReportFilter $reportFilter): Report;
}

