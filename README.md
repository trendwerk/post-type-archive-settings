Post type archive settings
===============

Settings for post type archives. Made for WordPress.

This plugin uses post types to edit the actual archive settings. This will allow you to easily extend archives with all features available for post types.

## Installation
If you're using Composer to manage WordPress, add this plugin to your project's dependencies. Run:
```sh
composer require trendwerk/post-type-archive-settings 1.0.0
```

Or manually add it to your `composer.json`:
```json
"require": {
	"trendwerk/post-type-archive-settings": "1.0.0"
},
```

## Usage

Add the post type support 'archive-settings' to any post type.

```
'supports' => array( 'title', 'editor', 'revisions', 'archive-settings' )
```

## Extending

If you'd like to extend the archives post type, it's registered under the post type name `_post_type_archives`.
