<?php

abstract class ReportFilterBase implements ReportFilter {

    protected array $filters;

    public function setFilters(array $filters): self{
        $this->filters = $filters;
        return $this;
    }

    public function getFilters(): array{
        return $this->filters;
    }

    abstract function apply(string $rawQuery): string;
}
