# CurlWebScraping

Calorific value data is being fetched from the http://mip-prd-web.azurewebsites.net/DataItemExplorer/Index url using curl php.

Follow the steps:
Step 1: execute calorific_value_data.sql file for database.
Step 2: execute index.php file for fetching the data from the url and displaying the results in the web page.

Index.php contains curl script to fetch the data from the url mention above. To avoid much loading time, i have fetched 3 areas from the calorific value areas.
the data is fetched in xml format.
Mysql queries are used to store the data into 2 tables : calorific_data and calorific_value.
using php, data from the tables are displayed in front-end web page.
Fusion Charts is used to display the data into graphical representation. For that, include the fusioncharts.php file in the index.php directory. 
