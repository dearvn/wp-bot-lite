# WP Land Lite
A simple project to manage real estate for agency to work in WordPress plugin development using WordPress Rest API, WP-script, React, React Router, Tailwind CSS, PostCSS, Eslint, WP-Data, WP-Data Store, React Components, React CRUD, i18n, PHPUnit Test, JestUnit Test, e2e Test, Gutenberg blocks and PHP OOP plugin architecture easily in a minute.

----

## What's included?

1. WordPress Rest API
2. WP-script Setup
3. React
4. React Router
5. TypeScript
6. Tailwind CSS [Nested + ]
7. Scss
8. PostCSS
9. Eslint
10. WP-Data
11. WP-Data Redux Store [Redux Saga, Generator function, Thunk, Saga Middleware]
12. React Components
13. React CRUD Operations - Create, Reade, Update, Delete, Status changes and so many...
14. Internationalization - WP i18n
15. PHPUnit Test [Test + Fix]
16. JestUnit Test
17. Jest-Pupetter e2e Test
18. PHP OOP plugin architecture [Traits + Interfaces + Abstract Classes]
19. Gutenberg blocks

### Quick Start
```sh
# Clone the Git repository
git clone https://github.com/dearvn/land-lite.git

# Install PHP-composer dependencies [It's empty]
composer install

# Install node module packages
npm i

# Start development mode
npm start

# Start development with hot reload (Frontend components will be updated automatically if any changes are made)
npm run start:hot

# To run in production
npm run build
```

After running `start`, or `build` command, there will be a folder called `/build` will be generated at the root directory.

### Activate the plugin
You need activate the plugin from plugin list page.
http://[domain]/wp-admin/plugins.php

### Zip making process [Build, Localization, Version replace & Zip]
```sh
# One by one.
npm run build
npm run makepot
npm run version
npm run zip

# Single release command - which actually will run the above all in single command.
npm run release
```

After running `release` command, there will be a folder called `/dist` will be generated at the root directory with `wp-land-lite.zip` project files.


### Run PHP Unit Test

```sh
composer run test
```

### Run all tests by single command - PHPCS, PHPUnit

```sh
composer run test:all
```

### Run Jest Unit Test

```sh
npm run test:unit
```

### Run Jest-Pupeteer e2e Test

WordPress core doc link: https://make.wordpress.org/core/2019/06/27/introducing-the-wordpress-e2e-tests/

**Requirements:**
- Must have docker installed and running by ensuring these commands -
```
npm run env:stop
npm run env:start
```

**Normal e2e test**
```sh
npm run test:e2e
```

**Interactive e2e test**
```sh
npm run test:e2e:watch
```

### PHP Coding Standards - PHPCS

**Get all errors of the project:**
```sh
composer run phpcs
```

**Fix all errors of the project:**
```sh
composer run phpcbf
```

**Full Composer test run:**
```sh
composer run test:all
```

### Browse Plugin

http://[domain]/wp-admin/admin.php?page=landlite#/

### REST API's

#### REST API Documentation

1. **Product Types**
    - Method: `GET`
    - URL: http://[domain]/wp-json/product-real-estate/v1/product-types
1. **Cities dropdown**
    - Method: `GET`
    - URL: http://[domain]/wp-json/product-real-estate/v1/cities/dropdown
1. **Product Lists**
    - Method: `GET`
    - URL: http://[domain]/wp-json/product-real-estate/v1/products
1. **Product Details**
    - Method: `GET`
    - URL By ID: http://[domain]/wp-json/product-real-estate/v1/products/1
    - URL By Slug: http://[domain]/wp-json/product-real-estate/v1/products/first-product
1. **Create Product**
    - Method: `POST`
    - URL: http://[domain]/wp-json/product-real-estate/v1/Products
    - Body:
    ```json
    {
        "title": "Simple Product Post",
        "slug": "simple-product-post",
        "description": "Simple product post description",
        "city_id": 1,
        "product_type_id": 2,
        "is_active": 1
    }
    ```
1. **Update Product**
    - Method: `PUT`
    - URL: http://[domain]/wp-json/product-real-estate/v1/products/1
    - Body:
    ```json
    {
        "title": "Simple Product Post Updated",
        "slug": "simple-product-post-updated",
        "description": "Simple product post description",
        "city_id": 1,
        "product_type_id": 2,
        "is_active": 1
    }
    ```
1. **Delete Products**
    - Method: `DELETE`
    - URL: http://[domain]/wp-json/product-real-estate/v1/products
    - Body:
    ```json
    {
        "ids": [1, 2]
    }
    ```

**Detailed Documentation** -
[View Detailed documentations with parameters and responses of the REST API](https://github.com/dearvn/land-lite/blob/main/Rest-API-Docs.MD)

### Version & Changelogs
**v0.0.1 - 05/12/2022**

1. New Feature : Product Create.
2. New Feature : Product Update.
3. New Feature : Product Delete.
4. New Feature : Product Status change.
5. New API: City dropdown list.
6. New: Updated logo icon and plugin name.
7. New Components: Input Text-Editor, Improved design.
8. Refactor: Refactored codebase and updated docs.
9. New: Product type seeder.
10. Chore: Zip file generator.
11. Chore: i18n localization generator.

<details>
    <summary>Options for specific files:</summary>

**Get specific file errors of the project:**
```sh
vendor/bin/phpcs product-real-estate.php
```


**Fix specific file errors of the project:**
```sh
vendor/bin/phpcbf product-real-estate.php
```
</details>

### Versions
<details>
    <summary>Simple Version with raw PHP</summary>

https://github.com/dearvn/land-lite/releases/tag/vSimple
</details>

<details>
    <summary>Version with EsLint and i18n Setup</summary>

https://github.com/dearvn/land-lite/releases/tag/vSimpleEslint
</details>


<details>
    <summary>Version with EsLint, i18n and React Router Setup</summary>

https://github.com/dearvn/land-lite/releases/tag/vReactRouter
</details>

<details>
    <summary>Version with PostCSS and Tailwind CSS Setup</summary>

https://github.com/dearvn/land-lite/releases/tag/vTailwindCss
</details>

<details>
    <summary>Version with PHPCS setup</summary>

https://github.com/dearvn/land-lite/releases/tag/vPHPCS
</details>

<details>
    <summary>Version with PHP OOP Architecture</summary>

https://github.com/dearvn/land-lite/releases/tag/vPhpOOP
</details>

 ## Gutenberg blocks
 Inside `src/blocks` you'll find gutenberg block for ready block setup -


## Contribution

Contribution is open and kindly accepted. Before contributing, please check the issues tab if anything in enhancement or bug. If you want to contribute new, please create an issue first with your enhancement or feature idea.
Then, fork this repository and make your Pull-Request. I'll approve, if everything goes well.

## Contact
Find me at donald.nguyen.it@gmail.com
