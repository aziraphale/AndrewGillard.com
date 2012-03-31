/**
 * Allows IE6, IE7 and IE8 to at least recognise the new HTML5 tags in CSS selectors. Not having the pretty CSS3 properties in older IEs is no big deal, but having it completely ignore those selectors is a huge problem.
 * 
 * @link http://www.communitymx.com/content/article.cfm?cid=8C170
 */
(function(){
    var html5elements = "address|article|aside|audio|canvas|command|datalist|details|dialog|figure|figcaption|footer|header|hgroup|keygen|mark|meter|menu|nav|progress|ruby|section|time|video".split('|');
    for(var i = 0, l=html5elements.length; i < l; i++){
        document.createElement(html5elements[i]);
    }
})();
