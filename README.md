# First installation instructions

Execute the following command in order :
+ `bin/console composer install`
+ `bin/console doctrine:migrations:migrate`
+ `bin/console doctrine:fixtures:load --append`

You can now connect as a regular user with :
+ mail@mail.com
+ password

or as admin user with :

+ admin@mail.com
+ password


# Additional information

You can execute a particular Fixture with the following command :

`bin/console doctrine:fixtures:load --group=userAndAdmin`

All Fixtures have a group which consist of their base name to the camelCase format.
