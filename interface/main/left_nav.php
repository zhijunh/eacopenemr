<?php
/* Copyright (C) 2006-2012 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 */

 // This provides the left navigation frame when $GLOBALS['concurrent_layout']
 // is true.  Following are notes as to what else was changed for this feature:
 //
 // * interface/main/main_screen.php: the top-level frameset.
 // * interface/main/finder/patient_select.php: loads stuff when a new patient
 //   is selected.
 // * interface/patient_file/summary/demographics.php: this is the first frame
 //   loaded when a new patient is chosen, and in turn sets the current pid and
 //   then loads the initial bottom frame.
 // * interface/patient_file/summary/demographics_full.php: added support for
 //   setting a new pid, needed for going to demographics from billing.
 // * interface/patient_file/summary/demographics_save.php: redisplay
 //   demographics.php and not the frameset.
 // * interface/patient_file/summary/summary_bottom.php: new frameset for the
 //   summary, prescriptions and notes for a selected patient, cloned from
 //   patient_summary.php.
 // * interface/patient_file/encounter/encounter_bottom.php: new frameset for
 //   the selected encounter, mosting coding/billing stuff, cloned from
 //   patient_encounter.php.  This will also self-load the superbill pages
 //   as requested.
 // * interface/usergroup/user_info.php: removed Back link.
 // * interface/usergroup/admin_frameset.php: new frameset for Admin pages,
 //   cloned from usergroup.php.
 // * interface/main/onotes/office_comments.php: removed Back link target.
 // * interface/main/onotes/office_comments_full.php: changed Back link.
 // * interface/billing/billing_report.php: removed Back link; added logic
 //   to properly go to demographics or to an encounter when requested.
 // * interface/new/new.php: removed Back link and revised form target.
 // * interface/new/new_patient_save.php: modified to load the demographics
 //   page to the current frame instead of loading a new frameset.
 // * interface/patient_file/history/history.php: target change.
 // * interface/patient_file/history/history_full.php: target changes.
 // * interface/patient_file/history/history_save.php: target change.
 // * interface/patient_file/history/encounters.php: link/target changes.
 // * interface/patient_file/encounter/encounter_top.php: another new frameset
 //   cloned from patient_encounter.php.
 // * interface/patient_file/encounter/forms.php: link target removal.
 // * interface/patient_file/encounter/new_form.php: target change.
 // * interface/forms/newpatient/new.php, view.php, save.php: link/target
 //   changes.
 // * interface/patient_file/summary/immunizations.php: removed back link.
 // * interface/patient_file/summary/pnotes.php: changed link targets.
 // * interface/patient_file/summary/pnotes_full.php: changed back link and
 //   added set_pid logic.
 // * interface/patient_file/transaction/transactions.php: various changes.
 // * interface/patient_file/transaction/add_transaction.php: new return js.
 // * interface/patient_file/encounter/superbill_codes.php: target and link
 //   changes.
 // * interface/patient_file/encounter/superbill_custom_full.php: target and
 //   link changes.
 // * interface/patient_file/encounter/diagnosis.php: target changes.
 // * interface/patient_file/encounter/diagnosis_full.php: target and link
 //   changes.
 // * interface/main/authorizations/authorizations.php: link and target changes.
 // * library/api.inc: url change.
 // * interface/patient_file/summary/rx_frameset.php: new frameset.
 // * interface/patient_file/summary/rx_left.php: new for prescriptions.
 // * all encounter forms: remove all instances of "target=Main" and change
 //   all instances of "patient_encounter.php" to "encounter_top.php".

 // Our find_patient form, when submitted, invokes patient_select.php in the
 // upper frame. When the patient is selected, demographics.php is invoked
 // with the set_pid parameter, which establishes the new session pid and also
 // calls the setPatient() function (below).  In this case demographics.php
 // will also load the summary frameset into the bottom frame, invoking our
 // loadFrame() and setRadio() functions.
 //
 // Similarly, we have the concept of selecting an encounter from the
 // Encounters list, and then having that "stick" until some other encounter
 // or a new encounter is chosen.  We also have a navigation item for creating
 // a new encounter.  interface/patient_file/encounter/encounter_top.php
 // supports set_encounter to establish an encounter.
 //
 // TBD: Include active_pid and/or active_encounter in relevant submitted
 // form data, and add logic to the save routines to make sure they match
 // the corresponding session values.

 require_once("../globals.php");
 require_once($GLOBALS['fileroot']."/library/acl.inc");
 require_once($GLOBALS['fileroot']."/custom/code_types.inc.php");
 require_once($GLOBALS['fileroot']."/library/patient.inc");
 require_once($GLOBALS['fileroot']."/library/lists.inc");

 // This array defines the list of primary documents that may be
 // chosen.  Each element value is an array of 3 values:
 //
 // * Name to appear in the navigation table
 // * Usage: 0 = global, 1 = patient-specific, 2 = encounter-specific
 // * The URL relative to the interface directory
 //

 $primary_docs = array(
  'ros' => array(xl('Roster')    , 0, 'reports/players_report.php?embed=1'),
  'cal' => array(xl('Calendar')  , 0, 'main/main_info.php'),
  'app' => array(xl('Portal Activity')  , 0, '../myportal/index.php'),
  'msg' => array(xl('Messages')  , 0, 'main/messages/mailbox.php?form_active=1'),
  'pwd' => array(xl('Password')  , 0, 'usergroup/user_info.php'),
  'prf' => array(xl('Preferences')  , 0, 'super/edit_globals.php?mode=user'),
  'adm' => array(xl('Admin')     , 0, 'usergroup/admin_frameset.php'),
  'rep' => array(xl('Reports')   , 0, 'reports/index.php'),
  'ono' => array(xl('Ofc Notes') , 0, 'main/onotes/office_comments.php'),
  'fax' => array(xl('Fax/Scan')  , 0, 'fax/faxq.php'),
  'adb' => array(xl('Addr Bk')   , 0, 'usergroup/addrbook_list.php'),
  'orl' => array(xl('Proc Prov') , 0, 'orders/procedure_provider_list.php'),
  'ort' => array(xl('Proc Cat')  , 0, 'orders/types.php'),
  'orc' => array(xl('Proc Load') , 0, 'orders/load_compendium.php'),
  'orb' => array(xl('Proc Bat')  , 0, 'orders/orders_results.php?batch=1'),
  'ore' => array(xl('E-Reports') , 0, 'orders/list_reports.php'),
  'cht' => array(xl('Chart Trk') , 0, '../custom/chart_tracker.php'),
  'imp' => array(xl('Import')    , 0, '../custom/import.php'),
  'bil' => array(xl('Billing')   , 0, 'billing/billing_report.php'),
  'sup' => array(xl('Superbill') , 0, 'patient_file/encounter/superbill_custom_full.php'),
  'aun' => array(xl('Authorizations'), 0, 'main/authorizations/authorizations.php'),
  'new' => array(xl('New Pt')    , 0, 'new/new.php'),
  'ped' => array(xl('Patient Education'), 0, 'reports/patient_edu_web_lookup.php'),
  'lab' => array(xl('Check Lab Results')  , 0, 'orders/lab_exchange.php'),
  'dem' => array(xl('Patient')   , 1,  "patient_file/summary/demographics.php"),
  'his' => array(xl('History')   , 1, 'patient_file/history/history.php'),
  'ens' => array(xl('Visit History'), 1, 'patient_file/history/encounters.php'),
  'nen' => array(xl('Create Visit'), 1, 'forms/newpatient/new.php?autoloaded=1&calenc='),
  'pre' => array(xl('Rx')        , 1, 'patient_file/summary/rx_frameset.php'),
  'iss' => array(xl('Issues')    , 1, 'patient_file/summary/stats_full.php?active=all'),
  'imm' => array(xl('Immunize')  , 1, 'patient_file/summary/immunizations.php'),
  'doc' => array(xl('Documents') , 1, '../controller.php?document&list&patient_id={PID}'),
  'orp' => array(xl('Proc Pending Rev'), 1, 'orders/orders_results.php?review=1'),
  'orr' => array(xl('Proc Res')  , 1, 'orders/orders_results.php'),
  'prp' => array(xl('Pt Report') , 1, 'patient_file/report/patient_report.php'),
  'prq' => array(xl('Pt Rec Request') , 1, 'patient_file/transaction/record_request.php'),
  'pno' => array(xl('Pt Notes')  , 1, 'patient_file/summary/pnotes.php'),
  'tra' => array(xl('Transact')  , 1, 'patient_file/transaction/transactions.php'),
  'sum' => array(xl('Summary')   , 1, 'patient_file/summary/summary_bottom.php'),
  'enc' => array(xl('Encounter') , 2, 'patient_file/encounter/encounter_top.php'),
  'erx' => array(xl('e-Rx') , 1, 'eRx.php'),
  'err' => array(xl('e-Rx Renewal') , 1, 'eRx.php?page=status'),
  'pay' => array(xl('Payment') , 1, '../patient_file/front_payment.php'),
  'edi' => array(xl('EDI History') , 0, 'billing/edih_view.php')
 );
 $primary_docs['npa']=array(xl('Batch Payments')   , 0, 'billing/new_payment.php');
 if ($GLOBALS['use_charges_panel'] || $GLOBALS['concurrent_layout'] == 2) {
  $primary_docs['cod'] = array(xl('Charges'), 2, 'patient_file/encounter/encounter_bottom.php');
 }

 // This section decides which navigation items will not appear.

 $disallowed = array();
 $disallowed['edi'] = !($GLOBALS['enable_edihistory_in_left_menu'] || acl_check('acct', 'eob'));
 $disallowed['adm'] = !(acl_check('admin', 'calendar') ||
  acl_check('admin', 'database') || acl_check('admin', 'forms') ||
  acl_check('admin', 'practice') || acl_check('admin', 'users') ||
  acl_check('admin', 'acl')      || acl_check('admin', 'super') ||
  acl_check('admin', 'superbill') || acl_check('admin', 'drugs'));

 $disallowed['bil'] = !(acl_check('acct', 'rep') || acl_check('acct', 'eob') ||
  acl_check('acct', 'bill'));

 $disallowed['new'] = !(acl_check('patients','demo','',array('write','addonly') ));

 $disallowed['fax'] = !($GLOBALS['enable_hylafax'] || $GLOBALS['enable_scanner']);

 $disallowed['ros'] = !$GLOBALS['athletic_team'];

 $disallowed['iss'] = !((acl_check('encounters','notes','','write') ||
  acl_check('encounters','notes_a','','write') ) &&
  acl_check('patients','med','','write') );

 $disallowed['imp'] = $disallowed['new'] ||
  !is_readable("$webserver_root/custom/import.php");

 $disallowed['cht'] = !is_readable("$webserver_root/custom/chart_tracker.php");

 $disallowed['pre'] = !(acl_check('patients', 'med'));

 // Helper functions for treeview generation.
 function genTreeLink($frame, $name, $title, $mono=false) {
  global $primary_docs, $disallowed;
  if (empty($disallowed[$name])) {
   $id = $name . $primary_docs[$name][1];
   echo "<li><a href='' id='$id' onclick=\"";
   if ($mono) {
    if ($frame == 'RTop')
     echo "forceSpec(true,false);";
    else
     echo "forceSpec(false,true);";
   }
   echo "return loadFrame2('$id','$frame','" .
        $primary_docs[$name][2] . "')\">" . $title . ($name == 'msg' ? ' <span id="reminderCountSpan" class="bold"></span>' : '')."</a></li>";
  }
 }
 function genMiscLink($frame, $name, $level, $title, $url, $mono=false) {
  global $disallowed;
  if (empty($disallowed[$name])) {
   $id = $name . $level;
   echo "<li><a href='' id='$id' onclick=\"";
   if ($mono) {
    if ($frame == 'RTop')
     echo "forceSpec(true,false);";
    else
     echo "forceSpec(false,true);";
   }
   echo "return loadFrame2('$id','$frame','" .
        $url . "')\">" . $title . "</a></li>";
  }
 }
 function genPopLink($title, $url, $linkid='') {
  echo "<li><a href='' ";
  if ($linkid) echo "id='$linkid' ";
  echo "onclick=\"return repPopup('$url')\"" .
       ">" . $title . "</a></li>";
 }
 function genDualLink($topname, $botname, $title) {
  global $primary_docs, $disallowed;
  if (empty($disallowed[$topname]) && empty($disallowed[$botname])) {
   $topid = $topname . $primary_docs[$topname][1];
   $botid = $botname . $primary_docs[$botname][1];
   echo "<li><a href='' id='$topid' " .
        "onclick=\"return loadFrameDual('$topid','$botid','" .
        $primary_docs[$topname][2] . "','" .
        $primary_docs[$botname][2] . "')\">" . $title . "</a></li>";
  }
 }

function genPopupsList($style='') {
  global $disallowed, $webserver_root;
?>
<select name='popups' onchange='selpopup(this)' style='background-color:transparent;font-size:9pt;<?php echo $style; ?>'>
 <option value=''><?php xl('Popups','e'); ?></option>
<?php if (!$disallowed['iss']) { ?>
 <option value='../patient_file/problem_encounter.php'><?php xl('Issues','e'); ?></option>
<?php } ?>
<?php if (!$GLOBALS['ippf_specific']) { ?>
 <option value='../../custom/export_xml.php'><?php xl('Export','e'); ?></option>
 <option value='../../custom/import_xml.php'><?php xl('Import','e'); ?></option>
<?php } ?>
<?php if ($GLOBALS['athletic_team']) { ?>
 <option value='../reports/players_report.php'><?php xl('Roster','e'); ?></option>
<?php } ;
 if (!$GLOBALS['disable_calendar']) { ?>
 <option value='../reports/appointments_report.php?patient=<?php if(isset($pid)) {echo $pid;} ?>'><?php xl('Appts','e'); ?></option>
<?php } ;
 if (file_exists("$webserver_root/custom/refer.php")) { ?>
 <option value='../../custom/refer.php'><?php xl('Refer','e'); ?></option>
<?php } ?>
 <option value='../patient_file/printed_fee_sheet.php?fill=1'><?php xl('Superbill','e'); ?></option>
 <option value='../patient_file/front_payment.php'><?php xl('Payment','e'); ?></option>
<?php if ($GLOBALS['inhouse_pharmacy']) { ?>
 <option value='../patient_file/pos_checkout.php'><?php xl('Checkout','e'); ?></option>
<?php } ?>
<?php if (is_dir($GLOBALS['OE_SITE_DIR'] . "/letter_templates")) { ?>
 <option value='../patient_file/letter.php'><?php xl('Letter','e'); ?></option>
<?php } ?>
</select>
<?php
}

function genFindBlock() {
?>
<table cellpadding='0' cellspacing='0' border='0'>
 <tr>
  <td class='smalltext'><?php xl('Find','e') ?>:&nbsp;</td>
  <td class='smalltext' colspan='2'>
   <input type="entry" size="7" name="patient" class='inputtext' style='width:65px;' />
  </td>
 </tr>
 <tr>
  <td class='smalltext'><?php xl('by','e') ?>:</td>
  <td class='smalltext'>
   <a href="javascript:findPatient('Last');" class="navitem"><?php xl('Name','e'); ?></a>
  </td>
  <td class='smalltext' align='right'>
   <a href="javascript:findPatient('ID');"   class="navitem"><?php xl('ID','e'); ?></a>
  </td>
 </tr>
 <tr>
  <td class='smalltext'>&nbsp;</td>
  <td class='smalltext'>
   <a href="javascript:findPatient('SSN');"  class="navitem"><?php xl('SSN','e'); ?></a>
  </td>
  <td class='smalltext' align='right'>
   <a href="javascript:findPatient('DOB');"  class="navitem"><?php xl('DOB','e'); ?></a>
  </td>
 </tr>
 <tr>
  <td class='smalltext'>&nbsp;</td>
  <td class='smalltext'>
   <a href="javascript:findPatient('Any');"  class="navitem"><?php xl('Any', 'e'); ?></a>
  </td>
  <td class='smalltext' align='right'>
   <a href="javascript:initFilter();"  class="navitem"><?php xl('Filter', 'e'); ?></a>
  </td>
 </tr>
</table>
<?php
} // End function genFindBlock()

?>
<html>
<head>
<title>Navigation</title>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">

<style type="text/css">
 body {
  font-size:8pt;
  font-weight:normal;
  padding: 5px 3px 5px 3px;
 }
 .smalltext {
  font-family:sans-serif;
  font-size:8pt;
  font-weight:normal;
 }
 a.navitem, a.navitem:visited {
  color:#0000ff;
  font-family:sans-serif;
  font-size:8pt;
  font-weight:bold;
 }
.inputtext {
 font-size:9pt;
 font-weight:normal;
 border-style:solid;
 border-width:1px;
 padding-left:2px;
 padding-right:2px;
 border-color: #000000;
 background-color:transparent;
}

#navigation ul {
 background-color:transparent;
}
#navigation-slide ul {
 background-color:transparent;
}
#navigation-slide a{
 width: 92%;
}
.nav-menu-img{
  width:25px;
  height:25px;
  border:none;
  margin-right:5px;
  vertical-align:middle;
}
</style>

<link rel="stylesheet" href="../../library/js/jquery.treeview-1.4.1/jquery.treeview.css" />
<script src="../../library/js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script src="../../library/js/jquery.treeview-1.4.1/jquery.treeview.js" type="text/javascript"></script>

<script type="text/javascript" src="../../library/dialog.js"></script>

<script language='JavaScript'>
 
 // tajemo work by CB 2012/01/31 12:32:57 PM dated reminders counter
 function getReminderCount(){ 
   top.restoreSession();
   // Send the skip_timeout_reset parameter to not count this as a manual entry in the
   //  timing out mechanism in OpenEMR.
   $.post("<?php echo $GLOBALS['webroot']; ?>/library/ajax/dated_reminders_counter.php",
     { skip_timeout_reset: "1" }, 
     function(data) {
       $("#reminderCountSpan").html(data);
    // run updater every 60 seconds 
     var repeater = setTimeout("getReminderCount()", 60000); 
   });
   //piggy-back on this repeater to run other background-services
   //this is a silent task manager that returns no output
   $.post("<?php echo $GLOBALS['webroot']; ?>/library/ajax/execute_background_services.php",
      { skip_timeout_reset: "1", ajax: "1" });
 }   
 
 $(document).ready(function (){
   getReminderCount();//
   parent.loadedFrameCount += 1;
 }) 
 // end of tajemo work dated reminders counter
 
 // Master values for current pid and encounter.
 var active_pid = 0;
 var active_encounter = 0;

 // Current selections in the top and bottom frames.
 var topName = '';
 var botName = '';

 // Expand and/or collapse frames in response to checkbox clicks.
 // fnum indicates which checkbox was clicked (1=left, 2=right).
 function toggleFrame(fnum) {
  var f = document.forms[0];
  var fset = top.document.getElementById('fsright');
  if (!f.cb_top.checked && !f.cb_bot.checked) {
   if (fnum == 1) f.cb_bot.checked = true;
   else f.cb_top.checked = true;
  }
  var rows = f.cb_top.checked ? '*' :  '0';
  rows += f.cb_bot.checked ? ',*' : ',0';
  fset.rows = rows;
  fset.rows = rows;
 }

 // Load the specified url into the specified frame (RTop or RBot).
 // The URL provided must be relative to interface.
 function loadFrame(fname, frame, url) {
  top.restoreSession();
  var i = url.indexOf('{PID}');
  if (i >= 0) url = url.substring(0,i) + active_pid + url.substring(i+5);
  top.frames[frame].location = '<?php echo "$web_root/interface/" ?>' + url;
  if (frame == 'RTop') topName = fname; else botName = fname;
 }

 // Load the specified url into a frame to be determined, with the specified
 // frame as the default; the url must be relative to interface.
 function loadFrame2(fname, frame, url) {
  var usage = fname.substring(3);
  if (active_pid == 0 && usage > '0') {
   alert('<?php xl('You must first select or add a patient.','e') ?>');
   return false;
  }
  if (active_encounter == 0 && usage > '1') {
   alert('<?php xl('You must first select or create an encounter.','e') ?>');
   return false;
  }
  var f = document.forms[0];
  top.restoreSession();
  var i = url.indexOf('{PID}');
  if (i >= 0) url = url.substring(0,i) + active_pid + url.substring(i+5);
  if(f.sel_frame)
   {
	  var fi = f.sel_frame.selectedIndex;
	  if (fi == 1) frame = 'RTop'; else if (fi == 2) frame = 'RBot';
   }
  if (!f.cb_bot.checked) frame = 'RTop'; else if (!f.cb_top.checked) frame = 'RBot';
  top.frames[frame].location = '<?php echo "$web_root/interface/" ?>' + url;
  if (frame == 'RTop') topName = fname; else botName = fname;
  return false;
 }

 // Make sure the the top and bottom frames are open or closed, as specified.
 function forceSpec(istop, isbot) {
  var f = document.forms[0];
  if (f.cb_top.checked != istop) {
   f.cb_top.checked = istop;
   toggleFrame(1);
  }
  if (f.cb_bot.checked != isbot) {
   f.cb_bot.checked = isbot;
   toggleFrame(2);
  }
 }

 // Make sure both frames are open.
 function forceDual() {
  forceSpec(true, true);
 }

 // Load the specified url into a frame to be determined, with the specified
 // frame as the default; the url must be relative to interface.
 function loadFrameDual(tname, bname, topurl, boturl) {
  var topusage = tname.substring(3);
  var botusage = bname.substring(3);
  if (active_pid == 0 && (topusage > '0' || botusage > '0')) {
   alert('<?php xl('You must first select or add a patient.','e') ?>');
   return false;
  }
  if (active_encounter == 0 && (topusage > '1' || botusage > '1')) {
   alert('<?php xl('You must first select or create an encounter.','e') ?>');
   return false;
  }
  var f = document.forms[0];
  forceDual();
  top.restoreSession();
  var i = topurl.indexOf('{PID}');
  if (i >= 0) topurl = topurl.substring(0,i) + active_pid + topurl.substring(i+5);
  i = boturl.indexOf('{PID}');
  if (i >= 0) boturl = boturl.substring(0,i) + active_pid + boturl.substring(i+5);
  top.frames.RTop.location = '<?php echo "$web_root/interface/" ?>' + topurl;
  top.frames.RBot.location = '<?php echo "$web_root/interface/" ?>' + boturl;
  topName = tname;
  botName = bname;
  return false;
 }

 // Select a designated radio button. raname may be either the radio button
 // array name (rb_top or rb_bot), or the frame name (RTop or RBot).
 // You should call this if you directly load a document that does not
 // correspond to the current radio button setting.
 function setRadio(raname, rbid) {
<?php if ($GLOBALS['concurrent_layout'] < 2) { ?>
  var f = document.forms[0];
  if (raname == 'RTop') raname = 'rb_top';
  if (raname == 'RBot') raname = 'rb_bot';
  for (var i = 0; i < f[raname].length; ++i) {
   if (f[raname][i].value.substring(0,3) == rbid) {
    f[raname][i].checked = true;
    return true;
   }
  }
<?php } ?>
  return false;
 }

 // Set disabled/enabled state of radio buttons and associated labels
 // depending on whether there is an active patient or encounter.
 function syncRadios() {
  var f = document.forms[0];
<?php if (($GLOBALS['concurrent_layout'] == 2)||($GLOBALS['concurrent_layout'] == 3)) { ?>
  var nlinks = document.links.length;
  for (var i = 0; i < nlinks; ++i) {
   var lnk = document.links[i];
   if (lnk.id.length != 4) continue;
   var usage = lnk.id.substring(3);
   if (usage == '1' || usage == '2') {
    var da = false;
    if (active_pid == 0) da = true;
    if (active_encounter == 0 && usage > '1') da = true;
    <?php
    if ($GLOBALS['concurrent_layout'] == 2){
      $color = "'#0000ff'";
    }else{
      $color = "'#000000'"; //this is the color of "summary, create visit, if patient created
    }  
    ?>
    lnk.style.color = da ? '#888888' : <?php echo $color; ?>;
   }
  }
<?php } else if ($GLOBALS['concurrent_layout'] < 2) { ?>
  for (var i = 0; i < f.rb_top.length; ++i) {
   var da = false;
   var rb1 = f.rb_top[i];
   var rb2 = f.rb_bot[i];
   var rbid = rb1.value.substring(0,3);
   var usage = rb1.value.substring(3);
   if (active_pid == 0 && usage > '0') da = true;
   if (active_encounter == 0 && usage > '1') da = true;
   // daemon_frame can also set special label colors, so don't mess with
   // them unless we have to.
   if (rb1.disabled != da) {
    rb1.disabled = da;
    rb2.disabled = da;
    document.getElementById('lbl_' + rbid).style.color = da ? '#888888' : '#000000';
   }
  }
<?php } ?>
  f.popups.disabled = (active_pid == 0);
 }

function goHome() {
    top.frames['RTop'].location='<?php echo $GLOBALS['default_top_pane']?>';
    top.frames['RBot'].location='messages/mailbox.php?form_active=1';
}

//Function to clear active patient and encounter in the server side
function clearactive() {
	top.restoreSession();
	//Ajax call to clear active patient in session
	$.ajax({
	  type: "POST",
	  url: "<?php echo $GLOBALS['webroot'] ?>/library/ajax/unset_session_ajax.php",
	  data: { func: "unset_pid"},
	  success:function( msg ) {
		clearPatient();
		top.frames['RTop'].location='<?php echo $GLOBALS['default_top_pane']?>';
		top.frames['RBot'].location='messages/mailbox.php?form_active=1';
	  }
	});
    
	$(parent.Title.document.getElementById('clear_active')).hide();
}
 // Reference to the search.php window.
 var my_window;

 // Open the search.php window.
 function initFilter() {
    my_window = window.open("../../custom/search.php", "mywindow","status=1");
 }

 // This is called by the search.php (Filter) window.
 function processFilter(fieldString, serviceCode) {
  var f = document.forms[0];
  document.getElementById('searchFields').value = fieldString;
  f.search_service_code.value = serviceCode;
  findPatient("Filter");
  f.search_service_code.value = '';
  my_window.close();
 }

 // Process the click to find a patient by name, id, ssn or dob.
 function findPatient(findby) {
  var f = document.forms[0];
  if (! f.cb_top.checked) {
   f.cb_top.checked = true;
   toggleFrame(1);
  }
  f.findBy.value = findby;
  setRadio('rb_top', 'dem');
  top.restoreSession();
  document.find_patient.submit();
 }

 // Helper function to set the contents of a div.
 function setSomeContent(id, content, doc) {
  if (doc.getElementById) {
   var x = doc.getElementById(id);
   x.innerHTML = '';
   x.innerHTML = content;
  }
  else if (doc.all) {
   var x = doc.all[id];
   x.innerHTML = content;
  }
 }
 function setDivContent(id, content) {
  setSomeContent(id, content, document);
 }
 function setTitleContent(id, content) {
  setSomeContent(id, content, parent.Title.document);
 }

 // This is called automatically when a new patient is set, to make sure
 // there are no patient-specific documents showing stale data.  If a frame
 // was just loaded with data for the correct patient, its name is passed so
 // that it will not be zapped.  At this point the new server-side pid is not
 // assumed to be set, so this function will only load global data.
 function reloadPatient(frname) {
  var f = document.forms[0];
  if (topName.length > 3 && topName.substring(3) > '0' && frname != 'RTop') {
   loadFrame('cal0','RTop', '<?php echo $primary_docs['cal'][2]; ?>');
   setRadio('rb_top', 'cal');
  }
  if (botName.length > 3 && botName.substring(3) > '0' && frname != 'RBot') {
   loadFrame('ens0','RBot', '<?php echo $primary_docs['ens'][2]; ?>');
   setRadio('rb_bot', 'ens');
  }
 }

 // Reload encounter-specific frames, excluding a specified frame.  At this
 // point the new server-side encounter ID may not be set and loading the same
 // document for the new encounter will not work, so load patient info instead.
 function reloadEncounter(frname) {
  var f = document.forms[0];
  if (topName.length > 3 && topName.substring(3) > '1' && frname != 'RTop') {
   loadFrame('dem1','RTop', '<?php echo $primary_docs['dem'][2]; ?>');
   setRadio('rb_top', 'dem');
  }
  if (botName.length > 3 && botName.substring(3) > '1' && frname != 'RBot') {
   loadFrame('ens1','RBot', '<?php echo $primary_docs['ens'][2]; ?>');
   setRadio('rb_bot', 'ens');
  }
 }

 // Clear and reload issue-related menu items for active_pid.
 // Currently this only applies to athletic teams, but might be implemented
 // in the general menu at some future time.
 //
 function reloadIssues() {
<?php
  if ($GLOBALS['athletic_team']) {
    // Generates a menu item for each active issue that this patient
    // has of each issue type.  Each one looks like this:
    //   Onset-Date [Add] Issue-Title
    // where the first part is a link to open the issue dialog,
    // [Add] is a link that auto-creates and opens a new encounter, and
    // Issue-Title is a link that shows related encounters.
    foreach ($ISSUE_TYPES as $key => $value) {
?>
  $('#icontainer_<?php echo $key ?>').empty();
  if (active_pid != 0) {
   $('#icontainer_<?php echo $key ?>').append("<li>" +
    "<a href='' id='xxx1' onclick='return repPopup(" +
    "\"../patient_file/summary/add_edit_issue.php?thistype=" +
    "<?php echo $key; ?>\")' " +
    "title='<?php echo xl('Create new issue'); ?>'>" +
    "<?php echo xl('New') . " " . $value[1]; ?></a></li>");
   top.restoreSession();
   $.getScript('../../library/ajax/left_nav_issues_ajax.php?type=<?php echo $key; ?>');
  }
<?php
    }
  }
?>
 } // end function reloadIssues

 // This is referenced in left_nav_issues_ajax.php and is called when [Add]
 // is clicked for an issue menu item to add a new encounter for the issue.
 // So far this only applies to the Athletic Team version of the menu.
 //
 function addEncNotes(issue) {

  // top.restoreSession();
  // $.getScript('../../library/ajax/left_nav_encounter_ajax.php?createvisit=1&issue=' + issue);

  // The above AJAX call was to create the encounter right away, but we later
  // (2012-07-03) decided it's better to present the New Encounter form instead.
  // Note the issue ID is passed so it will be pre-selected in that form.
  loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=&issue=' + issue);

  return false;
 }

 // Call this to announce that the patient has changed.  You must call this
 // if you change the session PID, so that the navigation frame will show the
 // correct patient and so that the other frame will be reloaded if it contains
 // patient-specific information from the previous patient.  frname is the name
 // of the frame that the call came from, so we know to only reload content
 // from the *other* frame if it is patient-specific.
 function setPatient(pname, pid, pubpid, frname, str_dob) {
  var str = '<a href=\'javascript:;\' onclick="parent.left_nav.loadCurrentPatientFromTitle()" title="PID = ' + pid + '"><b>' + pname + ' (' + pubpid + ')<br /></b></a>';
  setDivContent('current_patient', str);
  setTitleContent('current_patient', str + str_dob);
  if (pid == active_pid) return;
  setDivContent('current_encounter', '<b><?php xl('None','e'); ?></b>');
  active_pid = pid;
  active_encounter = 0;
  if (frname) reloadPatient(frname);
  syncRadios();
  $(parent.Title.document.getElementById('current_patient_block')).show();
  var encounter_block = $(parent.Title.document.getElementById('current_encounter_block'));
  $(encounter_block).hide();

  // zero out the encounter frame, replace it with the encounter list frame
  var f = document.forms[0];
  if ( f.cb_top.checked && f.cb_bot.checked ) {
      var encounter_frame = getEncounterTargetFrame('enc');
      if ( encounter_frame != undefined )  {
          loadFrame('ens0',encounter_frame, '<?php echo $primary_docs['ens'][2]; ?>');
          setRadio(encounter_frame, 'ens');
      }
  }

  reloadIssues(pid);
  $(parent.Title.document.getElementById('clear_active')).show();//To display Clear Active Patient button on selecting a patient
 }
 function setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray) {
 //This function lists all encounters of the patient.
 //This function writes the drop down in the top frame.
 //It is called when a new patient is create/selected from the search menu.
  var str = '<Select class="text" id="EncounterHistory" onchange="{top.restoreSession();toencounter(this.options[this.selectedIndex].value)}">';
  str+='<option value=""><?php echo htmlspecialchars( xl('Encounter History'), ENT_QUOTES) ?></option>';
  str+='<option value="New Encounter"><?php echo htmlspecialchars( xl('New Encounter'), ENT_QUOTES) ?></option>';
  str+='<option value="Past Encounter List"><?php echo htmlspecialchars( xl('Past Encounter List'), ENT_QUOTES) ?></option>';
  for(CountEncounter=0;CountEncounter<EncounterDateArray.length;CountEncounter++)
   {
    str+='<option value="'+EncounterIdArray[CountEncounter]+'~'+EncounterDateArray[CountEncounter]+'">'+EncounterDateArray[CountEncounter]+'-'+CalendarCategoryArray[CountEncounter]+'</option>';
   }
  str+='</Select>';
  $(parent.Title.document.getElementById('past_encounter_block')).show();
  top.window.parent.Title.document.getElementById('past_encounter').innerHTML=str;
 }

function loadCurrentPatientFromTitle() {
    top.restoreSession();
    top.frames['RTop'].location='../patient_file/summary/demographics.php';
}

function getEncounterTargetFrame( name ) {
    var bias = <?php echo $primary_docs[ 'enc'  ][ 1 ]?>;
    var f = document.forms[0];
    var r = 'RTop';
    if (f.cb_top.checked && f.cb_bot.checked) {
        if ( bias == 2 ) {
            r = 'RBot';
        } else {
            r = 'RTop';
        }
    } else {
        if ( f.cb_top.checked ) {
            r = 'RTop';
        } else if ( f.cb_bot.checked )  {
            r = 'RBot';
        }
    }
    return r;
}

 // Call this to announce that the encounter has changed.  You must call this
 // if you change the session encounter, so that the navigation frame will
 // show the correct encounter and so that the other frame will be reloaded if
 // it contains encounter-specific information from the previous encounter.
 // frname is the name of the frame that the call came from, so we know to only
 // reload encounter-specific content from the *other* frame.
 function setEncounter(edate, eid, frname) {
  if (eid == active_encounter) return;
  if (!eid) edate = '<?php xl('None','e'); ?>';
  var str = '<b>' + edate + '</b>';
  setDivContent('current_encounter', str);
  active_encounter = eid;
  reloadEncounter(frname);
  syncRadios();
  var encounter_block = $(parent.Title.document.getElementById('current_encounter_block'));
  var encounter = $(parent.Title.document.getElementById('current_encounter'));
  var estr = '<a href=\'javascript:;\' onclick="parent.left_nav.loadCurrentEncounterFromTitle()"><b>' + edate + ' (' + eid + ')</b></a>';
  encounter.html( estr );
  encounter_block.show();
 }

 function loadCurrentEncounterFromTitle() {
      top.restoreSession();
      top.frames[ parent.left_nav.getEncounterTargetFrame('enc') ].location='../patient_file/encounter/encounter_top.php';
 }

 // You must call this if you delete the active patient (or if for any other
 // reason you "close" the active patient without opening a new one), so that
 // the appearance of the navigation frame will be correct and so that any
 // stale content will be reloaded.
 function clearPatient() {
  if (active_pid == 0) return;
  var f = document.forms[0];
  active_pid = 0;
  active_encounter = 0;
  setDivContent('current_patient', '<b><?php xl('None','e'); ?></b>');
  $(parent.Title.document.getElementById('current_patient_block')).hide();
  top.window.parent.Title.document.getElementById('past_encounter').innerHTML='';
  $(parent.Title.document.getElementById('current_encounter_block')).hide();
  reloadPatient('');
  syncRadios();
 }

 // You must call this if you delete the active encounter (or if for any other
 // reason you "close" the active encounter without opening a new one), so that
 // the appearance of the navigation frame will be correct and so that any
 // stale content will be reloaded.
 function clearEncounter() {
  if (active_encounter == 0) return;
  top.window.parent.Title.document.getElementById('current_encounter').innerHTML="<b><?php echo htmlspecialchars( xl('None'), ENT_QUOTES) ?></b>";
  active_encounter = 0;
  reloadEncounter('');
  syncRadios();
 }
function removeOptionSelected(EncounterId)
{//Removes an item from the Encounter drop down.
	var elSel = top.window.parent.Title.document.getElementById('EncounterHistory');
	var i;
	for (i = elSel.length - 1; i>=2; i--) {
	 EncounterHistoryValue=elSel.options[i].value;
	 EncounterHistoryValueArray=EncounterHistoryValue.split('~');
		if (EncounterHistoryValueArray[0]==EncounterId) {
			elSel.remove(i);
		}
	}
}

 // You can call this to make sure the session pid is what we expect.
 function pidSanityCheck(pid) {
  if (pid != active_pid) {
   alert('Session patient ID is ' + pid + ', expecting ' + active_pid +
    '. This session is unstable and should be abandoned. Do not use ' +
    'OpenEMR in multiple browser windows!');
   return false;
  }
  return true;
 }

 // You can call this to make sure the session encounter is what we expect.
 function encounterSanityCheck(eid) {
  if (eid != active_encounter) {
   alert('Session encounter ID is ' + eid + ', expecting ' + active_encounter +
    '. This session is unstable and should be abandoned. Do not use ' +
    'OpenEMR in multiple browser windows!');
   return false;
  }
  return true;
 }

 // Pop up a report.
 function repPopup(aurl) {
  top.restoreSession();
  window.open('<?php echo "$web_root/interface/reports/" ?>' + aurl, '_blank', 'width=750,height=550,resizable=1,scrollbars=1');
  return false;
 }

 // This is invoked to pop up some window when a popup item is selected.
 function selpopup(selobj) {
  var i = selobj.selectedIndex;
  var opt = selobj.options[i];
  if (i > 0) {
   var width  = 750;
   var height = 550;
   if (opt.text == 'Export' || opt.text == 'Import') {
    width  = 500;
    height = 400;
   }
   else if (opt.text == 'Refer') {
    width  = 700;
    height = 500;
   }
   dlgopen(opt.value, '_blank', width, height);
  }
  selobj.selectedIndex = 0;
 }
// Treeview activation stuff:
$(document).ready(function(){
	$('input').attr('checked', false);  // This sentence and the following are used to unchecked bot as default
    toggleFrame(2);    
  if(3 == <?php echo $GLOBALS['concurrent_layout'] ?>){
    $("#navigation-slide > li > a.collapsed + ul").slideToggle("medium");
    $("#navigation-slide > li > ul > li > a.collapsed_lv2 + ul").slideToggle("medium");
    $("#navigation-slide > li > a.expanded").click(function() {
      $("#navigation-slide > li > a.expanded").not(this).toggleClass("expanded").toggleClass("collapsed").parent().find('> ul').slideToggle("medium");
      $(this).toggleClass("expanded").toggleClass("collapsed").parent().find('> ul').slideToggle("medium");
    });
    $("#navigation-slide > li > a.collapsed").click(function() {
      $("#navigation-slide > li > a.expanded").not(this).toggleClass("expanded").toggleClass("collapsed").parent().find('> ul').slideToggle("medium");
      $(this).toggleClass("expanded").toggleClass("collapsed").parent().find('> ul').slideToggle("medium");
    });
    $("#navigation-slide > li  > ul > li > a.expanded_lv2").click(function() {
      $("#navigation-slide > li > a.expanded").next("ul").find("li > a.expanded_lv2").not(this).toggleClass("expanded_lv2").toggleClass("collapsed_lv2").parent().find('> ul').slideToggle("medium");
      $(this).toggleClass("expanded_lv2").toggleClass("collapsed_lv2").parent().find('> ul').slideToggle("medium");
    });
    $("#navigation-slide > li  > ul > li > a.collapsed_lv2").click(function() {
      $("#navigation-slide > li > a.expanded").next("ul").find("li > a.expanded_lv2").not(this).toggleClass("expanded_lv2").toggleClass("collapsed_lv2").parent().find('> ul').slideToggle("medium");
      $(this).toggleClass("expanded_lv2").toggleClass("collapsed_lv2").parent().find('> ul').slideToggle("medium");
    });
    $("#navigation-slide > li  > a#cal0").prepend('<img src="../../images/calendar.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#msg0").prepend('<img src="../../images/messages.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#patimg").prepend('<img src="../../images/patient.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#app0").prepend('<img src="../../images/patient.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#repimg").prepend('<img src="../../images/reports.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#feeimg").prepend('<img src="../../images/fee.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#adm0").prepend('<img src="../../images/inventory.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#invimg").prepend('<img src="../../images/inventory.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#admimg").prepend('<img src="../../images/admin.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#misimg").prepend('<img src="../../images/misc.png" class="nav-menu-img" />');
    $("#navigation-slide > li  > a#proimg").prepend('<img src="../../images/procedures.png" class="nav-menu-img" />');
    $("#navigation-slide > li").each(function(index) {
      if($(" > ul > li", this).size() == 0){
        $(" > a", this).addClass("collapsed");
      }
    });
  }else if(2 == <?php echo $GLOBALS['concurrent_layout'] ?>){

    //Remove the links (used by the sliding menu) that will break treeview
    $('a.collapsed').each(function() { $(this).replaceWith('<span>'+$(this).text()+'</span>'); });
    $('a.collapsed_lv2').each(function() { $(this).replaceWith('<span>'+$(this).text()+'</span>'); });
    $('a.expanded').each(function() { $(this).replaceWith('<span>'+$(this).text()+'</span>'); });
    $('a.expanded_lv2').each(function() { $(this).replaceWith('<span>'+$(this).text()+'</span>'); });

    // Initiate treeview
    $("#navigation").treeview({
     animated: "fast",
     collapsed: true,
     unique: <?php echo $GLOBALS['athletic_team'] ? 'false' : 'true' ?>,
     toggle: function() {
      window.console && console.log("%o was toggled", this);
     }
    });
  }
});

</script>

    <!-- Bootstrap core CSS -->
    <link href="../themes/dist/css/eacemr.css" rel="stylesheet">
    <link href="../themes/dist/css/leftnav.css" rel="stylesheet">


    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../themes/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../themes/assets/js/ie10-viewport-bug-workaround.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="#">Scheduler</a></li>
            <li><a href="#">Messages</a></li>
            <li><?php genMiscLink('RTop','fin','0',xl('Patients'),'main/finder/dynamic_finder.php'); ?></li>
            <li><a href="#">Procedures</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Reports</a></li>
            <li><a href="">Administration</a></li>
            <li><a href=""></a></li>
            <li><a href=""></a></li>
            <li><a href=""></a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href=""></a></li>
            <li><a href=""></a></li>
            <li><a href=""></a></li>
          </ul>
        </div>
      </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../themes/dist/js/bootstrap.min.js"></script>
    <script src="../themes/assets/js/docs.min.js"></script>

<script language='JavaScript'>
syncRadios();
</script>

</body>
</html>
