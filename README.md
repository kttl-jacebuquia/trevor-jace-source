# Trevor

**Important!** This repository is constructed as a [git submodule](https://git-scm.com/book/en/v2/Git-Tools-Submodules).
It contains all the source code, but it lacks the necessary tools to build/run/deploy.
Please check out first using the [Pantheon](https://pantheon.io)'s git repository with the _`git clone --recurse-submodules`_ command.
You will find this repository under the `source` folder.

```shell script
git clone --recurse-submodules --depth 1 ssh://codeserver.dev.b60ba3a2-cd76-4e32-9990-a5b89450ec59@codeserver.dev.b60ba3a2-cd76-4e32-9990-a5b89450ec59.drush.in:2222/~/repository.git trevor-web
```

## Technologies

- [PHP 7.3](https://www.php.net/releases/7_3_0.php)
- [SASS](https://sass-lang.com)
- [WordPress](https://wordpress.org)
- [TailwindCSS](https://tailwindcss.com)
- [ReactJS](https://reactjs.org)
- Tools
    - [Webpack](https://webpack.js.org)
    - [Lando](https://lando.dev)

## Getting Started

**Requirements**
- Unix-based operating system (Linux or Mac OS X)
- [Lando](https://docs.lando.dev/basics/installation.html) _*_
- [Pantheon](https://pantheon.io) machine token

Start the development environment.
```shell script
lando start
```

Load {code _**_, db, files} from [dev|test|prod] 
```shell script
lando pull
```

Now your development environment should be ready and you should be able to see [http://trevor-web.lndo.site](http://trevor-web.lndo.site)!

_* When going through the Mac OS X installer you can choose to not install **Docker Desktop**,
    if you already have it and/or don't want to change the current version.
    Check the `Customize` section on the `LandoInstaller.pkg`._
    
_** Lando refers to all source code in the running server, including but not limited to WordPress code files, 3rd party plugins, etc.
    When you called `lando start` it will replace the main theme & plugin files with the local ones._


## Project Structure

- ../ : Pantheon Git root. _From now on this folder will be called as `_pantheon`._
  - web/ : WordPress root.
  - ./ : `source` folder. **_(this folder)_** _From now on this folder will be called as `_source`._
    - [plugin/](plugin) Plugin root.
      - [inc/](plugin/inc) NS: `TrevorWP`
      - [lib/](plugin/lib) Composer libraries. _†_
      - [static/](plugin/static)
        - [css/](plugin/static/css) Compiled style files. _†_
        - [icon-font/](plugin/static/icon-font) Compiled icon fonts. _†_
        - [js/](plugin/static/js) Compiled js files. _†_
        - [media/](plugin/static/media) To serve admin side files without webpack.
    - [theme/](theme) Theme root.
      - [inc/](theme/inc) NS: `TrevorWP\Theme`
      - [lib/](theme/lib) Composer libraries. _†_
      - [static/](theme/static)
        - [css/](theme/static/css) Compiled style files. _†_
        - [font/](theme/static/font) Regular fonts.
        - [icon-font/](theme/static/icon-font) Compiled icon fonts. _†_
        - [js/](theme/static/js) Compiled js files. _†_
        - [media/](theme/static/media) To serve frontend side files without webpack.
    - [src/](src)
      - [assets/](src/assets) Assets (SVGs, images, etc.), everything should be loaded by Webpack.
      - [plugin/](src/plugin) Plugin root.
        - [css/](src/plugin/css) Plugin style files. Available on admin side.
        - [js/](src/plugin/js)
          - [blocks/](src/plugin/js/blocks) Gutenberg blocks. Available on editor pages.
          - [main/](src/plugin/js/main) Main plugin entrypoint. Available on admin side.
      - [theme/](src/theme) Theme root.
        - [css/](src/theme/css)
          - [admin/](src/theme/css/admin) Admin styles.
          - [frontend/](src/theme/css/frontend) Frontend styles.
        - [js/](src/theme/js)
          - [admin/](src/theme/js/admin) Admin entrypoint.
          - [frontend/](src/theme/js/frontend) Frontend entrypoint.
    - [fontcustom/](fontcustom) FontCustom vectors & configs. See below for more information.
    - [scripts/](scripts) Node scripts, (e.g. start & build)
    - [config/](config) Webpack configs.

_† : Auto-generated._

## Developers

### Related Services

- [PhpMyAdmin](https://www.phpmyadmin.net) : [http://trevor-phpmyadmin.lndo.site](http://trevor-phpmyadmin.lndo.site)
- [MailHog](https://github.com/mailhog/MailHog) : [http://trevor-mailhog.lndo.site](http://trevor-mailhog.lndo.site)
- [Solr](https://lucene.apache.org/solr/) : Currently, the version of Solr on Pantheon is **v3.6**. [Read More](https://pantheon.io/docs/solr)
- [Redis](https://redis.io) : [Read More](https://pantheon.io/docs/redis)

### Tools

- [composer](https://getcomposer.org)
  - `lando composer-plugin`
  - `lando composer-theme`
- [npm](https://www.npmjs.com)
  - `lando npm`
- [wp-cli](https://wp-cli.org)
  - `lando wp`
- [fontcustom](https://github.com/FontCustom/fontcustom)
  - `lando ifonts`

#### Examples

- Add a npm package: `lando npm install --save react`
- Connect to the appserver: `lando ssh` 
  - Same as `lando ssh -s appserver`
- Connect to the node service: `lando ssh -s node`
  - As root `lando ssh -s node -u root`
- View logs (all services) `lando logs`
  - Continuous mode: `lando logs -f`
  - Single service: `lando logs -s node`
- Stop all containers: `lando stop`
  - Otherwise, they run until you stop them. You can check running containers using: `docker ps`
- Add an admin WP user: `lando wp user create bob bob@example.com --role=administrator`
- Install a composer package: `lando composer-plugin require twig/twig`
- Compile theme icon fonts: `lando ifonts theme`


### Branching

- `develop` The main branch. PRs should be merged to this branch.
- `master` ~~Release branch. Only the `develop` branch & hotfixes can merge.~~ No use for now.

### File Names

- For PHP class files, please follow [PSR-4](https://www.php-fig.org/psr/psr-4/).
- All other file names should be lower-case.[*](https://en.wikipedia.org/wiki/Case_sensitivity#In_filesystems)


### Coding

- There is a [.editorconfig](./.editorconfig) file that applies the [WordPress Coding Standards](https://make.wordpress.org/core/handbook/coding-standards/).
- Usage of `.jsx` extension is [not recommended](https://github.com/facebook/create-react-app/issues/87#issuecomment-234627904).

### Privacy & Security

- Please disable Lando's [Usage Data and Crash Data](https://docs.lando.dev/help/data.html#tl-dr) sharing option.
  That reports may contain folder names and that information can reveal your identity (computer username), and the project you are working on.
- Please use unpredictable passwords for your WP test accounts.
  We may disable password protection on live servers from time to time and bots can mess our staging environment.   

### Exceptions (PHP)

- `TrevorWP\Exception\Error` User error, users see the message. You are responsible to capture this exception.
- `TrevorWP\Exception\Internal` A runtime error. Users only see a generic error message, but it logs the details for further investigation.
- `TrevorWP\Exception\Not_Exist` Generic does not exist exception. You can replace the message.
- `TrevorWP\Exception\Unauthorized` Generic unauthorized exception. You can replace the message.

### Error & Audit Logs (PHP)

There are two logging engines, but they can be accessible through a single interface: `TrevorWP\Util\Log`

**All log levels:** 
```text
debug
info
notice
warning
error
critical
alert
emergency
```

#### Examples

```php
# Log an error
$msg = 'Something unfortunate happened.';
$optional_context = [ 'current_page' => $page_id ];
TrevorWP\Util\Log::error( $msg, $optional_context );

# Audit log
$msg = 'User deleted an image.';
$optional_context = compact('post_id', 'img_id');
TrevorWP\Util\Log::audit($msg, $optional_context);
```


#### Folders

- Development: `_pantheon/logs`
- Production: `/web/wp-content/uploads/private/trevor/logs`

### Icon Fonts

For [monochrome](https://en.wikipedia.org/wiki/Monochrome) icons, we use icon fonts.
It is not different from [FontAwesome](https://fontawesome.com), instead, we build our own pack from the SVGs.

To see current icons, you can use preview files, e.g. theme: `_source/build/icon-font/theme/trevorwp-i-preview.html`

There are two packs, you can use the `plugin` for the admin side and `theme` for the frontend.

To add a new icon, all you have to do is, place the new SVG file into the appropriate vector folder:

- [theme](fontcustom/theme/vectors): `_source/fontcustom/theme/vectors`
- [plugin](fontcustom/plugin/vectors): `_source/fontcustom/plugin/vectors`

To build fonts: `lando ifonts {theme|plugin|all}`

#### Usage

`<ICON_NAME>` is the basename (without extension) of the svg file you want to use.

- `trevor-ti-<ICON_NAME>` Theme icons.
- `trevor-pi-<ICON_NAME>` Plugin icons.

**Class Name**

```html
<i class="trevor-ti-<ICON_NAME>"></i>
```

**SASS @extend**

```css
.bar:before {
  @extend .trevor-ti-<ICON_NAME>:before;
}
```


### Pushing Code/Files/DB to Dev/Test/Live

`lando pull` Allows you to run any live environment _Dev | Test | Live_ on your computer.

In the same way, `lando push` can push to _Dev | Test_ environments. Pushing to _live_ from _local_ is forbidden.

Lastly, `lando switch` allows you to switch between the environments.


#### What are the Code/Files/DB?

**Code**

the `_pantheon\web` folder, omits `wp-content/uploads`.

**Files**

Any uploaded files. Basically the `wp-content/uploads` folder.

**DB**

Whole database.


##### Examples

- If you only added a post, pushing `DB` is sufficient.
- If you added a picture to that post, you should also push `Files`.
- If you only fixed something on plugin/theme then pushing `Code` is sufficient.
- If you want to start from scratch on your local setup, you should pull all.

#### When to push?

If your code needs some demo context you should push the related content with it.

We need to be careful about keeping two Git repos in sync.
`lando push` command doesn't include the submodule hash if it is dirty.
So, please submit your changes on the submodule (this readme's repo) first and stash/clean uncommitted changes before the push.
Deployment scripts work even if you forget do this, but it is important to keep changes in sync for other developers.

Also, you should notify other active developers before pushing to DEV/TEST environments.
If your work needs to be tested live, you can use [Pantheon's Multidev](https://pantheon.io/docs/multidev) feature.
It allows you to create your personal environment, and you can freely push anything to that environment. 


### Xdebug

[Xdebug](https://xdebug.org/) is ready to use but if you need to modify something, 
you can find the [xdebug.ini](docker/appserver/xdebug.ini) at the `_source/docker/appserver/` folder.
To apply changes, re-start lando : `lando restart`.
 
- [Setting up your IDE for XDEBUG](https://docs.lando.dev/config/php.html#setting-up-your-ide-for-xdebug)
- [xdebug settings](https://xdebug.org/docs/all_settings)

#### Path Mappings

- `_pantheon/source/docker/appserver/prepend.php` → `/srv/includes/prepend.php`
- `_pantheon/` → `/app`
- `_pantheon/source/plugin` → `/app/web/wp-content/plugins/trevor`
- `_pantheon/source/theme` → `/app/web/wp-content/themes/trevor`


### Long Term ToDo

- [ ] [Pantheon's Solr plugin](https://github.com/pantheon-systems/solr-power) looks insufficient for our needs.
   We may need to fork it or go with a lower level solution.
   Please do not depend on it.

### License Attributions

- [FontAwesome](https://fontawesome.com/license)

---

Forged in [✖ Kettle](https://wearekettle.com) with lots of ❤!
