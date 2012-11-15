<?php
# $Id: ex-ytickanchor.php 1001 2011-08-08 02:22:55Z lbayuk $
# PHPlot Example: Using a Y tick anchor to force a tick at 0 (part 1)
# This requires PHPlot >= 5.4.0
require_once 'phplot.php';

# The variable $set_anchor can be set to a value in another script
# which calls this script, to set the Y anchor to that value.
if (isset($set_anchor))
    $case = "with Y tick anchor at $set_anchor";
else
    $case = "without Y tick anchor";

# Function to plot:
function f($x)
{
  if ($x == 0) return 0;
  return 5 + 8 * sin(200 * M_PI / $x);
}

$data = array();
for ($x = 0; $x < 500; $x++)
  $data[] = array('', $x, f($x));

$plot = new PHPlot(800, 600);
$plot->SetImageBorderType('plain'); // For presentation in the manual
$plot->SetTitle("Example $case");
$plot->SetDataType('data-data');
$plot->SetDataValues($data);
$plot->SetPlotType('lines');
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

if (isset($set_anchor))
    $plot->SetYTickAnchor($set_anchor);

$plot->DrawGraph();