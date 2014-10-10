<script type="text/javascript" language="javascript">
  var ts = "<?php echo time(); ?>";
  var token = "<?php echo elgg_view('ajax/securitytoken'); ?>";
  var searchNr = 0;
  var searchActionURL = "<?php echo $vars['url']."action/mmsearch/search"; ?>";
  var website_url = "<?php echo $vars["url"]; ?>";

  <?php include_once(dirname(dirname(__FILE__)) . "/js/main.js"); ?>
  <?php include_once(dirname(dirname(__FILE__)) . "/js/center_div.js"); ?>
</script>

<style type="text/css">
  <?php include_once(dirname(dirname(__FILE__)) . "/css/jquery-ui-1.8.7.custom.css"); ?>
  <?php include_once(dirname(dirname(__FILE__)) . "/css/style.css"); ?>
</style>


<div class="contentWrapper">
  <div id="tabs">
    <!-- the tabs -->
    <ul>
      <li><a href="#newSearch">New search</a></li>
    </ul>

    <!-- tab "panes" -->
    <div id="newSearch">
      <?php echo elgg_view('mmsearch/form'); ?>
    </div>
  </div>
</div>

<!-- template to be used when opening a new tab -->
<div id="newTabTemplate">
  <div>
    <div class="top_display">
      <div class="search-info-display" id="search-info-display-:search_nr:">[ <em>here will be displayed search criteria</em> ]</div>
      <div class="close-display"><a href="javascript:closeTab(:search_nr:)">[close this search]</a></div>
      <div class="clear_div"></div>
    </div>
    <div class="pagination_sort_display">
      <div id="pagination-display-:search_nr:" class="pagination-display"></div>
      <div id="sort-display-:search_nr:" class="sort-display"><strong>Sort by:</strong> <a href="javascript:sort(':search_nr:','title','');">title</a></div>
      <div class="clear_div"></div>
    </div>
    <div id="results-display-:search_nr:">
      <img src="views/default/images/preloader.gif" align="left" style="width: 50px; margin-right: 20px; margin-bottom: 5px;">
      Searching ...<br/>Please wait.
      <br/><br/><br/>
    </div>
  </div>
</div>
<!-- template to be used for display of each result -->
<div id="searchResultTemplate">
  <div class="searchResult">
    <div class="searchResult_col1">
      <div class="mms_label_l">Title:</div>
      <div class="mms_content_l" title=":t_title:">:title:</div>
      <div class="clear_div"></div>
      <div class="mms_label_l">Author(s):</div>
      <div class="mms_content_l" title=":t_rauthors:">:rauthors:</div>
      <div class="clear_div"></div>
      <div class="mms_label_l">Added by:</div>
      <div class="mms_content_l">:mauthors:</div>
      <div class="clear_div"></div>
      <div class="mms_label_l">License:</div>
      <div class="mms_content_l" title=":t_license:">:license:</div>
      <div class="clear_div"></div>
    </div>
    <div class="searchResult_col2">
      <div class="mms_label_r">Resource creation date:</div>
      <div class="mms_content_r">:rdate:</div>
      <div class="clear_div"></div>
      <div class="mms_label_r">Metadata creation date:</div>
      <div class="mms_content_r">:mdate:</div>
      <div class="clear_div"></div>
      <div class="mms_label_r">Resource language:</div>
      <div class="mms_content_r">:rlang:</div>
      <div class="clear_div"></div>
      <div class="mms_label_r">Metadata language:</div>
      <div class="mms_content_r">:mlang:</div>
      <div class="clear_div"></div>
    </div>
    <div class="clear_div"></div>
    <!--<a href="javascript:viewProfile(':internal_id:')">View resource profile</a>&nbsp&nbsp|&nbsp;-->
    <a href=":profile_url:" target="_blank">View resource profile</a><!--&nbsp&nbsp|&nbsp;-->
    <!--<a href="javascript:viewDetails(':result_id:')">View RDF info</a>&nbsp&nbsp|&nbsp;-->
    <!--<a href="javascript:addFavorite(':result_id:')">Add to favorites</a>-->
  </div>
</div>

<!-- DIV USED TO DISPLAY DETAILS FROM RDF -->
<div id="details_div">
  <div id="details_div_controls"><a href="javascript:hideDetails()">[close]</a></div>
  <div id="details_div_content"></div>
</div>