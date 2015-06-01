Docker-box
==========

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/7483768f-fa75-446a-b168-26c34a4786d0/mini.png)](https://insight.sensiolabs.com/projects/7483768f-fa75-446a-b168-26c34a4786d0)

# Installation

Open a command console, enter your project directory and execute the
following command to install dependencies :

```bash
    $ composer install
```

This command requires you to have Composer installed globally, as explained
in the ``installation chapter``_ of the Composer documentation.


# Using

With this application, container can't be started more times.


## Add container

For add a container, execute the following command :
```bash
    $ src/app.php container:add name
```

Application ask you command to create the container (without `docker run` and option `--name`).
Example : ``-d -p 1080:80 jderusse/mailcatcher``


## List containers

For list containers, execute the following command :
```bash
    $ src/app.php container:list
```

### Delete container

After list it's possible to delete a container. 

For question asked, enter 0 for quit or index of container to delete.


## Start container

For start a container, execute the following command :
```bash
    $ src/app.php container:start name
```

## Stop container

For stop a container, execute the following command :
```bash
    $ src/app.php container:stop name
```

## Status container

For get status of container(s), execute the following command :
```bash
    $ src/app.php container:status [name]
```

## Inspect container

For inspect a container, execute the following command :
```bash
    $ src/app.php container:inspect name [field]
```

Argument `field` can be :
* ip
* ports
* mac
* image
* all (default)


## View configuration container

For view the configuration, execute the following command :
```bash
    $ src/app.php container:config [name]
```


# TODO

- [ ] Force download image for container ?
- [ ] Use docker-compose
- [ ] Unit tests
