<?php

use PHPUnit\Framework\TestCase;


class MysqlFilterTest extends TestCase {

    public function test_it_applies_filter_value_to_the_raw_query(): void {
        $raw_query = SqlQueryDummy::SqlQueryWithFilterParam();

        $mySqlFilter = new MysqlFilter();
        $mySqlFilter->setFilters(['{{dateFilter}}'=>'2020-01-05']);
        $query = $mySqlFilter->apply($raw_query);

        $this->assertSame('SELECT date, turnover FROM gmv WHERE gmv.date >= "2020-01-05" ', $query);
    }

    public function test_it_doesnot_apply_filter_value_to_the_raw_query_with_different_filter_name(): void {
        $raw_query = SqlQueryDummy::SqlQueryWithFilterParam();

        $mySqlFilter = new MysqlFilter();
        $mySqlFilter->setFilters(['{{dateFilterDifferent}}'=>'2020-01-05']);
        $query = $mySqlFilter->apply($raw_query);

        $this->assertSame('SELECT date, turnover FROM gmv WHERE gmv.date >= "{{dateFilter}}" ', $query);
    }

    public function test_it_ignores_filter_values_if_no_filters_are_present_in_the_row_query(): void {
        $raw_query = SqlQueryDummy::PlainSqlQuery();

        $mySqlFilter = new MysqlFilter();
        $mySqlFilter->setFilters(['{{dateFilter}}'=>'2020-01-05']);
        $query = $mySqlFilter->apply($raw_query);

        $this->assertSame('SELECT date, turnover FROM gmv', $query);
    }
}
