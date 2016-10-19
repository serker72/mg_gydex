CKEDITOR.plugins.add( 'classgy', {
    icons: 'classgy',
    init: function( editor ) {
        //Plugin logic goes here.
		
		editor.addCommand( 'insertClassgy', {
			exec: function( editor ) {
				//var now = new Date();
				//editor.insertHtml( '<div class="gydex_works">1GYDEX.Просто.Работает!</div>' );
				
				 var selected_text = editor.getSelection().getSelectedText(); // Get Text
				  var newElement = new CKEDITOR.dom.element("span");              // Make Paragraff
				  newElement.setAttributes({class: 'gydex'})                 // Set Attributes
				  newElement.setText(selected_text);                           // Set text to element
				  editor.insertElement(newElement);   
			}
		});
		
		editor.ui.addButton( 'Classgy', {
			label: 'вставить span class=gydex',
			command: 'insertClassgy'/*,
			toolbar: 'My'*/
		});
		
		/*editor.attachStyleStateChange( style, function( state ) {
			!editor.readOnly && editor.getCommand( commandName ).setState( state );
		} );*/
    }
});