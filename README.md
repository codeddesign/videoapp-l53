# Video App

# Getting Started
This project uses [Composer](https://getcomposer.org/) and [yarn](https://yarnpkg.com/) for dependency management and [Vagrant](http://www.vagrantup.com/) for development environment management.
You should install these tools and familiarize yourself with them.

## Development Environment Setup

1. Clone this repository and change your working directory to the repository root

2. Make sure you are running the latest version of VirtualBox and Vagrant

3. Setup [Laravel Homestead](https://laravel.com/docs/5.3/homestead)

4. Create the development and testing databases: 
```
psql -U homestead -h localhost
(password: secret)
CREATE DATABASE videoapp53;
CREATE DATABASE videoapp53-testing; 
```

5. Run `php artisan key:generate` to generate your application key.

6. Copy `.env.example` to `.env` and edit the file according to your local development environment.

7. Start the Webpack Dev Server using `yarn run dev`.


# Contributing

1. Please make all your changes in a new git branch: `git checkout -b my-new-feature develop`

2. When you're ready to submit your changes, make sure your code passes all tests (`composer run tests`) and that you're following the code styling guidelines - run `composer run lint` to lint your PHP code and `yarn run lint` to lint your JS code.

3. After making sure your code is ready, push your new branch using: `git push -u origin my-new-feature` (**do not** push to master or develop directly)

4. Submit a Pull Request on [GitHub](https://github.com) and your code will be reviewed and merged as soon as possible.
