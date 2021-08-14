SELECT
    DATE(date) Day,
    SUM(turnover - turnover*0.21) "Total turnover (excluding VAT)"
FROM
    gmv
WHERE gmv.date >= "{{startdate}}" AND gmv.date <= "{{enddate}}"
GROUP BY DATE(date)
