<html>
<head>
<link rel="stylesheet" href="messages.css" type="text/css">
<style type="text/css">
.leftnav{
    font-size:12px;
    text-decoration:none;
    font-family:sans-serif;
}
.navtable{
    color:#000000;
    width:100%;
    height:auto;
    padding-left:20px;
    margin-top:50px;
    line-height:30px;

}
.navtable a {
    color:#000000;
}
.navtr{
    background-color:#F1F1F1;
/*    border-right-style:solid ;
    border-right-width:thin;
    
    border-bottom:none;
    border-left:none;
    border-top:none;
    border-color:#838383;*/
    -moz-box-shadow: inset -5px -3px 15px #c4c4c4;
    -webkit-box-shadow: inset -5px -3px 15px #c4c4c4;
    box-shadow: inset -5px -3px 15px #c4c4c4;    
}

.border{
    /*border:0px solid #EEEEEE;*/
    border-radius:10px 10px 0px 0px;
    border-bottom:none;
}
.borderright{
    /*border:0px solid #EEEEEE;*/
/*    border-radius:0px 10px 0px 0px;
    border-bottom:none;*/
}
.topbar{
    background-color:lightgrey;
}
</style>
<script type="text/javascript">
function pagesubmit(title){
    document.getElementById('tittle').value = title;
    document.getElementById('mailboxform').submit();
}
</script>
</head>

<body class="body_top">
    <form id="mailboxform" name="mailboxform" method="POST" action="">
    <!--Wrapper Table Begins-->
    <table id="wrappertable" border="0" cellpadding="0" cellspacing="0" style="width:100%;height:100%;background-color:#FFFFFF;" align="center">
        <tr style="width:100%;height:70px;">
            <td colspan="2">
                <!--Header Table Begins-->
                <table id="headertable" border="0" cellpadding="0" cellspacing="0" style="width:100%;height:70px;font-size:15px;" >
                    <tr style="line-height:12px;">
<!--                        <td class="border topbar">
                            &nbsp;
                        </td>-->
                        <td class="border topbar" align="center" colspan="2">
                            Inbox                        </td>
                        <td>
                            &nbsp;
                        </td>                        
                    </tr>
                    <tr>
                        <td class="topbar" width="2%">&nbsp;
                        </td>
                        <td class="topbar" width="12%" >
                        </td>
                        <td class="topbar">
                        </td>                        
                    </tr>
                </table><!--Header Table Ends Here-->
            </td>
        </tr>
        <tr style="width:100%;height:100%;" align="center" >
            <td style="width:15%;height:100%;vertical-align:top;" align="center" class="navtr">
                <!--Nav Table Begins-->                
                <table  id="navtable" border="0" cellpadding="0" cellspacing="0" class="navtable" >
                    <tr>
                        <td><input type="button" id="compose" name="compose" onclick="pagesubmit('Compose')" value="Compose"/>
                        </td>
                    </tr>
                    <tr>
                        <td><a class="leftnav" href="#" onclick="pagesubmit('Inbox');">Inbox</a></td>
                    </tr>
                    <tr>
                        <td><a class="leftnav" href="#"  onclick="pagesubmit('Sent Items')">Sent Items</a></td>
                    </tr>                   
                    <tr>
                        <td><a class="leftnav" href="#"  onclick="pagesubmit('Audit Encounter')">Audit Encounter</a></td>
                    </tr>
                    <tr>
                        <td><a class="leftnav" href="#"  onclick="pagesubmit('Audited Encounter')">Audited Encounter</a></td>
                    </tr>                      
                </table><!--Nav Table Ends Here-->
            </td>
            <td style="width:85%;height:100%;background-color:#F0F0F0;border-collapse:collapse;vertical-align:top;" align="left">
                <!--Content Begins-->
                <input type="hidden" id="tittle" name="tittle" value=""/>
                                <iframe src="messages.php" frameborder="0" height="95%" width="100%" style="font-size:12px;padding-left:2px;padding-top:2px;"></iframe> 
                <!--Content Ends Here-->
            </td>  
        </tr>
    </table><!--wrappertable Ends Here-->
    </form>
</body>
</html>
