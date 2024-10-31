=== NGO Production ===
Contributors: George Bredberg
Donate link: https://ngo-portal.org/donera/
Tags: production, theater production, custom post type, 
Requires at least: 3.0.1
Tested up to: 4.6
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin to create events of the type "Theater productions". Creates a custom post type production with actor taxonomy,venues etc.

== Description ==

This plugin creates a custom post type for theater productions with taxonomy for actors, venues and more. It's intended to be used by theater associations, NGO:s presenting theater productions etc. It will hold the information about a theater production together with taxonomies to make it easy to create events. Included are template files that you can use to create your own templates for your own theme to customize how the information is showed. 
The template files will work with the theme "Beyond-expactations", but are merely provided as guides to make your own template files. If you dont use the templates your theme should be able to show the information about the event, but it might miss some of the taxonomies.
The plugin is a part of the [NGO-portal](https://ngo-portal) project. You can find the code at [GitHub](https://github.com/joje47/NGO-portal). 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. There are no settings for this plugin. Just add your events using the Concert menu item in your Wordpress back office.

== Screenshots ==
1. A sample page showing the custom post types for ngo-production

== Frequently Asked Questions ==

= The template files does not work with my theme =

No, these template files are made for specific themes. The folders are named after the themes, so it should be fairly easy to find them. Wally-theme is a high availability theme you can download from wally-wp.se. The rest you find at Wordpress. They are provided as guides for how you can create template files for your own theme.

== Changelog ==
= 1.2.2 =
* Latest stable release
Changed language base from Swedish to English to make translation easier. I also added a few more template files.

= 1.2.1 =
Changed donation link (important stuff ;) )

= 1.2 =
- Fixed issues with generic function names
- Fixed issues with sanitizing text-fields
- Included css in plugin, instead of from google, to reduce latency.

= 1.1.1 =
Initial release for WP repos
