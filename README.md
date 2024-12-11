# PEA - Platform Educational Applications (Pilot Phase)

## Setting up the application

### Requirements

- PHP 8.0
- Composer 2
- NVM with Node 20
- PNPM >=8.7.6 && <9
    - Run `curl -fsSL https://get.pnpm.io/install.sh | env PNPM_VERSION=8.15.8 sh -` to install pnpm
        - Run `pnpm add -g pnpm@8` if you need to update to a newer version of pnpm 8 for some reason
- MySQL 5.7
- S3 (or compatible) storage

### Set up

#### Production
- Create a database
- Copy the `.env.example` to `.env` and configure the variables
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
- Copy the `.env.example` to `.env` and configure the variables
- Install dependencies: `composer install`
- Set up a new key for the application: `artisan key:generate`
- Properly fill in the `.env` file
- Run the migrations and seeders: `artisan migrate --seed` (it will handle the bootstrapping and linking of the storage as well)
- Run the asset build: `./buildHook.sh $PWD --continue`
- Export the translations for Javascript: `artisan w2w:export-translations`

## Storage
Storage is assumed to run from a S3 service, or anything that's compatible with S3 (like Minio, Wasabisys, etc.).
A public bucket is required from which images are served, and an Access Key + Secret should be entered into the .env file:

```dotenv
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET_PUBLIC=
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=true
```

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
