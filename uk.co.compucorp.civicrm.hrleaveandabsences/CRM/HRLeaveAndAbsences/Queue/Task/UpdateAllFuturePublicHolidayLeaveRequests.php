<?php

use CRM_HRLeaveAndAbsences_Service_PublicHolidayLeaveRequest as PublicHolidayLeaveRequestService;
use CRM_HRLeaveAndAbsences_Service_PublicHolidayLeaveRequestCreation as PublicHolidayLeaveRequestCreation;
use CRM_HRLeaveAndAbsences_Service_PublicHolidayLeaveRequestDeletion as PublicHolidayLeaveRequestDeletion;


/**
 * This is the Queue Task which will be executed by the whenever the
 * PublicHolidayLeaveRequestUpdates queue is processed.
 *
 * Basically, it uses the PublicHolidayLeaveRequest service to update all leave
 * requests for public holidays in the future
 */
class CRM_HRLeaveAndAbsences_Queue_Task_UpdateAllFuturePublicHolidayLeaveRequests {

  public function run(CRM_Queue_TaskContext $ctx) {
    $creationLogic = new PublicHolidayLeaveRequestCreation();
    $deletionLogic = new PublicHolidayLeaveRequestDeletion();

    $service = new PublicHolidayLeaveRequestService($creationLogic, $deletionLogic);
    $service->updateAllLeaveRequestsInTheFuture();
  }

}
