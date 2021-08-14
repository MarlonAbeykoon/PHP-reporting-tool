# PHP-reporting-tool

###### **Initialize environment**

`docker pull mysql:8.0
`

`docker run -p 3307:3306 --name mysql8.0 -e MYSQL_ROOT_PASSWORD=root -d mysql:8.0`

`composer install`

set mysql details in `environments` section in _configs/con.php_

###### **Report generation**

List available reports - `php ReportGeneratorTool.php --function=listReports`

Generate reports - `php ReportGeneratorTool.php --function=generate --report=mysql/SevenDaysTurnoverPerDay.sql --output=csv --type=mysql --filter='startdate':'2018-05-01','enddate':'2018-05-07'`

###### **New data source additions**

Mysql is already implemented. If another datasource needs to be added, it should follow the following steps. 
1. The `ReportSource` interface should be implemented in the new class and place in the ReportSources directory.
2. A new mapping should be added to `default_file_extension_mapping` in the config. (extension of the report and the source is mapped)
3. Reports should be placed under reports/ (eg: reports/mongo/report1.js).
4. When generating the report the --type flag should be used with the new datasource name.

###### **New data output additions**

CSV is already implemented. If another data output method needs to be added, it should follow the following steps.
1. The `ReportOutput` interface should be implemented in the new class and place in the ReportOutputTypes directory.
2. A new mapping should be added to `report_formats` in the config.
3. When generating the report the --output flag should be used with the new output name.

###### **New filter additions to reports**

Two filters are currently integrated in the existing reports. If a new filter type to be adopted.
1. The `ReportFilter` interface should be implemented, the new filter type class needs to be placed under ReportFilter directory. The class should be prefixed with the datasource name it works with. (eg: MysqlFilter)

To add a filter to an existing report,
1. The report file needs to include the filtername (eg: reports/mysql/SevenDaysTurnoverPerBrand.sql `WHERE gmv.date >= "{{startdate}}" AND gmv.date <= "{{enddate}}"`)
2. The filters can be changed at runtime by using the --filter flag with a comma seperated string. (`--filter='startdate':'2018-05-01','enddate':'2018-05-07'`)

Note:

Two sample reports are generated at the root. (i.e: mysqlSevenDaysTurnoverPerDay.sql.csv & mysqlSevenDaysTurnoverPerBrand.sql.csv)
commands: 
1. `php ReportGeneratorTool.php --function=generate --report=mysql/SevenDaysTurnoverPerDay.sql --output=csv --type=mysql --filter='startdate':'2018-05-01','enddate':'2018-05-07'`
2. `php ReportGeneratorTool.php --function=generate --report=mysql/SevenDaysTurnoverPerBrand.sql --output=csv --type=mysql --filter='startdate':'2018-05-01','enddate':'2018-05-07'`


