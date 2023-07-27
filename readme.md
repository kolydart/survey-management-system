# survey

Α Survey Management System for conducting quantitative research using questionnaires.

The application can be used also as a learning platform used to fill online quizzes and tests for online teaching 

You may view a real-world deployment of the software here:
https://survey.music.uoa.gr/


## Deployment

1.  Extract the archive and put it in the folder you want

2.  Run `cp .env.example .env` file to copy example file to `.env`. 
    Then edit your `.env` file with DB credentials and other settings.

3.  Run `composer install` command

4.  Run `php artisan migrate --seed` command.
    Notice: seed is important, because it will create the first admin user for you.

5.  Run `php artisan key:generate` command.

6.  Run `php serve` command.

And that's it, go to `http://localhost:8000/admin` and login:

### Default credentials

Username: `admin@admin.com`

Password: `password`

### Demo deployment

You may view a demo deployment with fake data here:
http://demo-survey.kolydart.gr/

Use the same credentials as above.

Database data are reset each day.

## License

This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.