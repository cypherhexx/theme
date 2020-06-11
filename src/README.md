# Local Setup
This set up is designed for users with an intermediate skillset who have previously used programs like MAMP in order to set up a local web server.

It also assumes you are working on a Mac.

## Requirements

You'll need the following installed on your machine
- NPM
- Gulp
- docsify
- AMPPS <https://www.ampps.com/> or Mamp Pro (settings not included)

## Installing NPM
The quickest way to get NPM on your machine is to install Node JS <https://nodejs.org/en/>.

## Installing Gulp
You can get Gulp from <https://gulpjs.com/>.

## Installing docsify
Once you have NPM install in your terminal run...

```npm i docsify-cli -g```

This will instally docsify on your machine. We use docsify for the technical documents which will assist you in reskinning the white label setup. 

## AMPPS
The setup was designed to run in AMMPS. AMPPS makes it incredibly easy to set up a local domain with SSL and its free to use.

All domains are set up as follows.
- Production: https://www.domain.com
- Staging: https://staging.domain.com or https://client-project.staging.wearebigrock.com
- Development: https://dev.domain.com

You'll only need to set up the development domain in AMPPS, make sure you have enabled SSL when you do

## Cloning the repo
Your repo should be cloned to 
```/Applications/AMPPS/www/dev.domain.com```

## Run the following commands
Once you've cloned the repo cd into the src directory
``` cd /Applications/AMPPS/www/dev.domain.com/src```

Then run
```npm install```

To view the docs in a web browser run 
```npm run docs```

You'll now be able to see the full docs at <http://localhost:3000/#/>

To compile Scss, JS etc to the theme directory run
```gulp```

## Database setup
In most cases you'll find a local database setup and ready to go which needs to be installed on your local set up. The database used will most likely be called client_dev but you should check the name in this file & set it up using AMPPS PHPMyAdmin instalation.

```wp-br-config/wp-config.development.php```

Note: The database should be set up with the Collation set as utf8_general_ci

## Syncing the ACF Fields
Once you've set up the database, log into WordPress and check if the ACF fields require a sync.
