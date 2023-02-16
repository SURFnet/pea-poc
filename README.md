# PEA

## Setting up the application

### Requirements

- PHP 8.0
- Composer 2
- NVM with Node 14
- MySQL 5.7

### Set up

#### Production
- Create a database
- Copy the `.env.example` to `.env`
- Install dependencies: `composer install --no-dev`
- Set up a new key for the application: `artisan key:generate`
- Properly fill in the `.env` file
- Run the migrations: `artisan migrate`
- Run the bootstrapper: `artisan bootstrap:application`
- Link the storage: `artisan storage:link`
- Run the asset build: `./buildHook.sh $PWD`
- Import the translations: `artisan w2w:import-translations`
- Export the translations for Javascript: `artisan w2w:export-translations`

#### Development
- Create a database
- Copy the `.env.example` to `.env`
- Install dependencies: `composer install`
- Set up a new key for the application: `artisan key:generate`
- Properly fill in the `.env` file
- Run the migrations and seeders: `artisan migrate --seed` (it will handle the bootstrapping and linking of the storage as well)
- Run the asset build: `./buildHook.sh $PWD --continue`
- Export the translations for Javascript: `artisan w2w:export-translations`

## Storage

If running in a Docker environment, always ensure that the symbolic links are created from Docker and not the host OS. 
Otherwise the path will not be correct and files can not be found by nginx.

## SURFconext authentication
To be able to login with SURFconext you need to configure valid oAuth credentials in your .env file:

```dotenv
# SURFconext credentials
SURFCONEXT_CLIENT_ID=
SURFCONEXT_CLIENT_SECRET=
SURFCONEXT_REDIRECT_URI=http://sreapp.localtest.me/auth/surf/callback
SURFCONEXT_TEST=true
```
The credentials can be managed through the [SURFconext Service Provider dashboard](https://sp.surfconext.nl/).

### SURFconext teams
Before users can login with SURFconext, they need to be member of one of the defined teams in SURFconext Teams. Management of these teams can be done on the Teams dashboard ([Test](https://teams.test.surfconext.nl/) or [Production](https://teams.surfconext.nl/)).

These teams are identified with an URN value, which is different between test and production.
For the testing environment, these values are to be configured in your .env file:
```dotenv
SURFCONEXT_ROLE_TEACHER=urn:collab:group:test.surfconext.nl:nl:surfnet:diensten:edutools_test_docent
SURFCONEXT_ROLE_INFORMATION_MANAGER=urn:collab:group:test.surfconext.nl:nl:surfnet:diensten:edutools_test_informatiemanager
SURFCONEXT_ROLE_CONTENT_MANAGER=urn:collab:group:test.surfconext.nl:nl:surfnet:diensten:edutools_test_contentmanager
```

### SURFconext login process
Perform the following steps to login with SURFconext:
* Click the `Login with SURFconext` button on the login screen
* Choose the EduID provider
* Log in with an existing EduID account, or [create your own free account](https://wiki.surfnet.nl/display/conextsupport/eduID+gasttoegang)
* After the login, you are redirect back into the application and a user account is either created, or updated with the latest information from SURFconext

