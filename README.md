# Instructions for Reviewer

Run the following commands to build the docker image and install composer dependencies

```
docker build . -t howsy-code-challenge
```
```
docker run -it -v $(pwd):/app -w /app howsy-code-challenge composer install
```

Running the test suite

```
docker run -it -v $(pwd):/app -w /app howsy-code-challenge vendor/bin/phpunit
```

Running the simple CLI example

```
docker run -it -v $(pwd):/app -w /app howsy-code-challenge php example.php
```

# Howsy Software Engineer Code Challenge

This test allows you to demonstrate the skills that will be required as a Software Developer at Howsy. We primarily use PHP at Howsy and would prefer submissions in this format however we will accept submissions in other OOP languages (Java, Python, Ruby) *but please note, day-to-day you will probably be writing PHP*

Try to write elegant OOP code. It should be SOLID, DRY and have good test coverage. Just like it would for a real project! We are trying to see how you write code so please don’t use any full-service frameworks. Packages for things like DI containers are fine of course.

We also want to see the infrastructure around the system - how can we run your solution and validate it works. Please include a readme detailing this. (hint: DOCKER)

When you have completed the challenge please share your code in the form of a GIT repository with daniel.benzie@howsy.com.

# Challenge 

Howsy are building a new checkout. The checkout system allows users to pay upfront for products added to their property management agreement. The system should also allow users to take advantage of special offers. An initial offer will be “users who have agreed to a 12-month contract are entitled to a 10% discount off the basket total”

The products are below:

| Product Code | Name             | Price |
|--------------|------------------|-------|
| P001         | Photography      | 200   |
| P002         | Floorplan        | 100   |
| P003         | Gas Certificate  | 83.50 |
| P004         | EICR Certificate | 51.00 |

Your job is to implement the basket which should have the following interface:

1. Basket can be initialised with offer(s) user is eligible for
2. It has an add method to add a product
3. Each individual product can only be added to the basket one time
4. It has a total method that returns the total cost of the basket - remember to take into account any valid offers

## There is no need to build any front end components. The interface and behaviours should be validated by the code and its associated automated tests.
