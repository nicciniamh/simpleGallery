# Simple Gallery
Simple Gallery is a simple photograpic gallery that supports any number of sub-albums. 
[Demo](http://quack.ducksfeet.com/sgdemo)

## Features
* Simple photo management and descriptions
* Simple customization of page headers
* Popup image viewing via colorbox
* Touch friendly on mobile devices via touchSwipe
* Enhanced mouse navigation of colorbox popup. 

## Requirements
* Apache Web Server that has PHP and GD enabled. (for thumbnailer)
* PHP 5.0+
* jquery [http://code.jquery.com/](http://code.jquery.com/)
* jquery-colorbox (image display and slideshow) [http://www.jacklmoore.com/colorbox/](http://www.jacklmoore.com/colorbox/)
* jquery-touchSwipe (for touch events) [https://github.com/mattbryson/TouchSwipe-Jquery-Plugin](https://github.com/mattbryson/TouchSwipe-Jquery-Plugin)

### Colorbox Notes
Colorbox is distributed in a folder. To achieve what I have, I have it installed in *root*/js as jquery.colorbox.js and the images and css files are moved to js from the example folders. 

Other jquery code can be downloaded to the js/ folder. 

## Setup 
places all of the folders in the root of where you wish your galleries to be stored.

Edit the inifile, **sgallery.ini**, to match your site.

```[main]
base_dir = '/galleries'
lib_dir = '/galleries/lib'


[topindex]
title = "My Photos";
heading = 'My Photos';
subheading = "file:descript.htm"
```
You will notice the subheading entry. This is used on the main index and it may be text or it may be file:filename.ext which will be included in the HTML under the main heading and before the album thumbnails. 

## Thumbnailing
Thumbnails are generated to the thumbs directory and it must be writeable by the webserver. The script, **thumb.php**, generated the thumbnails using mod-rewrite. The file **lib/thumbs.htaccess** must be copied to **thumbs/.htaccess**. The server must be configured to allow overrides so this will work. 
The basic operation of thumbnailing is that the server looks for the image in the thumbs directory. If found, it serves the file, otherwise it calls **thumbs.php** to generate and then display the thumbnail. Once this is done, the thumbnail will simply be served by the server. 

## Creating an Album
1. Make a directory under albums with the name of the album you want. It may also be made in an existing album. 
2. Create a file called album.dsc with single line describing the album. 
3. For each file add a .dsc file descrribing the pictures. e.g.: DSC_2341.jpg would be descrubed by DSC_2341.jpg.dsc (If no corresponding .dsc file exists for an image, the gallery title is used.)
4. That's it!

It's a good idea to load your new gallery in a web browser to have the thumbnailer create thumbnails.
