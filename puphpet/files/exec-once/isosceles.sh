# Copy Isosceles config files
cp /var/www/puphpet/files/isosceles-setup/configs/config.inc.php /var/www/libs/config.inc.php
cp /var/www/puphpet/files/isosceles-setup/configs/config.tests.inc.php /var/www/tests/config.tests.inc.php

# Make web and tests datadir folders
mkdir /home/vagrant/data/
mkdir /home/vagrant/data/web/
mkdir /home/vagrant/data/tests/

# Set permissions
chmod -R 777 /home/vagrant/data/
