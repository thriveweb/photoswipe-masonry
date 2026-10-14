=== Photoswipe Masonry ===

Contributors: deanoakley
Author: Web Design Gold Coast
Author URI: https://thriveweb.com.au/
Plugin URI: https://thriveweb.com.au/the-lab/photoswipe/
Tags: photoswipe, gallery, image gallery, masonry, lightbox
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.2.36
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

PhotoSwipe Masonry takes advantage of the built in gallery features of WordPress. The gallery is built using PhotoSwipe from Dmitry Semenov.

== Description ==

PhotoSwipe Masonry is an image gallery plugin for WordPress built using PhotoSwipe from Dmitry Semenov. [photoswipe](http://photoswipe.com/ "PhotoSwipe")
PhotoSwipe Masonry takes advantage of the built in gallery features of WordPress. Simply use the WordPress admin to create a gallery and insert it in the page.
You may need to adjust the size of the thumbnails to suit your theme in the settings.

Options are under Settings > PhotoSwipe

The PhotoSwipe Masonry gallery plugin allows you to:

* Upload multiple images at once
* Easily order images via drag and drop
* Add a title and caption

Via the options panel you can modify:

* Thumbnail size
* Full image size

Some other features include:

* Keyboard control
* Supports multiple galleries

See a [demo here](http://thriveweb.com.au/the-lab/PhotoSwipe/ "PhotoSwipeWP")


== Installation ==

1. Upload `/photoSwipe-masonry/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Upload some photos to the post or page where you want the gallery
4. Use WordPress to create a gallery and insert it in the page

== Screenshots ==

1. Masonry gallery with three columns, varied image heights and white gutters
2. Screenshot Popup layout

== Changelog ==

= 1.2.36 =
* Fix masonry layout on small screens: galleries now display in two columns below 650px and re-layout on resize.
* Load gallery scripts in the footer with proper dependencies, and cache-bust the inline CSS/JS on change.
* Add Requires PHP and License metadata.

= 1.2.35 =
* Update compatibility metadata after testing with WordPress 7.1.3 and PHP 8.4.
* Fix a PHP deprecation warning when initialising settings on first activation.
* Initialise masonry after the gallery markup is ready, including on block themes.

= 1.2.34 =
* Refresh the WordPress.org banners and plugin icons with abstract masonry artwork.
* Update the gallery screenshot and caption to show the current three-column masonry layout.

= 1.2.33 =
* Update the plugin release version to 1.2.33. No gallery behaviour changes.

= 1.2.32 =
* Readme update

= 1.2.19 =
* Readme update
* Added responsive styling for figure 

= 1.2.18 =
* Security update

= 1.2.17 =
* Bug fix - Push error 

= 1.2.16 =
* Security update
* Bug fix

= 1.2.15 =
* Security update

= 1.2.14 =
* Merge pull request from larsschellhas
* Fixed lazy load issues and updated masonry and photoswipe libraries

= 1.2.13 =
* Readme.txt update
* Security update
* Checking latest compatibility

= 1.2.12 =
* Readme.txt update
* Checking latest compatibility

= 1.2.11 =
* Small PHP error fix

= 1.2.10 =
* Readme.txt update
* Checking latest compatibility

= 1.2.9 =
* Meta update
* z-index update

= 1.2.8 =
* Meta update and testing for WP v5

= 1.2.7 =
* Warning error fix

= 1.2.6 =
* Readme and testing
* See v2 dev https://github.com/thriveweb/photoswipe-masonry-v2

= 1.2.4 =
* fix for WP 4.6.1

= 1.2.3 =
* Now runs from function to simplify AJAX support

= 1.2.2 =
* Fix loading images via url in gallery mode
* Removed sharing for single images. Will try to add in future versions.
* Code tidy

= 1.2.1 =
* Fix for captions in full screen
* Tidied code

= 1.2.0 =
* Major update with support for single images in posts

= 1.1.2 =
* Removed .entry-content img { max-width: none; }

= 1.1.1 =
* masonry enqueued call causing issues. I rolled back to old style

= 1.1.0 =
* fixed alt text
* added white theme
* added caption option
* added masonry option
* fixed masonry enqueue
* added clear css
* ie9 fix

= 1.0.6 =
* gallery load from URL (#&pid=1&gid=2) fix

= 1.0.5 =
* Yoast fix

= 1.0.4 =
* css fix

= 1.0.3 =
* photoswipe dist update

= 1.0.2 =
* photoswipe dist update

= 1.0.1 =
* This is the first version
