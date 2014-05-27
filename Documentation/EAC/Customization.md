Welcome to [EAC OpenEMR](http://ec2-54-186-238-219.us-west-2.compute.amazonaws.com/openemr)!!!
=====================
-----05/08/2014-------  
  
1. Change Login interface pic: C:\xampp\htdocs\openemr\interface\pic\logo.gif and logo.jpg
2. Change favicon.ico C:\xampp\htdocs\favicon.ico
3. Add theme "style_EqualAccess.css" C:\xampp\htdocs\openemr\interface\themes
4. Navigation Slide Change when activated C:\xampp\htdocs\openemr\interface\main\left_nav.php  
line 530  
```
$color = "'#ffffff'"; // this is the color of "summary, create visit, if patient created
```
5. Navigation slide change C:\xampp\htdocs\openemr\interface\themes\style_EqualAccess.css  
line 169
```
    .body_title { background-color: #ffffff; }  /* $title_bg_line */
```
line 170  
```
.body_nav { background-color: #ffffff; }    /* $nav_bg_line */
```  
line 872  
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

6. Revise Patient Finder Background C:\xampp\htdocs\openemr\library\js\datatables\media\css\demo_table.css  
line 247  
```
    tr.odd {background-color: #FFFFFF;
    tr.even {background-color: white;
    }  
```
line 366  
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