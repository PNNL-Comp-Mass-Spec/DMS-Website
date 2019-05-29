Make sure php is in the path, or use the full path to the php executable
* export PATH=$PATH:/opt/rh/rh-php71/root/usr/bin

To add composer to the existing project, from scratch
* For use with CodeIgniter, do this from the 'application' subfolder
* follow instructions at https://getcomposer.org/download/
  * php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  * php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  * php composer-setup.php
  * php -r "unlink('composer-setup.php');"

To add a dependency using composer (e.g., PHPSpreadsheet)
* php composer.phar require phpoffice/phpspreadsheet

To have CodeIgniter load the composer autoloader (CodeIgniter 3+)
* edit application/config/xxx/config.php
  * Change $config['composer_autoload'] = FALSE to TRUE

To restore dependencies (requires composer.lock file)
  * php composer.phar install
