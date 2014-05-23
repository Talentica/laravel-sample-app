laravel-sample-app
==================

ToDo list web application using laravel and angularJs

Vagrant up should bring up something on 10.10.10.2. Add a host to your hosts file called talentica.dev pointing to 10.10.10.2
and see if talentica.dev/info.php says HipHop. If it does, you just got served a php page via HHVM.


if you run into  HipHop Fatal error: unexpected St13runtime_error: locale::facet::_S_create_c_locale name not valid #1052 
while running composer install, run this on the bash prompt:LC_CTYPE="en_US.UTF-8"
LC_ALL="en_US.UTF-8"


Better still, add this to your .bashrc/.bash_profile. Will work on a proper fix sometime.



