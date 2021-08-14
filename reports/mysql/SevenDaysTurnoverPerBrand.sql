SELECT
    b.name,
    sum(turnover) turnover
FROM
    gmv
        INNER JOIN brands b on gmv.brand_id = b.id
WHERE gmv.date >= "{{startdate}}" AND gmv.date <= "{{enddate}}"
GROUP BY b.id
