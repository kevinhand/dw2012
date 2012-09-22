var page = require('webpage').create(),
    address, output, size;

if (phantom.args.length < 2 || phantom.args.length > 3) {
    console.log('Usage: rasterize.js URL filename');
    phantom.exit();
} else {
    address = phantom.args[0];
    output = phantom.args[1];
    page.viewportSize = { width: 800, height: 600 };
    page.open(address, function (status) {
        if (status !== 'success') {
            console.log('Unable to load the address!');
        } else {
            var title = page.evaluate(function () {
                return document.title;
            });
            console.log(title);
            window.setTimeout(function () {
                page.clipRect = { top: 0, left: 0, width: 800, height: 600 };
                page.render(output);
                phantom.exit();
            }, 200);
        }
    });
}
