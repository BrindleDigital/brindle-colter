# Brindle Colter Theme

The Colter theme by Brindle Digital is a child theme of the GeneratePress Pro theme.

As a developer, there are steps to know in building and releasing this theme to clients.
Changes to the theme involve the following high-level development steps:

* Pull the source code from the 'develop' branch on the remote Git repository.
* Make your code changes.
* Run 'grunt' on the command line to compile SASS files and generate a minified CSS file.
* Commit and push your code to the 'develop' branch on the remote GitHub repository.

When a production release is ready:

* Update the version number in style.css.
* Create an entry in the CHANGELOG.md file.
* Commit the code, tagging the develop branch with the style.css version number.
* Merge the 'develop' branch into 'main.'
* Push both 'develop' and 'main' branches to the remote Git repository.

When client sites detect a new 'tagged' release in the main branch of GitHub,
the client site will prompt the user to update their theme version with
a click of a button!

So, if you're a developer, please read on! There's stuff to know.

## Contributors

It helps to track our contributers, for future support contact or otherwise.

* D.Faltermier <brindledigital.com>

## Theme Files
The theme files are scaffolded in a manner that keep files organized and code modular.

```
│── acf
│   │── blocks                    // Put ALL blocks assets in self-contained folders
│   │   │── call-to-action        //   including php, JS, and CSS/SCSS files
│   │   │   │── _call-to-action.scss
│   │   │   └── call-to-action.php
│   │   └── other blocks...
│   │── helpers.php               // Helper functions shared between ACF blocks
│   └── setup.php                 // Loads ACF blocks and their dependencies
│── acf-json
│   │── group_62a46809d2ceb.json  // ACF-created field group configuration files
│   └── group_61f56a7678559.json
│── assets
│   │── css
│   │   │── scss
│   │   │   │── common            // Folder contains styling for common page sections
│   │   │   │── components        // Folder contains styling for buttons, other discrete elements
│   │   │   │── custom-post-types // Folder contains styling for CPTs
│   │   │   │── vendor            // Folder for any vendor stylesheets
│   │   │   │── wp-admin          // Folder contains WP backend only styling
│   │   │   └── main.scss         // Imports all SCSS files. Add your new SCSS file here.
│   │   │── main.min.css          // Compiled SASS files. Loaded on frontend and WP backend
│   │   └── main.min.css.map      // Browser CSS source map (i.e. debugging)
│   │── images                    // Theme-specific images
│   └── js
│       │── common                // Folder contains JS for common page sections
│       │── custom-post-types     // Folder contains JS for CPTs
│       │── vendor                // Folder for any vendor JS
│       └── wpadmin               // Folder contains JS for WP backend only
│── lib
│   │── custom-post-types         // Folder contains PHP for CPTs
│   │── helpers.php               // Generic helper methods
│   │── setup.php                 // Sets up and inits the theme. Start here!
│   └── styles.php                // Places styles in the <head> from GeneratePress
│── node_modules                  // Installed during development. Do not push to Git.
│── 404.php                       // Default error pages
│── 500.php
│── CHANNGELOG.md                 // Record each [git] tagged release in here!
│── functions.php                 // Required
│── Gruntfile.js                  // Grunt commands to build the theme files
│── package.json                  // Node configuration for Grunt
│── package.json.lock
│── README..developer.md          // Developers should read this!
│── README.md                     // Everyone should read this!
│── screenshot.png                // Displayed in Menu > Appearance > Themes
└── style.css                     // Required
```

## Dependencies

* [Advanced Custom Fields Pro plugin](https://www.advancedcustomfields.com/pro/)
* [GeneratePress Pro theme](https://generatepress.com/premium/)
* [GenerateBlocks plugin](https://generateblocks.com/)
* [Rent Fetch plugin](https://github.com/jonschr/rent-fetch) (Brindle Digital)

## Build Tools

Making code or styling changes to the theme should NEVER be done manually on
a production website. There is a build process that compiles SASS files into
a single, minified CSS file: main.min.css (and main.min.css.map). These two
files must be deployed to the production websites.

The following tools (and versions) were used to perform the above. See their
websites in order to install these tools. We'll walk through the build process
in a moment.

* [grunt v1.5.3 and grunt-cli v1.4.3](https://gruntjs.com/)
* [node v18.4.0](https://nodejs.org/en/)

## Using Git

The remote Git repository should ALWAYS be used to store the latest source code.
There are two branches to maintain: develop and main. Broken source code should
never be commited to either. Use the develop branch for testing and incremental
development. The main branch should ONLY be used to store production-ready,
tagged commits. These commits will be propagated to the client websites. So,
be forewarned.

## Development Process

Once you clone the source code from the remote Git repository, you'll want to
run 'grunt' for the initial build.

```
$ cd <project-folder>
$ git clone ...
$ git checkout develop
$ npm install
$ grunt
```

From here, make any code changes you need. As you edit and save SASS files, grunt
will automatically watch for changes to these files and recompile as them
as needed. Here are the files that get touched:

```
└── assets
    └── css
        │── scss
        │   └── main.scss         // Imports all SCSS files. List your new SCSS files here.
        │── main.min.css          // Compiled SASS files. Loaded on frontend and WP backend.
        └── main.min.css.map      // Compiled CSS source map used by the browser i.e. debugging)

```

When coding is complete, commit your changes to the develop branch and push
to the remote Git repository.

## Release Process

The Colter site includes a plugin called [Git Updater](https://git-updater.com/). This
plugin provides the service of comparing the version of the theme on the client
site with the version in the 'main' branch in our GitHub repository. If a newer version
is detected in GitHub, then the client is prompted to update the theme using WordPress'
standard one-click process from the WP Menu > Dashboard > Updates page. The plugin will
download the new version in place of the one installed.

In order for this process to work, you MUST follow the below instructions.

```
# Checkout the 'develop' branch.
$ git checkout develop

# Update the Version number in style.css (i.e '1.0.1').
$ vi style.css

# Add an entry in the CHANGELOG.md file with the new version and change details.
$ vi CHANGELOG.md

# Run 'grunt' to compile SASS files, if you haven't already.
$ grunt build

# Commit your code to the develop branch.
$ git add .
$ git commit -m 'Bump version number in preparation for release'

# Add an annotated tag on the develop branch with the SAME EXACT version string you used in style.css.
$ git tag -a 1.0.1 -m 'Release version 1.0.1'

# Push the tagged commit to the remote repository.
$ git push origin 1.0.1

# Merge develop with the main branch. Commit and push the changes to the remote Git repository.
$ git checkout main
$ git merge --squash develop
$ git commit -m 'Release version 1.0.1'
$ git push origin main
```

## Updating Client Sites

On each client site, if a new tagged version of the theme is detected in the GitHug repository,
then the client will be prompted to update the theme on the WP Menu > Dashboard > Updates page.

Note that once a new release has been pushed to the GitHub repository, the change
may take a while before it is detected by the client sites. The Git Updater plugin
runs a Cron job, of sorts, to periodically check the repository.

## The Git Updater Plugin: What to Know

In the Git Updater plugin settings page, there is a tab for GitHub settings
(WP Menu > Settings > Git Update > GitHub). So that the
plugin may periodically check the GitHub server for a new theme, you'll need to create and
enter a GitHub.com Personal Access Token (PAT). Using a PAT will increase the number
of hits the GitHub server allows from about 60/hour to 5000/hour or so.

GitHub provides [instructions for create a PAT](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token). It's important that the owner of the GitHub repository
which hosts the source code generate the PAT. During the creation process, you'll have
the opportunity to select the access privileges this token should provide. **IT IS IMPORTANT
that the access token have read privileges for 'repo' and 'read:packages.'**

Once a PAT is created, save it in the Git Update plugin input field labeled 'GitHub.com Access Token.'

For more information

* https://github.com/afragen/git-updater/releases
* https://git-updater.com/knowledge-base/
* https://git-updater.com/knowledge-base/general-usage/?seq_no=2

## Advanced Custom Fields

TBD: ACF json lock-down

## Developer Notes

### Action Scheduler and the Rent Fetch Plugin

In rare cases, the Action Scheduler code in the Rent Fetch plugin may generate and store thousands of log records in the database. (In tables created by Action Scheduler.) The developer of the Rent Fetch plugin notes the following to fix this:

Here are the steps:
* Take a backup of the database and put it somewhere safe (just in case).
* Install and activate the [Action Scheduler plugin](https://wordpress.org/plugins/action-scheduler/.) by Automatic.
* Deactivate Rent Fetch plugin.
* In phpMyAdmin, drop the four actionscheduler tables.
* On the backend of the site, navigate to Tools > Scheduled Actions. The Action Scheduler plugin will detect that its tables are missing and will automatically reinstall those.
* After those tables have regenerated (which just takes a few seconds), reactivate Rent Fetch, and you're finished!
* In the Rent Fetch plugin > Settings, you can set synching to pause those tables from growing.
* Delete the Action Scheduler plugin
