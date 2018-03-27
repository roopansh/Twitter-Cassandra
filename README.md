# Twitter-Cassandra
Storing twitter dataset using Cassandra NoSQL Database.


### Objective

Given a Twitter dataset, created a Cassandra database and a Python program to insert the data into the database. The choice of data model(s) is at our discretion and is done keeping in mind the Cassandra architecture and its goals. Once the database is loaded with the given data, PHP is used to perform the following operations. A web interface is built that will take input for each of the following operations and will display the final result.

1. Given an author name, display all tweets posted by that author sorted by decreasing order of date and time. The details of the tweet must include the tweet Id, tweet text, tweet author Id, tweet location and tweet language.

2. Given a keyword, retrieve the tweets containing the keyword and sort them by their popularity in decreasing order. Popularity of a tweet is based on it's like-count. Higher the like-count, more the popularity.

3. Given a hashtag, retrieve all tweets containing the hashtag and sort them in decreasing order of date and time.

4. Given an author name, retrieve all tweets that mentions the author. Sort them in decreasing order of date and time.

5. Retrieve all tweets of a particular date sorted in decreasing order of their popularity where popularity is based on like count of the tweet.

6. Retrieve all tweets from a given location

7. Given a date, retrieve top 20 popular hashtags over the last 7 days. The popularity of a hashtag is determined by its frequency of occurrence over the said period.

8. Given a date, delete all tweets posted on that day.

-----------------------

## Project Author
#### Roopansh Bansal

B.Tech undergraduate (Computer Science & Engineering)

Indian Institute of Technology Guwahati

India

[E-Mail](mailto:roopansh.bansal@gmail.com)  |  [LinkedIn](https://www.linkedin.com/in/roopansh-bansal)
