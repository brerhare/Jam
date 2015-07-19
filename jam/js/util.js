

function _getFormVars(formName) {
    console.log($(formName).serialize());
    return $(formName).serialize();
}

//alert('pre');
//alert(_getFormVars("supplierform"));
//alert('post');


function myFunction(formName) {
    var kvpairs = [];
    var form = document.getElementById(formName);
    for (var i = 0; i < form.elements.length; i++ ) {
        var e = form.elements[i];
        kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
    }
    var queryString = kvpairs.join("&");
    alert(queryString);
return false;
}


// Place at end of html to run code after dom loaded but not waiting for images to finish loading
(function() {
   // your page initialization code here
   // the DOM will be available here
//alert('x');
})();
