Steps to install flot use npm:

cd /files1/www/html/dms/javascript
npm i flot

sudo chmod -R g+w node_modules
sudo chown apache:apache -R node_modules

Remove this line from file javascript/node_modules/flot/dist/es5/jquery.flot.js
//# sourceMappingURL=jquery.flot.js.map

Otherwise, Chrome's F12 window will show this warning:
"DevTools failed to load source map: Could not load content for https://dms2.pnl.gov/javascript/node_modules/flot/dist/es5/jquery.flot.js.map:"
