<?php
include '1_header.php';
?>

 <?php  if ( (acl_check('patients', 'med')) && ($GLOBALS['enable_cdr'] && $GLOBALS['enable_cdr_crw']) ) {
// clinical summary expand collapse widget
$widgetTitle = xl("Clinical Reminders");
$widgetLabel = "clinical_reminders";
$widgetButtonLabel = xl("Edit");
$widgetButtonLink = "../reminder/clinical_reminders.php?patient_id=".$pid;;
$widgetButtonClass = "";
$linkMethod = "html";
$bodyClass = "summary_item small";
$widgetAuth = true;
$fixedWidth = false;
expand_collapse_widget($widgetTitle, $widgetLabel, $widgetButtonLabel , $widgetButtonLink, $widgetButtonClass, $linkMethod, $bodyClass, $widgetAuth, $fixedWidth);
echo "<br/>";
echo "<div style='margin-left:10px' class='text'><image src='../../pic/ajax-loader.gif'/></div><br/>";
echo "</div>";
} // end if crw?>      
<?php
// Notes expand collapse widget
$widgetTitle = xl("Notes");
$widgetLabel = "pnotes";
$widgetButtonLabel = xl("Edit");
$widgetButtonLink = "pnotes_full.php?form_active=1";
$widgetButtonClass = "";
$linkMethod = "html";
$bodyClass = "notab";
$widgetAuth = true;
$fixedWidth = true;
expand_collapse_widget($widgetTitle, $widgetLabel, $widgetButtonLabel,
  $widgetButtonLink, $widgetButtonClass, $linkMethod, $bodyClass,
  $widgetAuth, $fixedWidth);
?>

</body>
</html>