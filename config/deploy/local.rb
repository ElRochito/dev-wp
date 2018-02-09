############################################
# Setup WordPress
############################################

set :wp_email, "dev-wp@dev-wp.fr" # The admin email address
set :wp_sitename, "DevWP" # The site title
set :wp_localurl, "http://dev-wp.local" # Your local environment URL

############################################
# Setup Server
############################################

set :stage, :local
set :stage_url, "http://dev-wp.local"
set :stage_www_path, "/var/www/dev-wp"
set :migration_path, ""
server "localhost", user: "vagrant", roles: %w{app db local}, port: 22
set :deploy_to, ""

############################################
# Setup Project
############################################

set :debug, true
set :project_path, "/var/www"
set :shared, "/var/www"

############################################
# Setup Git
############################################

set :branch, ""
