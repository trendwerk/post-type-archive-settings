Post type archive settings
===============

Settings for post type archives. Made for WordPress.

This plugin uses post types to edit the actual archive settings. This will allow you to easily extend archives with all features available for post types.

## Installation
```sh
composer require trendwerk/post-type-archive-settings
```

## Usage

Add the post type support 'archive-settings' to any post type.

```php
'supports' => array( 'title', 'editor', 'revisions', 'archive-settings' )
```

## API

```php
get_archive_settings( $post_type )
```

**$post_type**
Name of the post type.

## Extending

If you'd like to extend the archives post type, it's registered under the post type name `_post_type_archives`.
