# Asset Lockdown plugin for Craft CMS 3.x

Extends Craft CMS permissions to allow locking down access to the Assets Page but still allow uploading.

This can be useful in multi-site setups where Asset Fields are configured to upload to subfolders based on the site handle and you do not want users viewing or accessing assets outside of their site.

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

```bash
composer require oscarbarrett/craft-asset-lockdown
```

Then install the plugin from the Control Panel or via the CLI.

## Usage

This plugin adds a permission to `View the Assets Page`. Once installed, you'll need to enable this permission for any non-admin user groups who still need to access the Assets page.

Only administrators or users who have been granted this permission will see the Assets link in the CP navigation, and users without this permission are forbidden from accessing the assets page directly.

Users will still be able to upload files to Asset Volumes via Asset Fields (assuming they have the correct volume permissions).
