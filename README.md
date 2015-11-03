Fleet Command
==========

Fleet Command is a mission log application for spaceship commanders. This project is to be used as a test of skills for debugging and developing within the Symfony PHP and Ember JS frameworks.

## Requirements

- PHP >= 5.4.11
- Apache or compatible web server (PHP > 5.4.0 can use `php app/console server:run`)
- [Composer](https://getcomposer.org/download/)
- [NodeJS](https://nodejs.org/en/download/) and NPM
- Bower & Ember CLI `npm install -g bower ember-cli`
- You will need a Github account to submit your changes.

**If you are unable to meet these requirements, please contact bkrigbaum@southcomm.com to schedule a time to work through this with a pre-configured environment.**

## Installation

- Fork this repository to your Github account. After forking, clone your fork to your machine.
- Perform composer install `composer install --no-interaction`
- Start your web server (PHP > 5.4 can use `php app/console server:run`)
- Build ember application:
```
cd src/AppBundle/Resources/todo
npm install
bower install
ember build
```
- Rebuild JS/CSS using assetic:
```
cd ../../../../
php app/console cache:clear
php app/console assetic:dump
```
  
## Scenarios
There are several issues with this repository. Each of them are listed below. For each issue, determine the root cause and commit a fix to your fork of this repository.

1. **Sign In link is broken**: When I try to click sign in to update my mission logs, an error is displayed.
2. **Forgot my credentials :(**: I seem to have forgotten my username and password for Fleet Commander. Can you tell me what they are? I remember we use Symfony's [security configuration](http://symfony.com/doc/current/book/security.html), but I can't remember how to login.
3. **Javascript error when viewing mission logs**: When attempting to load my mission logs after logging in, nothing shows up on the page. In the browser console, I see an error.
4. **Javascript error when creating a log**: When I try to create a new mission log, a Javascript error shows up and my log isn't saved.

## Submission
Once you have completed all scenarios, submit a new PR to this repository with your changes. You can easily submit a PR by opening your fork on Github and clicking the green button on the left. Fill out the PR with a title and a summary of the changes you made.
