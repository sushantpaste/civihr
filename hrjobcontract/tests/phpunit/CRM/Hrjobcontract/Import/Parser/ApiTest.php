<?php

/**
 *
 */

require_once 'CiviTest/CiviUnitTestCase.php';

/**
 *  Test CRM/Member/BAO Membership Log add , delete functions
 *
 *  @package   CiviCRM
 */
class CRM_Hrjobcontract_Import_Parser_ApiTest extends CiviUnitTestCase {

  public $_contractTypeID;

  protected $_tablesToTruncate = array(
    'civicrm_hrjobcontract',
    'civicrm_hrjobcontract_details',
    'civicrm_hrjobcontract_health',
    'civicrm_hrjobcontract_hour',
    'civicrm_hrjobcontract_leave',
    'civicrm_hrjobcontract_pay',
    'civicrm_hrjobcontract_pension',
    'civicrm_hrjobcontract_revision',
    'civicrm_hrjobcontract_role');

  function setUp() {
    parent::setUp();
    $upgrader = CRM_Hrjobcontract_Upgrader::instance();
    $upgrader->install();
    $this->_contractTypeID = $this->creatTestContractType();
    $session = CRM_Core_Session::singleton();
    $session->set('dateTypes', 1);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   *
   */
  function tearDown() {
    parent::tearDown();
    $this->cleanDB();
  }


  function testMandatoryFieldsImportWithContactID() {
    $contact2Params = array(
      'first_name' => 'John_1',
      'last_name' => 'Snow_1',
      'email' => 'a1@b1.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    $params = array(
      'HRJobContract-contact_id' => $contactID,
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
    );

    $this->runImport($params);
    $this->validateResult($contactID);
  }

  function testMandatoryFieldsImportWithContactExternalIdentifier() {
    $contact2Params = array(
      'first_name' => 'John_2',
      'last_name' => 'Snow_2',
      'email' => 'a2@b2.com',
      'external_identifier' => 'abcdefg12345',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    $params = array(
      'HRJobContract-external_identifier' => $contact2Params['external_identifier'],
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
    );

    $this->runImport($params);
    $this->validateResult($contactID);
  }

  function testMandatoryFieldsImportWithContactEmail() {
    $contact2Params = array(
      'first_name' => 'John_3',
      'last_name' => 'Snow_3',
      'email' => 'a3@b3.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    $params = array(
      'HRJobContract-email' => $contact2Params['email'],
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
    );

    $this->runImport($params);
    $this->validateResult($contactID);
  }

  function testJobDetailsImport() {
    $contact2Params = array(
      'first_name' => 'John_4',
      'last_name' => 'Snow_4',
      'email' => 'a4@b4.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    $params = array(
      'HRJobContract-contact_id' => $contactID,
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
      'HRJobDetails-location' => 'headquarters',
      'HRJobDetails-period_end_date' => '2016-01-20',
      'HRJobDetails-end_reason' => 'Planned',
      'HRJobDetails-notice_amount_employee' => '3',
      'HRJobDetails-notice_unit_employee' => 'Week',
      'HRJobDetails-notice_amount' => '4',
      'HRJobDetails-notice_unit' => 'day',
      'HRJobDetails-funding_notes' => 'sample',
    );

    $this->runImport($params);
    $this->validateResult($contactID);
  }

  function testHourImport() {
    $contact2Params = array(
      'first_name' => 'John_5',
      'last_name' => 'Snow_5',
      'email' => 'a5@b5.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    $params = array(
      'HRJobContract-contact_id' => $contactID,
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
      'HRJobHour-location_standard_hours' => 'Small office - 36.00 hours per Week',
      'HRJobHour-hours_type' => 'part time',
      'HRJobHour-hours_amount' => '25',
      'HRJobHour-hours_unit' => 'week',
    );

    $this->runImport($params);
    $this->validateResult($contactID, 'HRJobHour');
  }

  function testPayImport() {
    $contact2Params = array(
      'first_name' => 'John_6',
      'last_name' => 'Snow_6',
      'email' => 'a6@b6.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    // TODO : add deduction and benefit amounts to params
    $params = array(
      'HRJobContract-contact_id' => $contactID,
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
      'HRJobPay-is_paid' => 'Yes',
      'HRJobPay-pay_scale' => 'US - Senior - USD 38000.00 per Year',
      'HRJobPay-pay_currency' => 'USD',
      'HRJobPay-pay_amount' => '35000',
      'HRJobPay-pay_unit' => 'year',
      'HRJobPay-pay_cycle' => 'Monthly',
    );

    $this->runImport($params);
    $this->validateResult($contactID, 'HRJobPay');
  }



  function testInsuranceImport() {
    $contact2Params = array(
      'first_name' => 'John_8',
      'last_name' => 'Snow_8',
      'email' => 'a8@b8.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    // TODO : create and add health and life insurance providers to params
    $params = array(
      'HRJobContract-contact_id' => $contactID,
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
      'HRJobHealth-dependents' => 'HI Description',
      'HRJobHealth-description' => 'HI dependents',
      'HRJobHealth-plan_type' => 'Family',
      'HRJobHealth-dependents_life_insurance' => 'LI dependents',
      'HRJobHealth-description_life_insurance' => 'LI description',
      'HRJobHealth-plan_type_life_insurance' => 'Individual',
    );

    $this->runImport($params);
    $this->validateResult($contactID, 'HRJobHealth');
  }

  function testPensionImport() {
    $contact2Params = array(
      'first_name' => 'John_9',
      'last_name' => 'Snow_9',
      'email' => 'a9@b9.com',
      'contact_type' => 'Individual',
    );
    $contactID = $this->createTestContact($contact2Params);
    $params = array(
      'HRJobContract-contact_id' => $contactID,
      'HRJobDetails-title' => 'Test Contract Title',
      'HRJobDetails-position' => 'Test Contract Position',
      'HRJobDetails-contract_type' => $this->_contractTypeID,
      'HRJobDetails-period_start_date' => '2016-01-01',
      'HRJobPension-is_enrolled' => 'Yes',
      'HRJobPension-er_cohntrib_pct' => '10',
      'HRJobPension-ee_contrib_pct' => '12',
      'HRJobPension-ee_contrib_abs' => '4000',
      'HRJobPension-ee_evidence_note' => 'sample',
      'HRJobPension-pension_type' => 'employer pension',
    );

    $this->runImport($params);
    $this->validateResult($contactID, 'HRJobPension');
  }

  private function runImport($params)  {
    $fields = array_keys($params);
    $values = array_values($params);
    $importObject = new CRM_Hrjobcontract_Import_Parser_Api($fields);
    $importObject->_importMode = CRM_Hrjobcontract_Import_Parser::IMPORT_CONTRACTS;
    $importObject->init();
    $this->assertEquals(CRM_Import_Parser::VALID, $importObject->import(NULL, $values), 'import error');
  }

  private function validateResult($contactID, $entity = NULL)  {
    $result = $this->callAPISuccessGetSingle('HRJobContract', array('contact_id'=>$contactID));
    $contractID = $result['id'];
    $result = $this->callAPISuccessGetSingle('HRJobContractRevision', array('jobcontract_id'=>$contractID));
    $revisionID = $result['details_revision_id'];
    $this->callAPISuccessGetSingle('HRJobDetails', array('jobcontract_revision_id'=>$revisionID));
    if ($entity !== NULl)  {
      if ($entity == 'HRJobLeave')  {
        $this->callAPISuccess($entity, 'getcount', array('jobcontract_revision_id'=>$revisionID), 5);
      }
      else  {
        $this->callAPISuccessGetSingle($entity, array('jobcontract_revision_id'=>$revisionID));
      }
    }
  }

  private function createTestContact($params)  {
    $contactID = $this->individualCreate($params);
    return $contactID;
  }

  private function creatTestContractType()  {
    $contractTypeGroup = $this->callAPISuccess('OptionGroup', 'get', array(
      'sequential' => 1,
      'name' => "hrjc_contract_type",
    ), 'unable to find contract type option group');

    $contractType = $this->callAPISuccess('option_value', 'create', array(
      'option_group_id' => $contractTypeGroup['id'],
      'name' => 'Test Contract Type',
      'label' => 'Test Contract Type',
      'sequential' => 1
    ), 'unable to create contract type');
    return  $contractType['id'];
  }
}

