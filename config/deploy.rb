# config valid only for Capistrano 3.4
# lock '3.4.0'

############################################
# Setup WordPress
############################################

set :wp_user, "dev-wp" # The admin username
#set :wp_pass, "dev-wp-password" # The admin password
set :wp_language, 'fr_FR'

############################################
# Setup project
############################################

set :application, "DevWP"
set :repo_url, ""
set :scm, :git

############################################
# Setup Capistrano
############################################

set :log_level, :debug
set :use_sudo, false

set :ssh_options, {
  forward_agent: true
}

set :keep_releases, 3

set :working_dir, -> { "#{fetch(:release_path)}" }

############################################
# Linked files and directories (symlinks)
############################################

set :linked_files, %w{dev-wp/wp-config.php dev-wp/.htaccess phinx.yml phinx-conf.php gateway/config.js}

dirs = [
  'dev-wp/content/uploads',
  'dev-wp/wordpress',
]

set :linked_dirs, fetch(:linked_dirs, []).concat(dirs)

namespace :deploy do

  after :finished, "composer:update"
  after :finished, "deploy:migrations:migrate"
  # after :finished, "deploy:plugins:update"
  after :finished, "wp:core:update"
  after :finished, "robots:block"
  after :finishing, "deploy:cleanup"

end
