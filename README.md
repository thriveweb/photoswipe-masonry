# Phasonry

[![License: GPL v2](https://img.shields.io/badge/License-GPL_v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html) ![GitHub release (latest SemVer including pre-releases)](https://img.shields.io/github/v/release/jedburke/phasonry?include_prereleases)

A fork of the excellent Photoswipe Masonry Gallery WordPress plugin by Web Design Gold Coast.

A recent botched update (v1.2.15) caused it to stop working on a high-priority website. The creation of this fork was lead by the apparent unwillingness to accept nor communicate about a pull request that corrects the issue.

## At a Glance

While the original is still somewhat maintained, this fork's goals are as follows

+ Prefer stability over new features
+ Validate user input and check for XSS when setting options
+ Utilize package managers for JS and PHP dependencies to
+ Keep dependencies updated with stable versions
+ Upgrade to Photoswipe v5 when stable
+ Bundle and tree-shake JS code
+ Drop support for officially unsupported versions of PHP
+ Scope PHP code for WordPress
+ Make JS code easier to debug (Only use minified dependencies for publishing)
+ Document what the functions do to make contribution easier
+ Increase test coverage for JS and PHP
+ Format and conform PHP source code to PSR-12
+ Format and conform JavasScript source code to Airbnb's JS style guide
+ Format and unify CSS source
+ Transpile JS for older browsers
+ Bring in PostCSS and Autoprefixer for older browsers
+ Maintain a separate CHANGELOG with detailed additions, removals, and fixes
+ Clarify the GPL v2 license
+ Explicitly use semantic versioning
+ Provide pre-built zip releases for each published version

The plugin will be uploaded to the WordPress Plugin Repository once it's stable. Please refer to the [releases](/releases) page for pre-built zip files that you may upload.

Consider the following JS style guides
 https://standardjs.com/rules.html
 https://github.com/airbnb/javascript
 https://guide.meteor.com/code-style.html#javascript


## Replacing PhotoSwipe Masonry Gallery

Phasonry uses [SemVer](https://semver.org/)

Phasonry v1.x is a drop-in replacement for Photoswipe Masonry Gallery. The class name and plugin name will remain the same.

+ v2.0 is the official start of Phasonry with breaking changes
+ v1.12.17 removes the caption addition as a figure element in the gallery
+ v1.12.16 contains the aforementioned bugfix for Photoswipe Masonry Gallery
+ v1.12.15 and below refers to Photoswipe Masonry Gallery

Please see the [CHANGELOG](/CHANGELOG.md) for more.

For security reasons, the 2.x branch of Phasonry will not support EOL versions of PHP. WordPress' own minimum requirements are of no consequence.

## License

Copyright for portions of Phasonry are held by Web Design Gold Coast circa 2010 as part of Photoswipe Masonary Gallery. All other copyright for Phasonry are held by Evrnet Systems circa 2022.

Please see the LICENSE file for the project's license and the licenses directory for the licenses of its dependencies.
