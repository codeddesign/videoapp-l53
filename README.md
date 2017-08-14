# Video App

# Getting Started
This project uses [Composer](https://getcomposer.org/) and [NPM](hhttps://www.npmjs.com/) for dependency management and [Vagrant](http://www.vagrantup.com/) for development environment management.
You should install these tools and familiarize yourself with them.

## Development Environment Setup

1. Clone this repository and change your working directory to the repository root.

2. Make sure you are running the latest version of VirtualBox and Vagrant.

3. Setup [Laravel Homestead](https://laravel.com/docs/5.3/homestead).

4. Create the development and testing databases: 
```
psql -U homestead -h localhost
(password: secret)
CREATE DATABASE videoapp53;
CREATE DATABASE "videoapp53-testing"; 
```

5. Run `php artisan key:generate` to generate your application key.

6. Copy `.env.example` to `.env` and edit the file according to your local development environment.

7. Install both PHP and JS dependencies - `composer install` and `npm install`, respectively.

8. Migrate and seed your database: `php artisan migrate` and `php artisan db:seed`

9. Start the Webpack Dev Server using `npm run dev`.

# Deployment

The deployment of this application is done using Ansible. A playbook that deploys the app is available at the [codeddesign/video-tracker](https://github.com/codeddesign/video-tracker/tree/master/ansible) repository, along with instructions on how to setup Ansible.

After properly setup, the Ansible deployment script can be executed using ```ansible-playbook deploy.yaml --ask-vault-pass``` and providing Ansible with the Vault's password.

# Contributing

1. Please make all your changes in a new git branch: `git checkout -b my-new-feature develop`.

2. When you're ready to submit your changes, make sure your code passes all tests (`composer run tests`) and that you're following the code styling guidelines - run `composer run lint` to lint your PHP code and `npm run lint` to lint your JS code.

3. After making sure your code is ready, push your new branch using: `git push -u origin my-new-feature` (**do not** push to master or develop directly).

4. Submit a Pull Request on [GitHub](https://github.com) and your code will be reviewed and merged as soon as possible.
