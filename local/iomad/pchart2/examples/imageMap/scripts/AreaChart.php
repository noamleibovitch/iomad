<?php
 /* Library settings */
 define("CLASS_PATH", "../../../class");
 define("FONT_PATH", "../../../fonts");

 /* pChart library inclusions */
 include(CLASS_PATH."/pData.class.php");
 include(CLASS_PATH."/pDraw.class.php");
 include(CLASS_PATH."/pImage.class.php");

 /* Create and populate the pData object */
 $MyData = new pData();
 $MyData->addPoints(array(4,2,10,12,8,3),"Probe 1");
 $MyData->addPoints(array(3,12,15,8,5,5),"Probe 2");
 $MyData->addPoints(array(2,7,5,18,15,22),"Probe 3");
 $MyData->setSerieTicks("Probe 2",4);
 $MyData->setAxisName(0,"Temperatures");
 $MyData->addPoints(array("Jan","Feb","Mar","Apr","May","Jun"),"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(700,230,$MyData);

 /* Retrieve the image map */
 if (isset($_GET["ImageMap"]) || isset($_POST["ImageMap"]))
  $myPicture->dumpImageMap("ImageMapAreaChart",IMAGE_MAP_STORAGE_FILE,"AreaChart","../tmp");

 /* Set the image map name */
 $myPicture->initialiseImageMap("ImageMapAreaChart",IMAGE_MAP_STORAGE_FILE,"AreaChart","../tmp");

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Draw the background */
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,700,230,$Settings);

 /* Overlay with a gradient */
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the chart title */
 $myPicture->setFontProperties(array("FontName"=>FONT_PATH."/Forgotte.ttf","FontSize"=>11));
 $myPicture->drawText(150,35,"Average temperature",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>FONT_PATH."/pf_arma_five.ttf","FontSize"=>6));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,650,200);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>255,"GridG"=>255,"GridB"=>255,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Write the chart legend */
 $myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the area chart */
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 $myPicture->drawAreaChart();

 /* Draw a line and a plot chart on top */
 $myPicture->drawLineChart();
 $myPicture->drawPlotChart(array("RecordImageMap"=>TRUE,"PlotBorder"=>TRUE,"PlotSize"=>3,"BorderSize"=>1,"Surrounding"=>-60,"BorderAlpha"=>80));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawAreaChart.simple.png");
?>