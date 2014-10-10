jQuery.fn.sort = function()
{
    return this.pushStack([].sort.apply(this, arguments), []);
};

jQuery.fn.sortOptions = function(sortCallback)
{
    jQuery('option', this)
    .sort(sortCallback)
    .appendTo(this);
    return this;
};

jQuery.fn.sortOptionsByText = function()
{
    var byTextSortCallback = function(x, y)
    {
        var xText = jQuery(x).text().toUpperCase();
        var yText = jQuery(y).text().toUpperCase();
        return (xText < yText) ? -1 : (xText > yText) ? 1 : 0;
    };
    return this.sortOptions(byTextSortCallback);
};

jQuery.fn.sortOptionsByValue = function()
{
    var byValueSortCallback = function(x, y)
    {
        var xVal = jQuery(x).val();
        var yVal = jQuery(y).val();
        return (xVal < yVal) ? -1 : (xVal > yVal) ? 1 : 0;
    };
    return this.sortOptions(byValueSortCallback);
};