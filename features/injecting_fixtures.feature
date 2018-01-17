Feature: Fixtures injector
  In order to provide additional configuration do scenarios
  As a developer
  I need to be able to load and inject fixtures to feature context.

  Background:
    Given a file named "behat.yml" with:
      """
      default:
        extensions:
          MKolecki\Behat\FixturesExtension:
            delimiter: /
            fixtures:
              foo: fixtures/foo.yaml
              bar: fixtures/bar.json
      """
    And a file named "features/bootstrap/FeatureContext.php" with:
      """
      <?php

      use Behat\Behat\Context\Context;
      use MKolecki\Behat\FixturesExtension\Fixtures;
      use PHPUnit_Framework_Assert as Assert;

      class FeatureContext implements Context
      {
          private $fixtures;

          public function __construct(Fixtures $fixtures) {
              $this->fixtures = $fixtures;
          }

          /** @When I run behat with fixtures extension*/
          public function iRunBehatWithFixturesExtension() {}

          /** @Then the fixtures should be loaded*/
          public function theExtensionLoaded() {
              Assert::assertEquals(1234, $this->fixtures->get('foo/bar'));
              Assert::assertEquals('yaml foo', $this->fixtures->get('foo/zab'));

              Assert::assertEquals(4567, $this->fixtures->get('bar/foo'));
              Assert::assertEquals('json foo', $this->fixtures->get('bar/baz'));
          }
      }
      """
    And a file named "fixtures/foo.yaml" with:
      """
      bar: 1234
      zab: yaml foo
      """
    And a file named "fixtures/bar.json" with:
      """
      {
        "foo": 4567,
        "baz": "json foo"
      }
      """
    And a file named "features/fixtures.feature" with:
      """
      Feature: Fixtures

        Scenario: Injecting fixtures
          When I run behat with fixtures extension
          Then the fixtures should be loaded
      """

  Scenario: Fixtures should be loaded and injected
    When I run "behat -f progress"
    Then it should pass with:
      """
      ..

      1 scenario (1 passed)
      2 steps (2 passed)
      """
