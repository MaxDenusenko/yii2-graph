# Chart js

Creating charts for html tables (time/money)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

The minimum required PHP version is PHP 7.1.

### Installing

After cloning the repository, run

```
composer update
```

Create file *.env* in the root directory

```
nano .env
```

Fill it with the *.env.example* file

Run migration

```
php yii migrate
```

Create *.htaccess* file in *web/* directory

Exhample

```
RewriteEngine On
RewriteRule ^$ web/
RewriteCond %{REQUEST_FILENAME} !-f 
RewriteCond %{REQUEST_FILENAME} !-d 
RewriteRule ^(.*)$ web/$1
```

Configure your virtual hosts
Example for Apache

```
<VirtualHost *:80>
    ServerAdmin admin@example.com
    ServerName example.com
    ServerAlias www.example.com
    DocumentRoot /var/www/example.com/web
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    <Directory "/var/www/example.com/web/">
        # use mod_rewrite for pretty URL support
        RewriteEngine on
        # If a directory or a file exists, use the request directly
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        # Otherwise forward the request to index.php
        RewriteRule . index.php

        # use index.php as index file
        DirectoryIndex index.php

        # ...other settings...
        # Apache 2.4
        Require all granted

        ## Apache 2.2
        # Order allow,deny
        # Allow from all
    </Directory>
</VirtualHost>

```

## Interface

*Datums*

Section for creating groups for files.
After creating a group, upload files on the watch page.
For each downloaded file you need to specify your settings.

## Files settings 

*First Data Index*

Enter column(*td* or *td*) index for date and time data. 
Count start from left.
The countdown starts on the left from position 1.
Sample data: *2020.02.13 05:55:08*.
These values will be used for *x coordinates*.

*First Data Index*

Enter column(*td* or *td*) index for balance data. 
Count start from left.
The countdown starts on the left from position 1.
Sample data: 8.16 to add the sum to the balance | -5.78 to take away the amount

*Label Row Index*

Enter row(*tr*) index for label data.
Count start from top.
The countdown starts on the left from position 0.

*Balance*

Enter your starting balance.
Sample data: 120 or -200

*Skip Top*

Enter the number of lines(*tr*) to skip at the beginning.
If not specified, script reads data from the first *tr*

*Skip down*

Enter the number of lines to skip at the end.

*Max element*

Enter the maximum number of items to display.

## Run

After filling in all the settings, go to the group viewing page and click > *RUN*

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
