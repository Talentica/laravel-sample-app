laravel-sample-app
==================

ToDo list web application using laravel and angularJs
-----------------------------------------------------

Vagrant up should bring up something on 10.10.10.2. Add a host to your hosts file [/etc/hosts] called talentica.dev pointing to 10.10.10.2
and see if talentica.dev/info.php says HipHop. If it does, you just got served a php page via HHVM. Your app should be available in talentica.dev/api/v1/lists or similar.

Ignore any errors regarding php-fpm if you see them when you do vagrant up.

Changes made to default puphet config
-------------------------------------
1. nginx root points to /var/www/talentica.dev/public instead of /var/www/talentica.dev now
2. Commented a regex check for supervisord autostart
3. Added a .bashrc to export locale so HHVM doesnt die with the error mentioned here https://github.com/facebook/hhvm/issues/1052


