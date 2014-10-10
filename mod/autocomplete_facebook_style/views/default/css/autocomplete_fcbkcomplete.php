<?php
/**
 *	Autocomplete Facebook Style Plugin
 *	@package autocomplete facebook style
 *	@author Liran Tal <liran.tal@gmail.com>
 *	@license GNU General Public License (GPL) version 2
 *	@copyright (c) Liran Tal of Enginx 2009
 *	@link http://www.enginx.com
 **/
?>


/* TextboxList sample CSS */
ul.holder { margin: 0; border: 1px solid #999; overflow: hidden; height: auto !important; height: 1%; padding: 4px 5px 0; }
*:first-child+html ul.holder { padding-bottom: 2px; } * html ul.holder { padding-bottom: 2px; } /* ie7 and below */
ul.holder li { float: left; list-style-type: none; margin: 0 5px 4px 0; white-space:nowrap;}
ul.holder li.bit-box, ul.holder li.bit-input input { font: 11px "Lucida Grande", "Verdana"; }
ul.holder li.bit-box { -moz-border-radius: 6px; -webkit-border-radius: 6px; border-radius: 6px; border: 1px solid #CAD8F3; background: #DEE7F8; padding: 1px 5px 2px; }
ul.holder li.bit-box-focus { border-color: #598BEC; background: #598BEC; color: #fff; }
ul.holder li.bit-input input { width: auto; overflow:visible; margin: 0; border: 0px; outline: 0; padding: 3px 0px 2px; } /* no left/right padding here please */
ul.holder li.bit-input input.smallinput { width: 20px; }

/* Facebook demo CSS */      
#add { border: 1px solid #999; width: 550px; margin: 50px; padding: 20px 30px 10px; }
form ol li { list-style-type: none; }
form ol { font: 11px "Lucida Grande", "Verdana"; margin: 0; padding: 0; }
form ol li.input-text { margin-bottom: 10px; list-style-type: none; padding-bottom: 10px; }
form ol li.input-text label { font-weight: bold; cursor: pointer; display: block; font-size: 13px; margin-bottom: 10px; }
form ol li.input-text input { width: 500px; padding: 5px 5px 6px; font: 11px "Lucida Grande", "Verdana"; border: 1px solid #999; }
form ul.holder { width: 500px; }
form ul { margin: 0 !important }
ul.holder li.bit-box, #apple-list ul.holder li.bit-box { padding-right: 15px; position: relative; z-index:1000;}
#apple-list ul.holder li.bit-input { margin: 0; }
#apple-list ul.holder li.bit-input input.smallinput { width: 5px; }
ul.holder li.bit-hover { background: #BBCEF1; border: 1px solid #6D95E0; }
ul.holder li.bit-box-focus { border-color: #598BEC; background: #598BEC; color: #fff; }
ul.holder li.bit-box a.closebutton { position: absolute; right: 4px; top: 5px; display: block; width: 7px; height: 7px; font-size: 1px; background: url('<?php echo $vars['url']; ?>mod/custom_inputs/graphics/close.gif'); }
ul.holder li.bit-box a.closebutton:hover { background-position: 7px; }
ul.holder li.bit-box-focus a.closebutton, ul.holder li.bit-box-focus a.closebutton:hover { background-position: bottom; }

/* Autocompleter */

.facebook-auto { display: none; position: absolute; width: 512px; background: #eee; }
.facebook-auto .default { padding: 5px 7px; border: 1px solid #ccc; border-width: 0 1px 1px;font-family:"Lucida Grande","Verdana"; font-size:11px; }
.facebook-auto ul { display: none; margin: 0; padding: 0; overflow: auto; width: 512px; position:absolute;}
.facebook-auto ul li { padding: 5px 12px; z-index: 1000; cursor: pointer; margin: 0; list-style-type: none; border: 1px solid #ccc; border-width: 0 1px 1px; font: 11px "Lucida Grande", "Verdana"; background-color: #eee }
.facebook-auto ul li em { font-weight: bold; font-style: normal; background: #ccc; }
.facebook-auto ul li.auto-focus { background: #4173CC; color: #fff; }
.facebook-auto ul li.auto-focus em { background: none; }
.deleted { background-color:#4173CC !important; color:#ffffff !important;}
.hidden { display:none;}

#demo ul.holder li.bit-input input { padding: 2px 0 1px; border: 1px solid #999; }
.ie6fix {height:1px;width:1px; position:absolute;top:0px;left:0px;z-index:1;}




<!-- autocomplete facebook style plugin by Liran Tal of Enginx http://www.enginx.com -->
