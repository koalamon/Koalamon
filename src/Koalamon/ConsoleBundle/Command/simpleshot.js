var system = require('system');
var page = require('webpage').create();

page.viewportSize = { width: 1024, height: 768 };
page.clipRect = { top: 0, left: 0, width: 1024, height: 2000 };

page.open(system.args[1], function () {
    page.render(system.args[2]);
    phantom.exit();
});