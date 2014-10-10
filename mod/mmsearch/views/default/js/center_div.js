(function($){
  $.fn.positionCenter = function(options) {
    var pos = {
      sTop : function() {
        return window.pageYOffset || document.documentElement && document.documentElement.scrollTop ||	document.body.scrollTop;
      },
      wHeight : function() {
        return window.innerHeight || document.documentElement && document.documentElement.clientHeight || document.body.clientHeight;
      }
    };

    return this.each(function(index) {
      if (index == 0) {
        var $this = $(this);
        var elHeight = $this.outerHeight();
        var elTop = pos.sTop() + (pos.wHeight() / 2) - (elHeight / 2);
        $this.css({
          position: 'absolute',
          margin: '0',
          top: elTop,
          left: (($(window).width() - $this.outerWidth()) / 2) + 'px'
        });
      }
    });
  };
})(jQuery);
