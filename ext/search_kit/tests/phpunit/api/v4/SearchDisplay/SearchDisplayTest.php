<?php
namespace api\v4\SearchDisplay;

use Civi\Api4\SearchDisplay;
use Civi\Test\HeadlessInterface;
use Civi\Test\TransactionalInterface;

/**
 * @group headless
 */
class SearchDisplayTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, TransactionalInterface {

  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function testGetDefault() {
    $params = [
      'api_entity' => 'Contact',
      'api_params' => [
        'version' => 4,
        'select' => ['first_name', 'last_name', 'contact_sub_type:label', 'gender_id'],
        'where' => [],
      ],
    ];
    $display = SearchDisplay::getDefault(FALSE)
      ->setSavedSearch($params)
      ->addSelect('*', 'saved_search_id.api_entity', 'type:name')
      ->execute()->single();

    $this->assertCount(5, $display['settings']['columns']);
    $this->assertEquals('Contacts', $display['label']);
    $this->assertEquals('crm-search-display-table', $display['type:name']);
    $this->assertEquals('Contact', $display['saved_search_id.api_entity']);
  }

}