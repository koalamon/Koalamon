var system = require('system');
var page = require('webpage').create();

page.viewportSize = { width: 1366, height: 1000 };
page.clipRect = { top: 0, left: 0, width: 1366, height: 1700 };

<<<<<<< HEAD
// setting the user agent crashes the script with a segmentation fault
//page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
=======
<<<<<<< HEAD
// setting the user agent crashes the script with a segmentation fault
//page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
=======
//page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
// page.settings.userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36";
>>>>>>> f60ef1daf8959ee1baaaa501f3b9936fef14ce01
>>>>>>> d902c99546012e1be2edef5e44ebd53b0d2d5dea

page.open(system.args[1], function () {
    page.render(system.args[2]);
    phantom.exit();
});
