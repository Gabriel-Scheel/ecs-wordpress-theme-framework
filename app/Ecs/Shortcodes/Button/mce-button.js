(function() {

    tinymce.PluginManager.add('btn', function( editor, url ) {

        var editor = editor;
        var btn_sh_tag = 'btn';

        //add popup
        editor.addCommand('btn_popup', function(ui, v) {

            //setup defaults
            var link = '';
            if (v.link)
                link = v.link;
            
            var btntext = '';
            if (v.btntext)
                btntext = v.btntext;

            //open the popup
            var win = editor.windowManager.open( {
                title: 'Button',
                id: 'cbButton',
                body: [
                    {//add header input
                        type: 'textbox',
                        name: 'btntext',
                        label: 'Button Text',
                        value: link,
                        tooltip: 'Enter text for button',
                        size: 'large'
                    },
                    {//add header input
                        type: 'textbox',
                        name: 'link',
                        label: 'URL',
                        value: link,
                        tooltip: 'Enter url for button',
                        size: 'large'
                    }
                ],
                onsubmit: function( e ) { //when the ok button is clicked
                    //start the shortcode tag
                    var shortcode_str = '[' + btn_sh_tag;
 
                    if (typeof e.data.link != 'undefined' && e.data.link.length)
                        shortcode_str += ' link="' + e.data.link + '"';

                    if (typeof e.data.btntext != 'undefined' && e.data.btntext.length)
                        shortcode_str += ' btntext="' + e.data.btntext + '"';

                    shortcode_str += ']';
 
                    //insert shortcode to tinymce
                    editor.insertContent(shortcode_str);
                }
            });
        });
 
        //add button
        editor.addButton('btn', {
            icon: 'btn',
            tooltip: 'Insert Button',
            onclick: function() {
                editor.execCommand('btn_popup','',{
                    btntext : '',
                    link : ''
                });
            }
        });
    });
})();