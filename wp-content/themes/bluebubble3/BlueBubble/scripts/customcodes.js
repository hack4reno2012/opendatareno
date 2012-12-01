(function() {
    tinymce.create('tinymce.plugins.alert', {
        init : function(ed, url) {
            ed.addButton('alert', {
                title : 'Add an alert box',
                image : url+'/images/tinymce-alert.png',
                onclick : function() {
                     ed.selection.setContent('[alert]' + ed.selection.getContent() + '[/alert]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('alert', tinymce.plugins.alert);
})();

(function() {
    tinymce.create('tinymce.plugins.dload', {
        init : function(ed, url) {
            ed.addButton('dload', {
                title : 'Add a download box',
                image : url+'/images/tinymce-download.png',
                onclick : function() {
                     ed.selection.setContent('[dload]' + ed.selection.getContent() + '[/dload]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('dload', tinymce.plugins.dload);
})();


(function() {
    tinymce.create('tinymce.plugins.info', {
        init : function(ed, url) {
            ed.addButton('info', {
                title : 'Add an info box',
                image : url+'/images/tinymce-info.png',
                onclick : function() {
                     ed.selection.setContent('[info]' + ed.selection.getContent() + '[/info]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('info', tinymce.plugins.info);
})();


(function() {
    tinymce.create('tinymce.plugins.idea', {
        init : function(ed, url) {
            ed.addButton('idea', {
                title : 'Add an idea box',
                image : url+'/images/tinymce-idea.png',
                onclick : function() {
                     ed.selection.setContent('[idea]' + ed.selection.getContent() + '[/idea]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('idea', tinymce.plugins.idea);
})();

(function() {
    tinymce.create('tinymce.plugins.gbutton', {
        init : function(ed, url) {
            ed.addButton('gbutton', {
                title : 'Add a green button',
                image : url+'/images/tinymce-gbutton.png',
                onclick : function() {
                     ed.selection.setContent('[gbutton url="http://"]' + ed.selection.getContent() + '[/gbutton]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('gbutton', tinymce.plugins.gbutton);
})();

(function() {
    tinymce.create('tinymce.plugins.gbutton', {
        init : function(ed, url) {
            ed.addButton('gbutton', {
                title : 'Add a green button',
                image : url+'/images/tinymce-gbutton.png',
                onclick : function() {
                     ed.selection.setContent('[gbutton url="http://"]' + ed.selection.getContent() + '[/gbutton]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('gbutton', tinymce.plugins.gbutton);
})();

(function() {
    tinymce.create('tinymce.plugins.gbutton', {
        init : function(ed, url) {
            ed.addButton('gbutton', {
                title : 'Add a green button',
                image : url+'/images/tinymce-gbutton.png',
                onclick : function() {
                     ed.selection.setContent('[gbutton url="http://"]' + ed.selection.getContent() + '[/gbutton]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('gbutton', tinymce.plugins.gbutton);
})();

(function() {
    tinymce.create('tinymce.plugins.gbutton', {
        init : function(ed, url) {
            ed.addButton('gbutton', {
                title : 'Add a green button',
                image : url+'/images/tinymce-gbutton.png',
                onclick : function() {
                     ed.selection.setContent('[gbutton url="http://"]' + ed.selection.getContent() + '[/gbutton]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('gbutton', tinymce.plugins.gbutton);
})();

(function() {
    tinymce.create('tinymce.plugins.bbutton', {
        init : function(ed, url) {
            ed.addButton('bbutton', {
                title : 'Add a blue button',
                image : url+'/images/tinymce-bbutton.png',
                onclick : function() {
                     ed.selection.setContent('[bbutton url="http://"]' + ed.selection.getContent() + '[/bbutton]');

                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('bbutton', tinymce.plugins.bbutton);
})();