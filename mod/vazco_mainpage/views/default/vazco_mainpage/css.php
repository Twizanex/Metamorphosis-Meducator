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

	require_once(dirname(dirname(dirname(dirname(__FILE__))))."/models/mainpage_widgets.php");

	$mainpageWidgets = new mainpageWidgets();
	$background = $mainpageWidgets->getBackgroundStyle();
?>


/*-------------------------------
Dynamic MEMBERS box
-------------------------------*/

#m_latest,
#m_views,
#m_com{
	display:none;
	margin: 0 20px;
}
/*-------------------------------
DYNAMIC LOOK OF BACKGOUND ON THE MAIN PAGE
-------------------------------*/

<?php if ($background){?>
.mainpage_content{
	background: <?php echo $background;?>;
}

<?php }?>

#layout_canvas{
	padding: 0;
}
#layout_canvas .content{
	padding: 20px;
	-moz-border-radius:8px;
}

/*-------------------------------
MAINPAGE WIDGETS MAIN PAGE
-------------------------------*/

#index_center{
	float:left;
	margin:0 12px;
	width:534px;
}
.index_narrow{
	width: 185px;
}

#index_right,
#index_right_narrow,
#index_left,
#index_left_narrow {
	margin:0 0 30px;
	padding:0 0 20px 0;
	width:442px;
}

#index_right,
#index_right_narrow{
	float:right;
}

#index_left,
#index_left_narrow {
	float:left;
}

#index_left_narrow,
#index_right_narrow{
	width: 185px;
}

/*-------------------------------
MAINPAGE WIDGETS EDIT PAGE
-------------------------------*/

textarea.mainpage_edit{
	width: 185px;
	font-size: 10px;
	height: 150px;
}
#custom_index {
	margin:10px;
}
#index_left {
    width:442px;
    float:left;
    margin:0 0 30px 0;
    padding:0 0 20px 0px;
}
#index_right {
    width:442px;
    float:right;
    margin:0 0 30px 0;
    padding:0 0px 20px 0;
}
#index_welcome,
#index_welcome_wide,
#index_welcome_narrow {
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
	background: #dedede;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	width:240px;
}
#index_welcome #login-box form {
	margin:0 10px 0 10px;
	padding:0 10px 4px 10px;
	background: white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	width:200px;
}
#index_welcome #login-box h2,
.index_box h2 {
	color:#0054A7;
	font-size:1.35em;
	line-height:1.2em;
	margin:0 0 0 8px;
	padding:5px;
}
#index_welcome #login-box h2 {
	padding-bottom:5px;
}

.index_box {
	margin:0 0 20px 0;
	background: #dedede;
	padding:0 0 5px 0;
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

.vazco_mainpage_border{
	border: 1px #CCCCCC solid;
	margin: 0;

}
.vazco_mainpage_border_container{
	height: 180px;
}

.vazco_mainpage_border_container div{
	float: left;
}

.mainpage_bckg_example{
	margin: 0 0 0 125px;
}

.vazco_mainpage_bckg_example{
	max-height:150px;
	max-width:150px;
}
/*-------------------------------
SIMPLEPIE FEED WIDGET
-------------------------------*/
.simplepie_blog_title {
  text-align: center;
  margin-bottom: 15px;
}

.simplepie_item {
  margin-bottom: 12px;
}

.simplepie_title {
  margin-bottom: 4px;
}

.simplepie_excerpt {
  margin-bottom: 4px;
}

.simplepie_date {
  font-size: 70%;
}

.simplepie_excerpt img {
  width: 170px;
}

/*-------------------------------
TIDYPICS WIDGET
-------------------------------*/
.tidypics_index {
	margin:4px;
	float:left;
}
.tidypics_frontpage{
	max-width: 60px;
	max-height: 60px;
}
.frontpage_tidypics_box{
	text-align:center;
	height: 70px;
	margin: 5px 30px;
}

.frontpage_tidypics_box_narrow{
	text-align:center;
	height: 70px;
	margin: 5px 8px;
}

/*-------------------------------
LISTING NARROW
-------------------------------*/
.listing_narrow .search_listing{
	padding: 0;
	overflow:hidden;
}

.listing_narrow .search_listing .search_listing_info{
	padding: 0;
	margin: 5px 5px 5px 45px;
}
.group_listing_narrow .groupdetails{
	float: none;
	margin: 5px;
}
.group_listing_narrow  .search_listing .search_listing_info{
	margin: 10px;
}




#index_welcome_narrow #login-box {
	width:166px;
}
#index_welcome_narrow #login-box form {
	margin:0 8px;
	width:132px;
}
#index_welcome_narrow #login-box .login-textarea {
	width: 118px;
}
#index_welcome_narrow #persistent_login{
	margin: 0 5px 3px;
	width:115px;
}
#index_welcome_narrow .submit_button{
	margin-top: 5px;
}
/*-------------------------------
MAINPAGE WIDGETS EDIT PAGE
-------------------------------*/

#index_welcome_wide #login-box form {
    width: 320px
}
#index_welcome_wide #login-box {
    width:360px;
    margin: 10px 25px 15px;
}
#index_welcome_wide #login-box .login-textarea{
	width:268px;
}

/*-------------------------------
QUOTE OF THE DAY
-------------------------------*/
.index_box .qod_listing{
	margin: 0;
}

/*IE 6*/
*html .index_box .qod_listing{
	width: 400px;
}

/*IE 7*/
*:first-child+html .index_box .qod_listing{
	width: 400px;
}

/*-------------------------------
FILES
-------------------------------*/


*:first-child+html .index_box .filerepo_file{
	margin:10px;
	padding: 5px;
}

*html .index_box .filerepo_file{
	margin:10px;
	padding: 5px;
}
/*-------------------------------
SEARCH BOX
-------------------------------*/
.search_box_input{
	width:360px;
}

.search_box_button{
}
#index_left_narrow .search_box_input,
#index_right_narrow .search_box_input{
	width: 100px;
}
#index_left_narrow .search_box_input,
#index_right_narrow .search_box_input{
	width: 100px;
}
#index_center .search_box_input{
	width:450px;
}
p.search_box{
	margin: 10px;
}

/*-------------------------------
POLLS
-------------------------------*/
.index_box .poll_post_body h2 {
}