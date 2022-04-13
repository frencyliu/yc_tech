// to enable the enqueue of this optional JS file,
// you'll have to uncomment a row in the functions.php file
// just read the comments in there mate

//console.log("Customizer js file loaded");

//add here your own js code. Vanilla JS welcome.

(function ($) {
    wp.customize("yc_background_color", function (value) {
        value.bind(function (newval) {
            $("body").css("background-color", newval);
        });
    });
})(jQuery);
