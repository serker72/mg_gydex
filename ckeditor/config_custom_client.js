/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	
	CKEDITOR.config.toolbar_Mymy =
	[
		 
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		 
		['Smiley'],
		
		['TextColor','BGColor'],
		 
	];
	
	CKEDITOR.config.toolbar = 'Mymy';
	
	
	
	
	CKEDITOR.config.width = '600px';
	CKEDITOR.config.height = '150px';
	CKEDITOR.config.allowedContent = true;
	
};
