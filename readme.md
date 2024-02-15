# iLS Chat

This internal chat application is meant to allow communication under strict canned messaging between phone agents (Relationship Managers).

## Installation

This application is built with Laravel 5.8, Vue 2 and Pusher.js as it's core components.

To install all that is needed is to run both

```bash
composer install
```

and

```bash
npm install
```

It is important to note that the following command needs to be run in order to compile the code with the correct Pusher keys in the .env file.

```bash
npm run production
```

This is because npm compiles the code based on the Pusher key on the .env file it was compiled with, so compiling on the development server and pushing to production will result in Pusher transmitting data to the development Pusher app instead of the production Pusher app.

As an additional note the following command should be run before pulling to production and compiling again

```bash
git reset --hard
```
This will help avoid the need to merge the compiled app.js file created when compiling the application on the production server.

## Contributing

Any future contributions need to be made via forking this repo. For more information email [Ismael](mailto:ismael@ileadserve.com) or [Ryan](mailto:ryan@ileadserve.com)