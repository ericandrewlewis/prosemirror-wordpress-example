# WordPress ProseMirror Example

This plugin is an example of how to use a [ProseMirror](http://prosemirror.net) editor in a WordPress project.

The plugin will create a meta box on the Edit Post page with an editor instance which saves to post meta.

## Tell me more!

ProseMirror is written in JavaScript with features from ES6. If you'd like to customize the JavaScript code, install [Node.js](https://nodejs.org).

In the command line, install JavaScript dependencies

```js
npm install
```

I've included a build script, which will transpile the source in `src/index.js` to `dist/index.js`, and recompile when a file changes. Run this script:

```js
node build.js
```