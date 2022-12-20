Steps to install flot using npm:

cd /files1/www/html/dms/public/javascript
npm i flot

sudo chmod -R g+w node_modules
sudo chown apache:apache -R node_modules

Remove this line from file javascript/node_modules/flot/dist/es5/jquery.flot.js
//# sourceMappingURL=jquery.flot.js.map

Otherwise, Chrome's F12 window will show this warning:
"DevTools failed to load source map: Could not load content for https://dms2.pnl.gov/javascript/node_modules/flot/dist/es5/jquery.flot.js.map:"

Another method to download the latest package from NPM:
npm pack flot
This downloads the .tgz package, which can be extracted with 'tar -xvf flot*.tgz', creating a 'packages' folder that can be renamed to 'flot'

Packages and sources:
jquery.fancytree: github.com/mar10/fancytree; npm pack jquery.fancytree
slickgrid: slickgrid.net, github.com/6pac/SlickGrid; npm pack slickgrid
sortablejs: (dependency of SlickGrid) npm pack sortablejs
jqueryui: jqueryui.com; download from jqueryui.com/download (use the quick downloads at the top of the page, for the stable release and themes)
jquery: jquery.com, download the individual files from jquery.com/download
clipboard-polyfill: (deprecated, used only for supporting old browser versions) github.com/lgarron/clipboard-polyfill; npm pack clipboard-polyfill
js-cookie: github.com/js-cookie/js-cookie; download from github releases, or use npm pack js-cookie
flot: www.flotcharts.org, github.com/flot/flot; npm pack flot
jquery.unobtrusive-ajax: github.com/aspnet/jquery-ajax-unobtrusive; npm pack jquery-ajax-unobtrusive
jquery.event.drag: github.com/threedubmedia/jquery.threedubmedia, threedubmedia.com/code/event/drag (see downloads on right side of page)

select2: github.com/select2/select2, select2.org; npm pack select2
chosen: github.com/harvesthq/chosen, harvesthg.github.io/chosen; npm pack chosen-js
chosen-jjj: github.com/jjj/chosen, https://jjj.github.io/chosen; npm pack chosen-jjj (npm doesn't have it listed, scrape from the github.io site)

