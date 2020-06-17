(function (wp) {
    // get all required elements from wordpress
    const registerPlugin            = wp.plugins.registerPlugin;
    const PluginSidebar             = wp.editPost.PluginSidebar;
    const el                        = wp.element.createElement;
    const {Button, SelectControl}   = wp.components;
    const {select, dispatch}        = wp.data;

    // requests to yandex
    const translateContent  = new XMLHttpRequest();
    const translateTitle    = new XMLHttpRequest();
    const getLanguages      = new XMLHttpRequest();

    // get saved options
    const apiKey    = options.easytranslate_field_api;
    let langFrom    = options.easytranslate_lang_1;
    let langTo      = options.easytranslate_lang_2;

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
            languageList.push({
                label: 'Loading...',
                value: 'ld'
            });
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

                    // select to choose the language from
                    el(SelectControl, {
                        className: 'LanguageFromSelect',
                        label: 'Language 1',
                        value: langFrom,
                        options: languageList.map(option => ({label: option.label, value: option.value})),
                        onChange: function (selectedItem) {
                            langFrom = selectedItem;
                        }
                    }),

                    // language to choose the language to
                    el(SelectControl, {
                        label: 'Language 2',
                        value: langTo,
                        options: languageList.map(option => ({label: option.label, value: option.value})),
                        onChange: function (selectedItem) {
                            langTo = selectedItem;
                        }
                    }),

                    // invert language 1 with language 2
                    el(Button, {
                        isPrimary: true,
                        onClick: function () {
                            [langTo, langFrom] = [langFrom, langTo];
                        }
                    }, 'Invert')
                )
            );
        },
    });
})(window.wp);