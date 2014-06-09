Welcome to [EAC OpenEMR](http://ec2-54-186-238-219.us-west-2.compute.amazonaws.com/openemr)!!!
=====================

EAC openemr is a UF EAC Open Source electronic health records and medical practice management application.  
-----05/08/2014-------  
  
1. Change Login interface pic: C:\xampp\htdocs\openemr\interface\pic\logo.gif and logo.jpg
2. Change favicon.ico C:\xampp\htdocs\favicon.ico
3. Add theme "style_EqualAccess.css" C:\xampp\htdocs\openemr\interface\themes
4. Navigation Slide Change when activated C:\xampp\htdocs\openemr\interface\main\left_nav.php    
```
$color = "'#ffffff'"; // this is the color of "summary, create visit, if patient created
```
5. Navigation slide change C:\xampp\htdocs\openemr\interface\themes\style_EqualAccess.css  
```
    .body_title { background-color: #ffffff; }  /* $title_bg_line */
```   
```
.body_nav { background-color: #ffffff; }    /* $nav_bg_line */
```    
```
    #navigation-slide a{
    background: #1175b5;
    border-top: 2px solid #EAE6FF;
    color: #fff;
    display: block;
    font-weight: bold;
    padding: 5px 2px 5px 10px;
    width: 150px;
    }

    #navigation-slide li a.collapsed{
    background: #999; /* for non-css3 browsers */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#014c85', endColorstr='#014c85'); /* for IE */
    background: -webkit-gradient(linear, left top, left bottom, from(#014c85), to(#014c85)); /* for webkit browsers */
    background: -moz-linear-gradient(top,  #014c85,  #014c85); /* for firefox 3.6+ */
    }

    #navigation-slide li a.expanded{
    background: #999; /* for non-css3 browsers */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#014c85', endColorstr='#014c85'); /* for IE */
    background: -webkit-gradient(linear, left top, left bottom, from(#014c85), to(#014c85)); /* for webkit browsers */
    background: -moz-linear-gradient(top,  #014c85,  #014c85); /* for firefox 3.6+ */
    }

    #navigation-slide ul li a.collapsed_lv2{
    background: #999; /* for non-css3 browsers */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8cc6ff', endColorstr='#8cc6ff'); /* for IE */
    background: -webkit-gradient(linear, left top, left bottom, from(#8cc6ff), to(#8cc6ff)); /* for webkit browsers */
    background: -moz-linear-gradient(top,  #8cc6ff,  #8cc6ff); /* for firefox 3.6+ */
    }

    #navigation-slide ul li a.expanded_lv2{
    background: #999; /* for non-css3 browsers */
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8cc6ff', endColorstr='#8cc6ff'); /* for IE */
    background: -webkit-gradient(linear, left top, left bottom, from(#8cc6ff), to(#8cc6ff)); /* for webkit browsers */
    background: -moz-linear-gradient(top,  #8cc6ff,  #8cc6ff); /* for firefox 3.6+ */
    }
```

6. Revise patient finder form background color C:\xampp\htdocs\openemr\library\js\datatables\media\css\demo_table.css
```
    tr.odd {background-color: #FFFFFF;
    tr.even {background-color: white;
    }  
```  
```
    tr.odd td.sorting_1 {background-color: #FFFFFF;
    }

    tr.odd td.sorting_2 {background-color: #FFFFFF;
    }

    tr.odd td.sorting_3 {background-color: #FFFFFF;
    }

    tr.even td.sorting_1 {background-color: #FFFFFF;
    }

    tr.even td.sorting_2 {background-color: #FFFFFF;
    }

    tr.even td.sorting_3 {background-color: #FFFFFF;
```  
-----05/20/2014-------  
1. Change back the "summary" font color in navigation slides  
```
 $color = "'#000000'"; //this is the color of "summary, create visit, if patient created
```  
2. Change "style_EqualAccess.css" to "style_EAC_1.css"  
3. Change the font size; add hover function; remove sorting color  
   library/js/datatables/media/css/demo_table.css  
```
 table.display td {
 	padding: 3px 10px;
	font-size:13px
```  
```
/* 
 * Sorting classes for columns   disabled  
 */
```  
```
/*
 * Hover, change color
 */
 tr:hover{
	background: #09C;
	cursor: hand;
	color: #fff;
	font-weight:bold
}
```  
4. Add new files "interface/main/messages/mailbox.php" "interface/themes/style_EAC_2.css"
5. Change the "message" page direction  
interface/main/left_nav.php  
```
'msg' => array(xl('Messages')  , 0, 'main/messages/mailbox.php?form_active=1'),
```  
```
top.frames['RBot'].location='messages/mailbox.php?form_active=1';
```
```
top.frames['RBot'].location='messages/mailbox.php?form_active=1';
```
```
$('input').attr('checked', true);  // This sentence and the following are used to unchecked bot as default
```  
interface/main/main_screen.php  
```
<frame src='messages/mailbox.php?form_active=1' name='RBot' scrolling='auto' />
```
```
<frame src='messages/mailbox.php?form_active=1' name='RBot' scrolling='auto' />
```  
-----05/21/2014-------  
1. Create new mailbox  
interface/main/messages/mailbox.php
2. Separate mailbox CSS file  
