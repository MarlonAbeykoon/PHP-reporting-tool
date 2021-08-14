<?php

interface ReportFilter {
    public function setFilters(array $filters): self;
    public function getFilters(): array;
    public function apply(string $rawQuery): string;
}
