# Serve The Base :snowman:

Serve The Base is a WordPress parent theme that allows for rapid and highly-customized REST API creation. It parses a JSON file in your child theme and generates all of your custom post types, taxonomies, and meta fields for you.

STB is designed to be completely front-end agnostic. You can build a full front-end in your child theme, or you can use your WordPress site as the backend for a fully decoupled CMS.

## What It Does :zap:

STB will register all custom post types, taxonomies, and fields specified in the schema file. It will create all necessary REST endpoint as well as include data from your custom fields as part of responses.

STB makes absolutely no decisions about the front-end of your site, but should be used with a child theme if you want to use WordPress for the front-end of your site. If you try using STB as a theme directly, it will yell at you.

## Getting Started :fire:

* Download a zip of the theme and install it like normal
* `cd` to the theme directory and run `composer install` – this assumes [you have composer itself installed](https://getcomposer.org/doc/00-intro.md)
* Make a new directory in your theme directory – this will be your child theme. In this theme, create two files: `functions.php` and `style.css`. In style.css add the following:

```css
/*
Theme Name: <your theme name>
Template: <directory where STB installed>
*/
```

* Create a file named `schema.json` in your child theme. You can start with the contents of `schema-sample.json` in this repo, or start from scratch

## Schema.json :pencil:

A sample `schema.json` file is provided with this project that can be used as a reference. Your file should look something like this:

```
{
  "types": [
    { 
      "name":      string | required
      "plural":    string | optional – provide only for non-standard pluralizations (e.g. "radii", "children")
      "supports":  array<string> | optional – used as "supports" argument when registering CPT
      "args":      object | optional – non-default args to be used when registering CPT
    }
  ],
  "taxonomies: [
    {
      "name":      string | required
      "plural":    string | optional – provide for non-standard pluralizations
      "describes": array<string>|string | optional – post type(s) that the taxonomy will be associated with. default "post"
      "args":      object | optional – non-default args to be used when registering custom taxonomy
    }
  ],
  "field_groups: [
    {
      "name":        string | required
      "belongs_to":  array<string>|string|"all" – post type(s) that the field group will belong to. default "post"
      "fields": {    array<object> | required
        {
          "name":    string | required 
          "type":    string | optional – defaults to "text_small"
          "options": array<string> | optional
        }
      }
    }
  ]
}
```

Referring to the documentation for registering [custom post types](https://codex.wordpress.org/Function_Reference/register_post_type), [custom taxonomies](https://codex.wordpress.org/Function_Reference/register_taxonomy), and [CMB2 fields](https://github.com/CMB2/CMB2/wiki/Field-Types) may be helpful.

## On the radar :eyes:

This project is in a production-ready state, but I'll keep it maintained should anything in WordPress core or in CMB2 change. If anyone wants to put together a fork which works with ACF, go for it!

## Thanks :kissing_heart:

This project wouldn't be possible without [CMB2](https://github.com/CMB2/CMB2).
