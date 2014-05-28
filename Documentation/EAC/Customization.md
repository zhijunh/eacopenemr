Welcome to [EAC OpenEMR](http://ec2-54-186-238-219.us-west-2.compute.amazonaws.com/openemr)!!!
This is the record of customization did to openemr.
=====================
  
1. Change Login interface pic: C:\xampp\htdocs\openemr\interface\pic\logo.gif and logo.jpg
2. Change favicon.ico C:\xampp\htdocs\favicon.ico
3. Add theme "style_EAC_1.css" and "style_EAC_2.css" C:\xampp\htdocs\openemr\interface\themes  
3.1 Change navigation slide style (color;font color)  
3.2 Add hover function  
3.3 Hide "Reports" to users except administrator  
3.4 Add "message.php" style change at bottom  
3.4.1 Changed link color: ".body_title .text a"; "#showMenuLink;"  
3.4.2 Add class "body_title_2" to "login_title.php"; Change title background color:  ".body_title, .body_title_2"  
3.4.3 Make class "link" bold: ".link"  
3.4.4 Change the body background color to transparent so that the message page will not have white border: ".body_top"  
3.4.5 Remove sorting color
4. Change the dynamic_patient display (font size; hover; background color): "demo_table.css"
5. Change the "message" page direction: "left_nav.php","main_screen.php"
6. Set up mailbox; separate mailbox CSS file: "mailbox.php", "mailbox.css"
7. Change "message.php" content  
7.1 delete "message and reminder center" and "Reminder" title  
7.2 Give class names, shift it into style_eac_2.css
8. Change "left_nav.php"  
8.1 Hide "Reports" to users except admin

