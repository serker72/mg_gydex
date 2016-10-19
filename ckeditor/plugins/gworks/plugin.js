CKEDITOR.plugins.add( 'gworks', {
    icons: 'gworks',
    init: function( editor ) {
        //Plugin logic goes here.
		
		editor.addCommand( 'insertGworks', {
			exec: function( editor ) {
				//var now = new Date();
				//editor.insertHtml( '<div class="gydex_works">GYDEX.������.��������!</div>' );
				
				
				 var selected_text = editor.getSelection().getSelectedText(); // Get Text
				  var newElement = new CKEDITOR.dom.element("div");              // Make Paragraff
				  newElement.setAttributes({class: 'gydex_works'})                 // Set Attributes
				  newElement.setText(selected_text);                           // Set text to element
				  editor.insertElement(newElement);   
			}
		});
		
		editor.ui.addButton( 'Gworks', {
			label: '�������� div class=gydex_works',
			command: 'insertGworks'/*,
			toolbar: 'My'*/
		});
    }
});
 