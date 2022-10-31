# Joomla! Cache Exclude Plugin

This plugin is used to exclude pages from Joomla's system cache (Convervative and Progressive caching). This plugin was based on Joomla!'s Cache plugin (`plg_system_cache`).

## Technical Requirements

 * Joomla! 3.10.x or 4.x.x.

## Installation

[Download the latest release](https://github.com/cmextension/joomla-cache-exclude/releases/) and install it in Joomla! back-end.

## Configuration and Usage

You can find the plugin in Joomla!'s plugin list by searching for `System - Cache Exclude`. The plugin has 2 configuration options:

 * **Excluded Menu Items**: The menu items which you want to exclude from caching.
 * **Exclude URLs**: Specify the URLs you want to exclude from caching, each on a separate line. Regular expressions are supported, eg. `about\-[a-z]+` - will exclude all URLs that have 'about-', for example `about-us`, `about-me`, `about-joomla`; or `/component/users/` - will exclude all URLs that have `/component/users/`; or `com_users` - will exclude all Users component pages.