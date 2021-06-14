# NIPopEstimateGraph
Northern Ireland Population Estimate Graph Visualisation Application. Implements a REST API web service. [Created to be published on an ubuntu virtual machine on an Azure resource group]


This application is used for the visualisation of population estimates of Northern Ireland. 
The application allows the visualisation of data supplied from the government in tabular form. It contains a webpage made using HTML, CSS and JavaScript and a REST API web service. It retrieves data from a database using phpMyAdmin and transfers it to the client webpage using JSON format.
The visualisation is a line graph that is created with help from the Google Charts library, it can be changed depending on the age and gender you wish to view.

It allows for the population estimate for ages 0-90 of either males, females or both between the years of 1971 and 2019 to be shown on a line graph. When points are moused over on the graph it highlights the exact data.

The data I used is obtained opendatani.gov.uk and is part of the Population Estimates for Northern Ireland, specifically the Population Estimates for Northern Ireland by single year of age and gender (mid-1971 to mid-2019). 

It can be found at the following URL:
https://www.opendatani.gov.uk/dataset/population-estimates-for-northern-ireland/resource/dec6ac8b-fb34-4a5a-8f5b-252c1834b1f6
and downloaded as a .csv file at the following URL:
https://www.opendatani.gov.uk/dataset/62e7073f-e924-4d3f-81a5-ad45b5127682/resource/dec6ac8b-fb34-4a5a-8f5b-252c1834b1f6/download/northern-ireland-by-single-year-of-age-and-gender-mid-1971-to-mid-2019.csv

The Google Charts library can be found at the following URL:
https://developers.google.com/chart
