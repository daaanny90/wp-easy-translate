# Easy Translate WordPress Plugin
Easy Translate allows you to write in your motherthongue and to publish your posts and pages already translated.

## How it works
The plugin works the free API of Yandex. Yandex has a free plan until 1.000.000 charachters/month.
For a free-time blogger like me, is more than enough. If this limit is too thight for you, you can simply subscribe to their pro plan.

The plugin consists in a simple filter hook, "wp_insert_post_data", that translates the post content and post title just before being saved into the database. This allow you to save your content already translated.

You just have to:
1. Install and activate the plugin
2. [Get a Yandex free API key](https://translate.yandex.com/developers/keys)
3. Insert the API key into the plugin settings

From now, every post and page you will write, will magically posted translated.
Following the [Yandex requirements for the use of translation results](https://tech.yandex.com/translate/doc/dg/concepts/design-requirements-docpage/) a text is placed at the end of every translated post and page.

## THIS IS STILL A WORK IN PROGRESS
At the moment the plugin will only translate from italian to english. There is still a lot of work to do:

### TO-DO
- [ ] Add other languages in settings menu
- [ ] Tranlsate also the permalink
- [x] Let the user choose if translate the post/page or not

### BUGS
- [ ] The filter is fired two times on publication
- [ ] Customizer can't save changes
