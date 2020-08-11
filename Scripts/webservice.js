
 
 
 
 ageValue.indexOf(' ') >= 0;
 
 

 	 function getAllInfo()
{
    $.ajax({
        url: '/info',
        type: 'GET',
        cache: false,
        dataType: 'json',
        success: function (data) {
            createInfoTable(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(jqXHR + '\n' + textStatus + '\n' + errorThrown);
        }
    });
}

 
 
 
 
 function deleteInfo(Id)
{
    $.ajax({
        url: '/info/' + Id,
        type: 'DELETE',
        dataType: 'json',
        success: function (data) {
            getAllInfo();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(jqXHR + '\n' + textStatus + '\n' + errorThrown);
        }
    });
}

function fillInfoArrays()
{
	femalesArray(21)
}

function femalesArray(Age)
{
	$.ajax({
		url: '/info/Females/' + Age,
		type: 'GET',
		cache: false,
		dataType: 'json'
		success: function(data){
			drawCharts(data)
		},
		 error: function (jqXHR, textStatus, errorThrown) {
            alert(jqXHR + '\n' + textStatus + '\n' + errorThrown);
        }
    });
}


function drawChart(info) {
	alert("hi");
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Year');
      data.addColumn('number', 'Females');
 
	var fillWith = [];
	$.each(info, function (index, info_)
	{
		fillWith.push([info_.Year, Number(info_.Population_Estimate)]);
	}
      data.addRows([
      fillWith
      ]);

      var options = {
        chart: {
          title: 'Northern Ireland Population Estimate',
          subtitle: 'for females age 21',
          curveType: 'function'
        },
        width: 900,
        height: 500
      };

      var chart = new google.charts.Line(document.getElementById('curve_chart'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }



 function getAllInfo()
{
    $.ajax({
        url: '/info',
        type: 'GET',
        cache: false,
        dataType: 'json',
        success: function (data) {
            createInfoTable(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(jqXHR + '\n' + textStatus + '\n' + errorThrown);
        }
    });
}


 
 

 
