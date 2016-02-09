var system = require('system');
var page = require('webpage').create();

page.viewportSize = { width: 1366, height: 1000 };
page.clipRect = { top: 0, left: 0, width: 1366, height: 1700 };

// setting the user agent crashes the script with a segmentation fault
// page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
// page.settings.userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36";

page.open(system.args[1], function () {
    page.render(system.args[2]);
    phantom.exit();
});
