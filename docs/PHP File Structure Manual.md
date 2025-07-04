# Assets
Assets holds the assets used by the web application in the same level. it could be used by the `index.html` or `index.php` file.
Can be compose of the following but not limited to; `css`, `js`, `images`, `fonts`, etc.

Each asset should be placed in its own folder, for example:
- `assets/css`, `assets/js`, `assets/images`, etc.

Each file inside can have their own name, for example:
- `assets/css/header.css` which is the CSS file for the header component.
- `assets/js/carousel.js` which is the JavaScript file for the carousel component.

# Components
Components are reusable parts of the web application. They can be used in multiple places and can have their own styles and scripts.
Components should be placed in the `components` folder, and each component should have its own folder. For example:
- `components/card`, `components/carousel`, etc.

This work as holder if there is variations of components, for example:
- `components/card/primary`, `components/card/secondary`, etc.
- `components/carousel/slider`, `components/carousel/thumbnails`, etc.

## Template
Template is a special type of component that is used to render the main layout of the web application. It can be used to define the structure of the page, such as the header, footer, and main content area.
It is also possible to create folder for variations of the template, for example:
- `components/template/header` which can hold the header component variations.
    - `components/template/header/admin` for the admin header.
    - `components/template/header/user` for the user header.

# Docs
Docs is a folder that holds the documentation for the web application. It can be used to document the components, assets, and other parts of the web application.

# Handlers
Handlers are the PHP files that handle the requests and responses of the web application. They can be used to process the data, interact with the database, and return the response to the client.

There is also possible to create subfolders for the handlers, for example:
- `handlers/api` for the API handlers.
- `handlers/weatherApi` for the weather API handlers, which is external api.

# Layout
Layout is a folder that holds the layout files for the web application. It can be used to define the structure of the page, such as the header, footer, and main content area.
It can also be used to define the styles and scripts that are used in the web application. The layout files can be used to define the structure of the page, such as the header, footer, and main content area.

# Pages
Pages are the main content of the web application. They can be used to display the data, interact with the user, and return the response to the client.

## Page
Page is a folder that holds the pages of the web application. It is using layout files to define the structure of the page then contain inside the layout the content of the page.

## Assets
Each page can have its own assets, such as styles and scripts. These assets can be placed in the `assets` folder inside the page folder.

# Static Data
Static data is a folder that holds the static data for the web application. It can be used to store the data that is used in the web application, such as the data for the components, pages, and other parts of the web application.

# Utils
Utils is a folder that holds the utility files for the web application. It can be used to store the functions and classes that are used in the web application, such as the functions for formatting the data, interacting with the database, and other utility functions.