# Certificates API

This API is built with Codeigniter to handle Certificates info

## Setup

Copy `env` to `.env` and tailor for your app.

## Upload to server

- `public` content in root folder, this one has the domain pointing at it
- the rest of the app content in another folder

#### `public/index.php`:

```
require FCPATH . '../[rest-of-the-app-folder]/app/Config/Paths.php';
```

## Run spark server

```
php spark serve
```
