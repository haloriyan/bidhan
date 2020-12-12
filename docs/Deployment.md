# Deployment

After develop the app as you want to be, of course you will deploy your code into a server like vps or shared hosting. And here is guide to deploy your code into;

### VPS or Cloud Engine (like EC2 or GCE)

- Zip all of your code and extract to `/var/www/` in your engine; or
- Push to git repo and then clone from `/var/www/`
- Rename `public/` to be `html/`
- Change `BASE_URL` in `.env` with your new base url (example: `https://belajarngeweb.id`)
- Update your new database configuration in `.env` with your new database environment

### Shared Hosting

- Zip all of your code, upload, and extract to `/home/` in File Manager; or
- Push to git repo and then clone from `/home/` (if your hosting service has git ability)
- Rename `public/` to be `public_html/`
- Change `BASE_URL` in `.env` with your new base url (example: `https://belajarngeweb.id`)
- Update your new database configuration in `.env` with your new database environment

## How about the database?

You can export from local and import to server the `*.sql` file manually if you want to keep whole records. But if you don't, you can migrate the migration with steps that had explained in [Migration](./Migration.md#migrate)