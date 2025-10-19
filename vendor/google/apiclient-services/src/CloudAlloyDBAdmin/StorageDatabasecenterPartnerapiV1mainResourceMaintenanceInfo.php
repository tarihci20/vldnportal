<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\CloudAlloyDBAdmin;

class StorageDatabasecenterPartnerapiV1mainResourceMaintenanceInfo extends \Google\Collection
{
  protected $collection_key = 'denyMaintenanceSchedules';
  protected $denyMaintenanceSchedulesType = StorageDatabasecenterPartnerapiV1mainResourceMaintenanceDenySchedule::class;
  protected $denyMaintenanceSchedulesDataType = 'array';
  protected $maintenanceScheduleType = StorageDatabasecenterPartnerapiV1mainResourceMaintenanceSchedule::class;
  protected $maintenanceScheduleDataType = '';
  /**
   * @var string
   */
  public $maintenanceVersion;

  /**
   * @param StorageDatabasecenterPartnerapiV1mainResourceMaintenanceDenySchedule[]
   */
  public function setDenyMaintenanceSchedules($denyMaintenanceSchedules)
  {
    $this->denyMaintenanceSchedules = $denyMaintenanceSchedules;
  }
  /**
   * @return StorageDatabasecenterPartnerapiV1mainResourceMaintenanceDenySchedule[]
   */
  public function getDenyMaintenanceSchedules()
  {
    return $this->denyMaintenanceSchedules;
  }
  /**
   * @param StorageDatabasecenterPartnerapiV1mainResourceMaintenanceSchedule
   */
  public function setMaintenanceSchedule(StorageDatabasecenterPartnerapiV1mainResourceMaintenanceSchedule $maintenanceSchedule)
  {
    $this->maintenanceSchedule = $maintenanceSchedule;
  }
  /**
   * @return StorageDatabasecenterPartnerapiV1mainResourceMaintenanceSchedule
   */
  public function getMaintenanceSchedule()
  {
    return $this->maintenanceSchedule;
  }
  /**
   * @param string
   */
  public function setMaintenanceVersion($maintenanceVersion)
  {
    $this->maintenanceVersion = $maintenanceVersion;
  }
  /**
   * @return string
   */
  public function getMaintenanceVersion()
  {
    return $this->maintenanceVersion;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StorageDatabasecenterPartnerapiV1mainResourceMaintenanceInfo::class, 'Google_Service_CloudAlloyDBAdmin_StorageDatabasecenterPartnerapiV1mainResourceMaintenanceInfo');
