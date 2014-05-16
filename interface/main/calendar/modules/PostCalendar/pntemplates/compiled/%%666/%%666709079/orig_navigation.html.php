<?php /* Smarty version 2.3.1, created on 2014-05-08 22:16:37
         compiled from default/views/global/orig_navigation.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'pc_form_nav_open', 'default/views/global/orig_navigation.html', 21, false),
array('function', 'pc_filter', 'default/views/global/orig_navigation.html', 26, false),
array('function', 'pc_date_select', 'default/views/global/orig_navigation.html', 29, false),
array('function', 'pc_url', 'default/views/global/orig_navigation.html', 40, false),)); ?>
<?php $this->_config_load("navigation.conf", null, 'local'); ?>

<?php $this->_config_load("lang.$USER_LANG", null, 'local'); ?>
<style type="text/css">
.bnav 			{ border: 1px solid #000000; }
td.nav  		{ border-right: 1px outset <?php echo $this->_tpl_vars['BGCOLOR3']; ?>
; background-color:<?php echo $this->_tpl_vars['BGCOLOR3']; ?>
; }
td.nav:hover 	{ border-right: 1px outset <?php echo $this->_tpl_vars['BGCOLOR3']; ?>
; background-color:<?php echo $this->_tpl_vars['BGCOLOR2']; ?>
; }
td.rnav 		{ background-color:<?php echo $this->_tpl_vars['BGCOLOR3']; ?>
; }
td.rnav:hover 	{ background-color:<?php echo $this->_tpl_vars['BGCOLOR2']; ?>
; }
td.snav 		{ border-right: 1px outset <?php echo $this->_tpl_vars['BGCOLOR3']; ?>
; background-color:<?php echo $this->_tpl_vars['BGCOLOR1']; ?>
; font-weight : bold; }
td.rsnav 		{ background-color:<?php echo $this->_tpl_vars['BGCOLOR1']; ?>
; font-weight : bold; }
a.nav:link  	{ padding-right: 3px; padding-left: 3px; color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; text-decoration: none; }
a.nav:active 	{ padding-right: 3px; padding-left: 3px; color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; text-decoration: none; }
a.nav:hover 	{ padding-right: 3px; padding-left: 3px; color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; text-decoration: none; }
a.nav:visited 	{ padding-right: 3px; padding-left: 3px; color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; text-decoration: none; }
SELECT.nav  	{ color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; background-color:<?php echo $this->_tpl_vars['BGCOLOR1']; ?>
; }
OPTION.nav  	{ color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; background-color:<?php echo $this->_tpl_vars['BGCOLOR1']; ?>
; }
INPUT.nav 		{ color: <?php echo $this->_tpl_vars['TEXTCOLOR1']; ?>
; background-color:<?php echo $this->_tpl_vars['BGCOLOR1']; ?>
; }
</style>
<?php $this->_plugins['function']['pc_form_nav_open'][0](array(), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<!-- main navigation -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td nowrap valign="bottom" align="left">
		 	<?php $this->_plugins['function']['pc_filter'][0](array('label' => $this->_config[0]['vars']['_PC_FILTER_LABEL'],'class' => "",'type' => "user,category,topic",'order' => "user,category,topic,jump"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
			<?php $this->_plugins['function']['pc_date_select'][0](array('day' => "on",'month' => "on",'year' => "on",'view' => "on",'label' => $this->_config[0]['vars']['_PC_JUMP_MENU_LABEL'],'order' => "month,day,year,view,jump"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>


</td>
		<td width="100%" valign="bottom" align="right">
            <table id="bnav" class="bnav" border="0" cellpadding="3" cellspacing="0" bgcolor="<?php echo $this->_config[0]['vars']['TABLE_BGCOLOR']; ?>
">
                <tr>
                    <td nowrap align="center" valign="bottom" <?php if ($this->_tpl_vars['VIEW_TYPE'] == "day"): ?>class="snav"<?php else: ?>class="nav"<?php endif; ?>>
                        <a class="nav" href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "day"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" onclick="top.restoreSession()"><?php  xl('Day','e');  ?></a>
                    </td>
                    <td nowrap align="center" valign="bottom" <?php if ($this->_tpl_vars['VIEW_TYPE'] == "week"): ?>class="snav"<?php else: ?>class="nav"<?php endif; ?>>
                        <a class="nav" href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "week"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" onclick="top.restoreSession()"><?php  xl('week','e');  ?></a>
                    </td>
                    <td nowrap align="center" valign="bottom" <?php if ($this->_tpl_vars['VIEW_TYPE'] == "month"): ?>class="snav"<?php else: ?>class="nav"<?php endif; ?>>
                        <a class="nav" href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "month"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" onclick="top.restoreSession()"><?php  xl('Month','e');  ?></a>
                    </td>
                    <td nowrap align="center" valign="bottom" <?php if ($this->_tpl_vars['VIEW_TYPE'] == "year"): ?>class="snav"<?php else: ?>class="nav"<?php endif; ?>>
                        <a class="nav" href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "year"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" onclick="top.restoreSession()"><?php  xl('Year','e');  ?></a>
                    </td>
					<?php if ($this->_tpl_vars['ACCESS_ADD'] == true): ?>
                 	<td nowrap align="center" valign="bottom" <?php if ($this->_tpl_vars['FUNCTION'] == "submit"): ?>class="snav"<?php else: ?>class="nav"<?php endif; ?>>
					<?php 
						$todayh = date("H");
					 ?>
                        <a class="nav" href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "submit",'setdeftime' => "1"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" onclick="top.restoreSession()"><?php  xl('Add','e');  ?></a>
                    </td>
					<?php endif; ?>
                    <td nowrap align="center" valign="bottom" <?php if ($this->_tpl_vars['FUNCTION'] == "search"): ?>class="rsnav"<?php else: ?>class="rnav"<?php endif; ?>>
                        <a class="nav" href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "search"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" onclick="top.restoreSession()"><?php  xl('Search','e');  ?></a>
                    </td>
				</tr>
           </table>
        </td>
	</tr>
</table>