# Easy Translate WordPress Plugin
Easy Translate allows you to write in your native language and to publish your posts and pages already translated.

## How it works
The plugin works with the free API of Yandex.

Yandex has a free plan until 1.000.000 characters/month.
For a free-time blogger like me, is more than enough. If this limit is too tight for you, you can simply subscribe to their pro plan.

The plugin consists in a simple filter hook, "wp_insert_post_data", that translates the post content and post title just before being saved into the database. This allow you to save your content already translated.

## Setting page
Once installed and activated, a new menu item "Easy Translate" will appear in your admin sidebar.
From there you can set the plugin.

![setting page of easy translate](./screenshots/menu_settings.png)

1. You can get a free Yandex API [here](https://translate.yandex.com/developers/keys)
2. You can choose wether always keep the translation activated as default. If deactivated, you have to activate it every time if you want your content to be translated. You can do it activating the switch at the end of your post/page in the editor.![switch in editor page](./screenshots/switch_editor.png)
3. Choose your languages

From now, every post and page you will write, will magically posted translated.
Following the [Yandex requirements for the use of translation results](https://tech.yandex.com/translate/doc/dg/concepts/design-requirements-docpage/) a text is placed at the end of every translated post and page.

## THIS IS STILL A WORK IN PROGRESS
At the moment the plugin will only translate from italian to english. There is still a lot of work to do:

### TO-DO
- [x] Add other languages in settings menu
- [ ] Tranlsate also the permalink
- [x] Let the user choose if translate the post/page or not

### BUGS
- [ ] The filter is fired two times on publication
- [ ] Customizer can't save changes
