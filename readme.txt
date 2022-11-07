=== O3 CLI Services ===
Contributors: gwolfman
Tags: O3 CLI, automation, quality assurance, testing, regression testing, REST API
Requires at least: 4.0
Tested up to: 5.3.1
Stable tag: 1.0.2
License: GPLv2 or later

O3 CLI Services integrates any WordPress site with the O3 CLI
(https://www.npmjs.com/package/o3-cli) tool.

== Description ==

In its current version, O3 CLI Services exposes two WordPress REST API endpoints
to empower developers and QA engineers to query URL paths of WordPress posts by
post types, category types, and menus.

== Installation ==

Upload the O3 CLI Services plugin to your blog, or download it to the
your-project-folder/wp-content/plugins folder. Then, activate it on the Plugins
page.

== Usage ==

Use the /wp-json/o3-cli-api/url-sources API endpoint to get a list of sources of
URL paths, and use the /wp-json/o3-cli-api/urls API to get a list of URL paths
of posts in the system, using your sources as URL query parameter filters.

Source filters for /wp-json/o3-cli-api/urls:
  - post_types
    - Include a comma-separated list of post type machine names.
  - categories
    - Include a comma-separated list of category slugs.
  - menus
    - Include a comma-separated list of menu slugs.
  - limit
    - Include an integer limit to control the maximum number of URL paths to
    return for each machine name in any source.

An example request:

GET http://example.com/wp-json/o3-cli-api/urls?post_types=post,product&categories=food,travel&menus=main-navigation,footer-menu&limit=50

The above example requests the URL paths of posts of post types with the machine
names of 'post' and 'product', having categories with 'food' and 'travel' slugs,
as well as items in menus with 'main-navigation' and 'footer-menu' slugs. As
with any WordPress REST API endpoints, the above request returns a JSON array.

The O3 CLI automatically generates requests like the above, and it empowers
developers and QA engineers to dynamically generate visual regression tests,
among other needs. See the documentation at
https://www.npmjs.com/package/o3-cli.


== Changelog ==

= 1.0.2 =
*Release Date - 19 December 2019*

* Fixed release naming

= 1.0.1 =
*Release Date - 19 December 2019*

* Fixed release naming

= 1.0.0 =
*Release Date - 19 December 2019*

* Initial release