[![Build Status](https://travis-ci.org/mateusz-kolecki/behat-fixtures-extension.svg?branch=master)](https://travis-ci.org/mateusz-kolecki/behat-fixtures-extension)
[![GitHub issues](https://img.shields.io/github/issues/mateusz-kolecki/behat-fixtures-extension.svg)](https://github.com/mateusz-kolecki/behat-fixtures-extension/issues)
[![GitHub license](https://img.shields.io/github/license/mateusz-kolecki/behat-fixtures-extension.svg)](https://github.com/mateusz-kolecki/behat-fixtures-extension/blob/master/LICENCE)
[![Support PHP >= 5.3](https://img.shields.io/badge/PHP-%3E%3D5.3-brightgreen.svg)](http://php.net/)

---

# Behat fixtures extension

Behat extension for loading and injecting fixtures to your context.
It make life easier when you need test data in your features and you want to separate them from your scenarios.

Simple as:
* install extension (`composer require`)
* create your fixture files (`vim fixtures/users.yml`)
* configure it (`vim behat.yml`)
* add `Fixtures` object to your feature context constructor (`__construct(Fixtures $fixtures)`)

Enjoy!

## Installation

Use `composer` command to install `mkolecki/behat-fixtures-extension`:

```
composer require --dev mkolecki/behat-fixtures-extension
```

## Usage

Create `fixtures/development/users.yaml` file in your project:

```yaml
with-blocked-account:
  login: john
  passwotd: supersecret123

with-unpaid-invoces:
  login: max
  password: megasecret5
  
with-many-friends:
  login: adam
  password: test1234
```

Add extension configuration to your `behat.yml` file. `fixtures` is an array of files to load.

```yaml
default:
  extensions:
    MKolecki\Behat\FixturesExtension:
      fixtures:
        users: %paths.base%/fixtures/development/users.yaml
        companies: %paths.base%/fixtures/development/companies.yaml
        admins: %paths.base%/fixtures/development/admins.yaml
```

Use fixtures in your feature context code:

```php
<?php
use MKolecki\Behat\FixturesExtension\Fixtures;
use Behat\Behat\Context\Context;

class LoginContext implements Context
{
    /** @var Fixtures */
    private $fixtures;

    public function __construct(Fixtures $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    /**
     * @Then I login with user :user
     */
    public function iLoginWithUser($user)
    {
        $login = $this->fixtures->get("users/$user/login");
        $password = $this->fixtures->get("users/$user/password");

        // eg. use selenium to fill login form and submit
    }

    // ...
}
```

In your `*.feature` file you can now refer to user definition:

```feature
Feature: User can login

  Scenario: I can login to my application and see home page
    When I open login page
    And I login with user "with-many-friends"
    Then I see my home page
```
