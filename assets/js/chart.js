jQuery(document).ready(function () {
	if (kawuda_chart_vars.id == 0 && kawuda_chart_vars.current_user_id > 0 && kawuda_chart_vars.view == "kawuda" && kawuda_chart_vars.utm_output != 0) {
     // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', kawuda_chart_vars.date_range_x);
        data.addColumn('number',  kawuda_chart_vars.date_range_y);
        
        var utm_output =  JSON.parse(kawuda_chart_vars.utm_output);        
        console.log(utm_output);
        data.addRows(utm_output);


        // Set chart options
        var options = {'title':kawuda_chart_vars.date_range,
                       'width':300,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('utm_source_chart_div'));
        chart.draw(data, options);
      }


}
 
 if (kawuda_chart_vars.id == 0 && kawuda_chart_vars.current_user_id > 0 && kawuda_chart_vars.view == "kawuda" && kawuda_chart_vars.utm_output_row_me != 0) {


      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart1);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart1() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', kawuda_chart_vars.date_range_x);
        data.addColumn('number',  kawuda_chart_vars.date_range_y);
        
        var utm_output =  JSON.parse(kawuda_chart_vars.utm_output_row_me);        
        console.log(utm_output);
        data.addRows(utm_output);


        // Set chart options
        var options = {'title':kawuda_chart_vars.date_range_medium,
                       'width':300,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('utm_medium_chart_div'));
        chart.draw(data, options);
      }
  }
  if (kawuda_chart_vars.id == 0 && kawuda_chart_vars.current_user_id > 0 && kawuda_chart_vars.view == "kawuda" && kawuda_chart_vars.utm_output_row_ca != 0) {


      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart1);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart1() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', kawuda_chart_vars.date_range_x);
        data.addColumn('number',  kawuda_chart_vars.date_range_y);
        
        var utm_output_ca =  JSON.parse(kawuda_chart_vars.utm_output_row_ca);        
        console.log(utm_output_ca);
        data.addRows(utm_output_ca);


        // Set chart options
        var options = {'title':kawuda_chart_vars.date_range_campain,
                       'width':300,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('utm_campain_chart_div'));
        chart.draw(data, options);
      }
  }
});



 