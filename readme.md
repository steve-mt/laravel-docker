# Docker containers of Laravel development.

Docker Containers for simple Laravel development.

# Prerequisites
##### This package only works for Linux users right now which can run docker natively. 
Some software you need installed for this to work.

1. Docker engine, installation documentation can be found [here](https://docs.docker.com/installation/)
2. Docker compose is needed to connect all of the containers together, documentation on installation can be found [here](https://docs.docker.com/compose/install/)
3. Having a fresh install of Laravel 5.1 

# Installation

In the root of your Laravel installation
```
$ composer require steveazz/laravel-docker
$ ./vendor/bin/laravel-docker make
$ sudo docker-compose up
```
If you check your directory where laravel is installed you will see a new file called.
```
    |-- docker-config
        |-- vhost.conf          # Config file for nginx
        |-- php-fpm.conf        # Config file for php-fpm
    |-- docker-compose.yml      # yml file for docker-compose
```

Next up open your favorite browser and go on 127.0.0.1:8080

![browser window](http://i.imgur.com/18LZ3yW.png)

You should see the default installation Laravel welcome page.

# Troubleshooting
 * If you get a '500' or '502 bad gateway' response in your browser, run the following commands.

    ```bash
    $ sudo chown -R www-data storage
    $ sudo chmod -R 0770 storage
    ``` 

* If you get 'failed to open stream: Permission denied' from laravel in your browser run the following command 

    ```bash
    $ php artisan cache:clear 
    $ composer dump-autoload
    ```

# More Information
You can find more information on how to use the docker containers in the wiki [here](https://github.com/SteveAzz/laravel-docker/wiki)
***

If you encounter any problem please do not hesitate to open up an issue I'll do my best to help.
