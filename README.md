
# CurlWebScraping


![Capture](https://user-images.githubusercontent.com/64549960/119282857-0c42f200-bc33-11eb-9ed3-ae5d8505cac1.PNG)

Calorific value data is being fetched from the http://mip-prd-web.azurewebsites.net/DataItemExplorer/Index url using curl php.

Follow the steps:
Step 1: execute calorific_value_data.sql file for database.
Step 2: execute index.php file for fetching the data from the url and displaying the results in the web page.

Index.php contains curl script to fetch the data from the url mentioned above. To avoid excessive loading time, i have fetched 3 areas from the calorific value areas.
The data is fetched in xml format.
Mysql queries are used to store the data into 2 tables : calorific_data and calorific_value.
Using php, data from the database is displayed in web page.
Fusion Charts is used to give graphical representation of the data. For that, include the fusioncharts.php file in the index.php directory. 
