# Bidhan PHP Framework

Bidhan (stands for Because I Don't Have a Name) is a mini PHP Framework with focus to beautify code. Suitable for personal project and other simple web application. I personally telling you to do not use this for big and expensive project due to I didn't concern to security and performance issue.

## Installation

1. Clone this repository into your local machine

```
git clone https://github.com/haloriyan/bidhan
```

2. Rename `.env.example` to `.env` and change environment configuration as yours.

```
mv .env.example .env && vim .env
```

3. Run the server

```
php canyou serve
```

4. Change your git remote

Due to you clone this repo, make sure you have deleted the default (`origin`) remote in cloned repository and use your own remote.

```
git remote remove origin
git remote add origin {your-remote-url}
```

## Learning

I have created documentation for every helper, either you willing to read it or not.

- [Auth](./docs/Auth.md)
- [Basic Function](./docs/Basic.md)
- [Controller](./docs/Controller.md)
- [Database (Query Builder, Relationship, and Pagination)](./docs/Database.md)
- [Deployment](./docs/Deployment.md)
- [File Storage](./docs/File_Storage.md)
- [Mailing](./docs/Mailing.md)
- [Middleware](./docs/Middleware.md)
- [Migration](./docs/Migration.md)
- [Request](./docs/Request.md)
- [Routes](./docs/Routes.md)
- [Validation](./docs/Validation.md)

But if you can't find some information that you need to do, or you didn't get it yet, feel free to ask to me question.

## Found a issue?

Contribute and join to open-source community by reporting some issues that appears in fresh installation nor when you developing an application. Send me an email to [riyan.satria.619@gmail.com](mailto:riyan.satria.619@gmail.com) to report any issues.

## License

Bidhan is open-sourced software licensed under the MIT license.

## Become a Patron

If you don't mind you can buy me a cup coffee to accompany when I code via [Karyakarsa @belajarngewebid](https://karyakarsa.com/belajarngewebid) or [Trakteer @haloriyan](https://trakteer.id/haloriyan)