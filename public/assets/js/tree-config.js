// const { forEach } = require("lodash");

$(document).ready(function () {
    var data = $('#treeData').data('tree');
    $('#tree').jstree({
        'core': {
            'data': data,
            'check_callback': true,
            'themes': {
                'responsive': true,
                'variant': 'large'
            }
        },
        'plugins': ['types', 'wholerow', 'search', 'state'],
        'types': {
            'default': {
                'icon': 'fa fa-folder'
            },
            'home': {
                'icon': 'fa fa-home'
            }
        },
        'state': {
            'key': 'category-tree-state',
            'opened': true
        }
    }).on('loaded.jstree', function () {
        $('#tree').jstree('open_all');
    });
});
