# PHP App Challenge Solution 

## Overview

This application is meant to solve a particular use case for an entertainment event provider 
who is integrated along with another external data provider to get all the plans information available to 
final users. 

The data obtained from the external provider is parsed and filtered according to 
specific conditions to be eligible, then stored locally into this application database 
to be consumed afterwards on demand depending on the user requests. 

## Tech stack 

- php 8.3 
- Laravel Framework 12 
- SQlite database engine 
- Docker 

## Key components 

### ExternalPlansClient

- Used to connect to the specific provider data endpoint and **fetch** all available data

### ExternalPlansContentParser

- Meant to **parse** the original data from the provider endpoint (XML) and shape it into 
internal object representation (DTOs)

### ExternalPlansDataService 

- Main process **orchestrator** connecting all parts above and using internal repositories
to persist the data into the main database 

### Task scheduler 

- **Background** process in charge of running the main external provider data process
on **regular** basis by calling a special command tailored for it, as ``artisan app:plans:udpate``
- At the moment programmed to run every minute for demo purposes

### ApiController 

- Main front controller for final  users, serving an internal **endpoint** where the available plans data is
returned based on the request
- Handles and **validate** necessary parameters and responses

### PlansDateSearchAction

- Specific **Action** class to process the search request served by the controller, 
taking the available data from the database and using a **cache** layer as well to improve performance

### Eloquent models 

- Concrete classes used to provide **data transfer** with the main database, using repositories on top of them
to decouple the domain logic from the framework native db objects 

### Eloquent migrations 

- Used to create all the needed **schema** for the main database on application first booting

## Application setup and start 

- Clone the current repository 
- Find the Makefile at the root level 
- Execute `make run` in the terminal 
- Let the docker container finish creation 
- Visit the local url in ``http://localhost:10000``
- Check the welcome laravel screen to ensure is running
- Allow the background process to run (every minute) and fetch the available data
- Or you can also run it manually by doing ``make plans`` 
- Try now the plans endpoint on ``http://localhost:10000/api/search`` 
- Remember to provider a valid pair of dates as: ``start_date=yyyy-mm-dd&end_date=yyyy-mm-dd`` into the query

### Bonus tip 

- You can also use _pagination_ parameters as other common API's, by adding the args into the query: 
  - ``pagination`` -> number of items returned 
  - ``page`` -> current page number 

## Useful Make Commands

- Make run -> Builds and starts app 
- Make stop -> Stops app container 
- Make plans -> Runs background process to fetch external data 
- Make logs -> Displays current app logs content for debugging
- Make test -> Shows the test results running the whole battery 

## Considerations and future recommendations 

- From the top the first things to address would be maybe in terms of infrastructure, 
to mount the application in a better way using proper containers such as dedicated 
`nginx` server along with a `php-fpm` processor 
- Change Database engine and move to a more sustainable option for a relational db (MySQL, PostgresSQL)  
- Improve and change the background process runner by implementing a proper Cron mechanism 
configured in a different time span depending on usage
- It may be also accepted to move into a Queue system approach and implement a Message Broker tool
such as RabbitMQ or simply Redis server as the main job's fast storage.
- Change the Cache layer as well and implement a more robust solution such as Redis or Memcache 
- Improve logs with structured logging and Integrate monitoring tools
