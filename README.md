![PHP VERSION](https://img.shields.io/badge/php-%5E7.4-blue)

# Movie list

Symfony _5.4_ project gathering a list of movies with local notes but also IMDB notes and desciption.

## Indications

To add a film you have to go through the route: `/movie/add`or for a bulk import `/movie/add/bulk`.

If you add a movie that already exists it will just calculate the new average score and save it in the database.

Knowing that normally the batch import is reserved for admins and that movies already contains a descriptions, the app
will not check with the API to not overload it

Also, only the last uploaded poster is kept.

## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

`DATABASE_URL = sqlite:///%kernel.project_dir%/var/data.db`

`OMBDBAPIKEY = 1234`

`ADMIN_CODE = 1234`

## Tech Stack

**Client:** TailwindCSS

**Server:** Symfony, SQLite, Twig, Webpack and Encor

**API:** OMDB

## Deployment

To deploy this project run

```bash
  composer install
  php bin/console doctrine:migrations:migrate
  npm install
  npm run build
```

_Launch dev server_

```bash
  symfony server:start
```

## Author

- [@Lazare_C](https://github.com/Lazare-C)

## Acknowledgements

- [Symfony documentation](https://symfony.com/doc/current/index.html)
- [TailwindCSS documentation](https://tailwindcss.com/docs)

## 🔗 Socials

[![GitHub followers](https://img.shields.io/github/followers/Lazare-C?style=social)](https://github.com/Lazare-C)

[![Twitter Follow](https://img.shields.io/twitter/follow/LazareC_?style=social)](https://twitter.com/LazareC_)

[![Instagram Follow](https://img.shields.io/badge/Follow-Lazare_chr-green?logo=instagram&style=social)](https://www.instagram.com/lazare_chr/)
