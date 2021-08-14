SELECT
    b.name brand_name,
    sum(turnover - turnover*0.21) turnover
FROM
    gmv
        INNER JOIN brands b on gmv.brand_id = b.id
WHERE gmv.date >= "{{startdate}}" AND gmv.date <= "{{enddate}}"
GROUP BY b.id
