## 0. Reuirements
 * [VirtualXox](https://www.virtualbox.org/)
 * [Git](https://git-scm.com/download/win)
 * [Vagrant](https://www.vagrantup.com/)
 * [Node.js](https://nodejs.org/)


## 1. Prepare Vagrant
 * Install VirtualBox
 * Install Git
 * Install Vagrant
 * Open any directory in system console
 * Run: __*git clone https://github.com/chybaDapi/exchange.git*__
 * Change default Exchange wallet in __*database.sql*__ (lines 62-69)
 * Open __*exchange*__ directory in system console
 * Run: __*vagrant up*__
 
 
## 2. Prepare Gulp
 * Install Node.js
 * Open __*exchange/website*__ directory in system console
 * Run: __*npm install --global gulp*__
 * Run: __*npm install*__
 * Run: __*Gulp*__
 
 
## 3. Post-instalation
  * Change integrity attribute in __*website/templates/index.twig*__ (from __*website/sri.json*__)
  * Add __*192.168.33.10 exchange.dev*__ to yout hosts file *(System32/drivers/ets)*
  * Open exchange.dev in your web browser
  * Pray :)
 

## N. A few words at the end...
  * The application implemented SRI. Hashes CSS and JS files are generated to website/sri.json and should be changed in website/templates/index.twigg after each gulped.
  * The application implemented CSP. Please do not spoil it. :) Do not use inline styles and use inline script always with "nonce" attribute. Remember about CSP headers!