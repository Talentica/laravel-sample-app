# Class: supervisord::service
#
# Class for the supervisord service
#
class supervisord::service inherits supervisord  {
  service { 'supervisord':
    ensure     => $supervisord::service_ensure,
    enable     => true,
    hasrestart => true,
    hasstatus  => true
  }
}
