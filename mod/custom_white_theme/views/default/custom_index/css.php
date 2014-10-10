<?php
	/**
	 * Custom Index page css extender
	 * 
	 * @package custom_index
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
?>

#custom_index {
	margin:10px;
}
#index_left {
    width:325px;
    float:right;
    margin:0 0 30px 0;
    padding:0 0 20px 0px;
}
#index_right {
    width:560px;
    float:left;
    margin:0 0 30px 0;
    padding:0 0px 20px 0;
}
#index_welcome {
	padding:5px 10px 5px 10px;
	margin:0 0 20px 0;
	border:1px solid silver;
	background: white;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px; 
}
#index_welcome #login-box {
	margin:5px 0 10px 0;
	padding:0 0 10px 0;
	background: #000000;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	width:303px;
}
#index_welcome #login-box form {
	margin:0 10px 0 10px;
	padding:0 10px 4px 10px;
	background: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	width:262px;
}
#index_welcome #login-box h2,
.index_box h2 {
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;	
	-webkit-border-top-left-radius:5px;
	-webkit-border-top-right-radius:5px;
background:#FAFAFA url(<?php echo $vars['url']; ?>mod/custom_white_theme/graphics/toptoolbar_background.gif);
color:#DDDDDD;
font-size:1em;
line-height:1.2em;
margin:0 0 12px 0;
padding:5px 5px 5px 14px;
}
#index_welcome #login-box h2 {
	padding-bottom:5px;
}

.index_box {
margin:0 0 20px;
padding:0 0 5px;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
}

.index_box .search_listing {

}
.index_box .index_members {
	float:left;
	margin:2pt 5px 3px 0pt;
}
#persistent_login {
	float:right;
	display:block;
	margin-top:-34px;
}



