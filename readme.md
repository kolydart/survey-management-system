# survey

**Α Survey Management System for conducting quantitative research using questionnaires.**

The application can be used also as a learning platform used to fill online quizzes and tests for online teaching 

## Description of the software

- Efficient data collection

	The app streamlines data collection process by automating various tasks, such as distributing questionnaires, collecting responses, and organizing the data. It eliminates the need for manual data entry, reducing the chances of errors and saving time.

- Reach a larger audience

	It allows researchers to reach a wider audience with automated processes. This increased reach can lead to a more diverse and representative sample, enhancing the validity and generalizability of the research findings.

- Real-time data analysis

	Researchers can access real-time data analysis and visualization tools. This enables them to monitor response patterns and trends as they happen, helping them to make quick decisions and adjust the survey if necessary.

- Data security and privacy

	It ensures data security and privacy for both researchers and respondents. Encryption and secure storage protect sensitive information, building trust with survey participants.

- Advanced question types and branching logic

	The app offers a variety of question types (e.g., multiple-choice, Likert scale, open-ended) and branching logic capabilities. This allows researchers to design complex and customized surveys that gather specific information relevant to their research objectives.

- Data export and integration

	Researchers can easily export survey data into various formats (e.g., CSV, Excel) for further analysis or integrate it with other research tools and software, facilitating seamless data management.

- Collaboration and teamwork

	The app supports collaboration, enabling multiple researchers to work together on survey design, data analysis, and reporting. This fosters teamwork and enhances overall research productivity.

## Real-world deployment

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