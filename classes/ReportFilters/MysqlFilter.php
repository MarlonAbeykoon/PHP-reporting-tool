<?php

class MysqlFilter extends ReportFilterBase {
    public function apply(string $rawQuery): string
    {
        return strtr($rawQuery, parent::getFilters());
    }
}
