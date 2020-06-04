(function (wp) {
    // get all required elements from wordpress
    const registerPlugin = wp.plugins.registerPlugin;
    const PluginSidebar = wp.editPost.PluginSidebar;
    const el = wp.element.createElement;
    const {Button, SelectControl, CheckboxControl} = wp.components;
    const {select, dispatch} = wp.data;

    // requests to yandex
    const translateContent = new XMLHttpRequest();
    const translateTitle = new XMLHttpRequest();
    const getLanguages = new XMLHttpRequest();

    // get saved options
    const apiKey = options.easytranslate_field_api;
    let langFrom = options.easytranslate_lang_1;
    let langTo = options.easytranslate_lang_2;

    // Language list functionality
    let overrideSettings;

    // requests for title and content (for the content the blocks must be reset)
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
            console.log(languageList)

        } else {
            console.log(translateContent);
        }
    };

    // get available languages
    const languageList = [];
    getLanguages.onload = function () {
        if (getLanguages.status >= 200 && getLanguages.status < 300) {
            let response = JSON.parse(getLanguages.response);
            let languages = response.langs;
            for (let key of Object.keys(languages)) {
                languageList.push({
                    label: languages[key],
                    value: key
                });
            }
        } else {
            console.log(translateContent);
        }
    };
    getLanguages.open('GET', 'https://translate.yandex.net/api/v1.5/tr.json/getLangs?' +
        'key=' + apiKey +
        '&ui=en'
    );
    getLanguages.send();

    // render the sidebar
    registerPlugin('easytranslate-sidebar', {
        render: function () {
            return el(PluginSidebar,
                {
                    name: 'easytranslate-sidebar',
                    icon: 'translation',
                    title: 'Easy Translate',
                },

                // translate button section
                el('div',
                    {className: 'easytranslate-sidebar-button'},
                    el(Button, {
                        isPrimary: true,
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
                ),

                // language section
                el('div',
                    {className: 'easytranslate-sidebar-languages'},

                    // choose if override the main settings or choose new languages for this post
                    el(CheckboxControl, {
                        heading: 'User',
                        label: 'Is Autor',
                        help: 'Is the user a author or not?',
                        onChange: function (checked) {
                            overrideSettings = checked;
                        }
                    }),

                    // select to choose the language from
                    el(SelectControl, {
                        label: 'Language 1',
                        value: 'value',
                        options: [languageList],
                        onChange: function (value) {
                            console.log('language 1: ' + value)
                        }
                    }),

                    // language to choose the language to
                    el(SelectControl, {
                        label: 'Language 1',
                        value: 'value',
                        options: [languageList],
                        onChange: function (value) {
                            console.log('language 2: ' + value)
                        }
                    })
                )
            );
        },
    });
})(window.wp);