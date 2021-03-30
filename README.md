# Purdue Branding Plugin

Used to add Purdue University brand fonts, favicons and branded login form to WordPress websites

## Description

Provide a consistent method of managing the Purdue brand across multiple WordPress sites.

## Installation

1. Upload the directory brandfonts and all its contents to the /wp-content/mu-plugins/ directory
2. RECOMMEND that you include [bedrock-autoloader plugin](https://github.com/roots/bedrock-autoloader) to support Must Use plugin install in subfolders

### or with Composer

```
{
  "repositories": [{
    "type": "composer",
    "url": "https://purdue.github.io"
  }]
}

"require": {
    "purdue/boilerup-wp": "*"
}
```

## Change Log
#### [1.8.0] - 2020-03-30
- ADD: Brand fonts added to admin pages

#### [1.7.3] - 2020-03-18
- ADD: Admin Settings options
- ADD: Remove "tests" when installed on internal Purdue network
- UPDATED: Code logic and Segment file location

#### [1.6.0] - 2020-03-17
- UPDATE: File location fix for internal Purdue install

#### [1.5.1] - 2020-03-12
- UPDATE: Disable Segment Customizations

#### [1.3.0] - 2020-04-21
- ADD: Source Serif Pro font replaces Farnham Text

#### [1.2.0] - 2020-04-09
- ADD: Branded Login Page

#### [1.1.0] - 2020-03-22
- ADD: Favicon set
- ADD: Composer support

#### [1.0.0] - 2020-01-31
- Initial Release of Brandfonts
