<?php
/**
 * main_title.php - The main titlebar, at the top of the 'concurrent' layout.
 */

include_once('../globals.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Title Bar">
    <meta name="author" content="Jason">

    <title>Fixed Top Navbar for Openemr</title>
    
    <!-- OpenEMR core CSS -->
    <link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
    <style type="text/css">
      .hidden {
        display:none;
      }
      .visible{
        display:block;
      }
    </style>

    <!-- Bootstrap core CSS -->
    <link href="../themes/dist/css/eacemr.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="navbar-fixed-top.css" rel="stylesheet">

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
    
    <script type="text/javascript" language="javascript">
    function toencounter(rawdata) {
    //This is called in the on change event of the Encounter list.
    //It opens the corresponding pages.
	document.getElementById('EncounterHistory').selectedIndex=0;
	if(rawdata=='')
	 {
		 return false;
	 }
	else if(rawdata=='New Encounter')
	 {
	 	top.window.parent.left_nav.loadFrame2('nen1','RBot','forms/newpatient/new.php?autoloaded=1&calenc=')
		return true;
	 }
	else if(rawdata=='Past Encounter List')
	 {
	 	top.window.parent.left_nav.loadFrame2('pel1','RBot','patient_file/history/encounters.php')
		return true;
	 }
    var parts = rawdata.split("~");
    var enc = parts[0];
    var datestr = parts[1];
    var f = top.window.parent.left_nav.document.forms[0];
	frame = 'RBot';
    if (!f.cb_bot.checked) frame = 'RTop'; else if (!f.cb_top.checked) frame = 'RBot';

    top.restoreSession();
    <?php if ($GLOBALS['concurrent_layout']) { ?>
    parent.left_nav.setEncounter(datestr, enc, frame);
    parent.left_nav.setRadio(frame, 'enc');
    top.frames[frame].location.href  = '../patient_file/encounter/encounter_top.php?set_encounter=' + enc;
    <?php } else { ?>
    top.Title.location.href = '../patient_file/encounter/encounter_title.php?set_encounter='   + enc;
    top.Main.location.href  = '../patient_file/encounter/patient_encounter.php?set_encounter=' + enc;
    <?php } ?>
    }
    function showhideMenu() {
	var m = parent.document.getElementById("sidebar");
	var targetdisplay = 'none';
	var m2 = parent.document.getElementById("hide");
	if (m.style.display == targetdisplay) {
		m.style.display = 'block';
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars( xl('Hide Menu'), ENT_QUOTES); ?>';
	} else {
		m.style.display = targetdisplay;
		document.getElementById("showMenuLink").innerHTML = '<?php echo htmlspecialchars( xl('Show Menu'), ENT_QUOTES); ?>';
	}
    }
    </script>
  </head>

  <body>
  <?php
  $res = sqlQuery("select * from users where username='".$_SESSION{"authUser"}."'");
  ?>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation" >
      <div class="container">
        <div class="navbar-header">
        <button type="button" class="btn btn-primary btn-lg" onclick='javascript:showhideMenu();return false;'><span class="glyphicon glyphicon-tasks"></span></button>
        </div>
        <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">EAC EMR</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">New Patient</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown" >
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="../navbar/">Default</a></li>
            <li><a href="../navbar-static-top/">Static top</a></li>
            <li class="active"><a href="./">Fixed top</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../themes/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript">
    parent.loadedFrameCount += 1;
    </script>
  </body>
</html>
