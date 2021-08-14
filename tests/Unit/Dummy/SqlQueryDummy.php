<?php


class SqlQueryDummy
{
    public static function PlainSqlQuery(): string {
        return "SELECT date, turnover FROM gmv";
    }

    public static function SqlQueryWithFilterParam(): string {
        $query = self::PlainSqlQuery();

        return $query.' WHERE gmv.date >= "{{dateFilter}}" ';
    }

    public static function InvalidSqlQuery(): string {
        $query = self::PlainSqlQuery();

        return $query.' INVALID QUERY ';
    }
}
