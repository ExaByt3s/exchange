## 0. Reuirements
 * [VirtualXox](https://www.virtualbox.org/)
 * [Git](https://git-scm.com/download/win)
 * [Vagrant](https://www.vagrantup.com/)
 * [Node.js](https://nodejs.org/)


## 1. Prepare Vagrant
 * Install VirtualBox
 * Install Git
 * Install Vagrant
 * Open __*exchange*__ directory in system console
 * Run: __*vagrant up*__
 
 
## 2. Prepare Gulp
 * Install Node.js
 * Open __*exchange/website*__ directory in system console
 * Run: __*npm install --global gulp*__
 * Run: __*npm install*__
 
 
 ## X. Post-instalation
  * Add __*192.168.33.10 exchange.dev*__ to yout hosts file *(System32/drivers/ets)*
 



 w database zmienić ilości początkowe





testowe:
 sudo -u postgres pg_dump exchange > /vagrant/test.sql
 sudo -u postgres psql < /vagrant/database.sql