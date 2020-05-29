(function (wp) {
    let registerPlugin = wp.plugins.registerPlugin;
    let PluginSidebar = wp.editPost.PluginSidebar;
    let el = wp.element.createElement;
    let Button = wp.components.Button;
    const {select, dispatch} = wp.data;
    let translateContent = new XMLHttpRequest();
    let translateTitle = new XMLHttpRequest();
    const apiKey = options.easytranslate_field_api;
    const langFrom = options.easytranslate_lang_1;
    const langTo = options.easytranslate_lang_2;

    translateTitle.onload = function () {
        if (translateTitle.status >= 200 && translateTitle.status < 300) {
            let response = JSON.parse(translateTitle.response);
            let translatedData = response.text[0];

            dispatch('core/editor').editPost({title: translatedData});
        } else {
            console.log(translateTitle);
        }
    };

    translateContent.onload = function () {
        if (translateContent.status >= 200 && translateContent.status < 300) {
            let response = JSON.parse(translateContent.response);
            let translatedData = response.text[0];

            dispatch('core/editor').editPost({content: translatedData});
            dispatch('core/block-editor').resetBlocks(wp.blocks.parse(translatedData));

        } else {
            console.log(translateContent);
        }
    };

    registerPlugin('easytranslate-sidebar', {
        render: function () {
            return el(PluginSidebar,
                {
                    name: 'easytranslate-sidebar',
                    icon: 'translation',
                    title: 'Easy Translate',
                },
                el('div',
                    {className: 'easytranslate-sidebar-content'},
                    el(Button, {
                        isPrimary: true,
                        label: 'Meta Block Field',
                        value: 'Initial value',
                        onClick: function () {
                            const titleRaw = select('core/editor').getEditedPostAttribute('title');
                            const title = encodeURI(titleRaw);
                            const contentRaw = select('core/editor').getEditedPostAttribute('content');
                            const content = encodeURI(contentRaw);

                            translateContent.open('GET', 'https://translate.yandex.net/api/v1.5/tr.json/translate?' +
                                'key=' + apiKey +
                                '&text=' + content +
                                '&lang=' + langFrom + '-' + langTo +
                                '&format=html'
                            );
                            translateContent.send();

                            translateTitle.open('GET', 'https://translate.yandex.net/api/v1.5/tr.json/translate?' +
                                'key=' + apiKey +
                                '&text=' + title +
                                '&lang=' + langFrom + '-' + langTo +
                                '&format=html'
                            );
                            translateTitle.send();
                        },
                    }, "Translate your content")
                )
            );
        },
    });
})(window.wp);