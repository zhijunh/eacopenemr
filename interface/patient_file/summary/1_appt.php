<?php
include '1_header.php';
?>
    <?php
	// Show current and upcoming appointments.
	if (isset($pid) && !$GLOBALS['disable_calendar']) {
	 $query = "SELECT e.pc_eid, e.pc_aid, e.pc_title, e.pc_eventDate, " .
	  "e.pc_startTime, e.pc_hometext, u.fname, u.lname, u.mname, " .
	  "c.pc_catname, e.pc_apptstatus " .
	  "FROM openemr_postcalendar_events AS e, users AS u, " .
	  "openemr_postcalendar_categories AS c WHERE " .
	  "e.pc_pid = ? AND e.pc_eventDate >= CURRENT_DATE AND " .
	  "u.id = e.pc_aid AND e.pc_catid = c.pc_catid " .
	  "ORDER BY e.pc_eventDate, e.pc_startTime";
	 $res = sqlStatement($query, array($pid) );

    

	// appointments expand collapse widget
        $widgetTitle = xl("Appointments");
        $widgetLabel = "appointments";
        $widgetButtonLabel = xl("Add");
        $widgetButtonLink = "return newEvt();";
        $widgetButtonClass = "";
        $linkMethod = "javascript";
        $bodyClass = "summary_item small";
        $widgetAuth = (isset($res) && $res != null);
        $fixedWidth = false;
        expand_collapse_widget($widgetTitle, $widgetLabel, $widgetButtonLabel , $widgetButtonLink, $widgetButtonClass, $linkMethod, $bodyClass, $widgetAuth, $fixedWidth);
        $count = 0;
        while($row = sqlFetchArray($res)) {
            $count++;
            $dayname = date("l", strtotime($row['pc_eventDate']));
            $dispampm = "am";
            $disphour = substr($row['pc_startTime'], 0, 2) + 0;
            $dispmin  = substr($row['pc_startTime'], 3, 2);
            if ($disphour >= 12) {
                $dispampm = "pm";
                if ($disphour > 12) $disphour -= 12;
            }
            $etitle = xl('(Click to edit)');
            if ($row['pc_hometext'] != "") {
                $etitle = xl('Comments').": ".($row['pc_hometext'])."\r\n".$etitle;
            }
            echo "<a href='javascript:oldEvt(" . htmlspecialchars($row['pc_eid'],ENT_QUOTES) . ")' title='" . htmlspecialchars($etitle,ENT_QUOTES) . "'>";
            echo "<b>" . htmlspecialchars(xl($dayname) . ", " . $row['pc_eventDate'],ENT_NOQUOTES) . "</b>" . xlt("Status") .  "(";
            echo " " .  generate_display_field(array('data_type'=>'1','list_id'=>'apptstat'),$row['pc_apptstatus']) . ")<br>";   // can't use special char parser on this
            echo htmlspecialchars("$disphour:$dispmin " . xl($dispampm) . " " . xl_appt_category($row['pc_catname']),ENT_NOQUOTES) . "<br>\n";
            echo htmlspecialchars($row['fname'] . " " . $row['lname'],ENT_NOQUOTES) . "</a><br>\n";
        }
        if (isset($res) && $res != null) {
            if ( $count < 1 ) { 
                echo "&nbsp;&nbsp;" . htmlspecialchars(xl('None'),ENT_NOQUOTES); 
            }
            echo "</div>";
      }
    }
            
	// Show PAST appointments.
	if (isset($pid) && !$GLOBALS['disable_calendar'] && $GLOBALS['num_past_appointments_to_show'] > 0) {
	 $query = "SELECT e.pc_eid, e.pc_aid, e.pc_title, e.pc_eventDate, " .
	  "e.pc_startTime, e.pc_hometext, u.fname, u.lname, u.mname, " .
	  "c.pc_catname, e.pc_apptstatus " .
	  "FROM openemr_postcalendar_events AS e, users AS u, " .
	  "openemr_postcalendar_categories AS c WHERE " .
	  "e.pc_pid = ? AND e.pc_eventDate < CURRENT_DATE AND " .
	  "u.id = e.pc_aid AND e.pc_catid = c.pc_catid " .
	  "ORDER BY e.pc_eventDate, e.pc_startTime DESC " . 
      "LIMIT " . $GLOBALS['num_past_appointments_to_show'];
	
     $pres = sqlStatement($query, array($pid) );

	// appointments expand collapse widget
        $widgetTitle = xl("Past Appoinments");
        $widgetLabel = "past_appointments";
        $widgetButtonLabel = '';
        $widgetButtonLink = '';
        $widgetButtonClass = '';
        $linkMethod = "javascript";
        $bodyClass = "summary_item small";
        $widgetAuth = false; //no button
        $fixedWidth = false;
        expand_collapse_widget($widgetTitle, $widgetLabel, $widgetButtonLabel , $widgetButtonLink, $widgetButtonClass, $linkMethod, $bodyClass, $widgetAuth, $fixedWidth);   
        $count = 0;
        while($row = sqlFetchArray($pres)) {
            $count++;
            $dayname = date("l", strtotime($row['pc_eventDate']));
            $dispampm = "am";
            $disphour = substr($row['pc_startTime'], 0, 2) + 0;
            $dispmin  = substr($row['pc_startTime'], 3, 2);
            if ($disphour >= 12) {
                $dispampm = "pm";
                if ($disphour > 12) $disphour -= 12;
            }
            if ($row['pc_hometext'] != "") {
                $etitle = xl('Comments').": ".($row['pc_hometext'])."\r\n".$etitle;
            }
            echo "<a href='javascript:oldEvt(" . htmlspecialchars($row['pc_eid'],ENT_QUOTES) . ")' title='" . htmlspecialchars($etitle,ENT_QUOTES) . "'>";
            echo "<b>" . htmlspecialchars(xl($dayname) . ", " . $row['pc_eventDate'],ENT_NOQUOTES) . "</b>" . xlt("Status") .  "(";
            echo " " .  generate_display_field(array('data_type'=>'1','list_id'=>'apptstat'),$row['pc_apptstatus']) . ")<br>";   // can't use special char parser on this
            echo htmlspecialchars("$disphour:$dispmin ") . xl($dispampm) . " ";
            echo htmlspecialchars($row['fname'] . " " . $row['lname'],ENT_NOQUOTES) . "</a><br>\n";
        }
        if (isset($pres) && $res != null) {
           if ( $count < 1 ) { 
               echo "&nbsp;&nbsp;" . htmlspecialchars(xl('None'),ENT_NOQUOTES);          
           }
        echo "</div>";
        }
    }
// END of past appointments            
            
			?>

</body>
</html>