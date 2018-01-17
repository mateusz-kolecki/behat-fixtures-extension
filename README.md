[![Build Status](https://travis-ci.org/mateusz-kolecki/behat-fixtures-extension.svg?branch=master)](https://travis-ci.org/mateusz-kolecki/behat-fixtures-extension)
[![GitHub issues](https://img.shields.io/github/issues/mateusz-kolecki/behat-fixtures-extension.svg)](https://github.com/mateusz-kolecki/behat-fixtures-extension/issues)
[![GitHub license](https://img.shields.io/github/license/mateusz-kolecki/behat-fixtures-extension.svg)](https://github.com/mateusz-kolecki/behat-fixtures-extension/blob/master/LICENCE)
[![Support PHP >= 5.3](https://img.shields.io/badge/PHP-%3E%3D5.3-brightgreen.svg)](http://php.net/)

---

# Behat fixtures extension

Behat extension for loading fixtures to your context.

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

Add extension configuration to your `behat.yml` file:

```yaml
default:
  extensions:
    MKolecki\Behat\FixturesExtension:
      fixtures:
        users: %paths.base%/fixtures/development/users.yaml
```

Use fixtures in your context code:

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
     * @Then I login with :user
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
Feature: I need verify login process

  Scenario: I can login to my application and see home page
    When I open login website
    And I login with "with-many-friends"
    Then I see my home page
```
