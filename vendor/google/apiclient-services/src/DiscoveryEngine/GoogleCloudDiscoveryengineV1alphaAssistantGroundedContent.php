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

namespace Google\Service\DiscoveryEngine;

class GoogleCloudDiscoveryengineV1alphaAssistantGroundedContent extends \Google\Model
{
  protected $contentType = GoogleCloudDiscoveryengineV1alphaAssistantContent::class;
  protected $contentDataType = '';
  protected $textGroundingMetadataType = GoogleCloudDiscoveryengineV1alphaAssistantGroundedContentTextGroundingMetadata::class;
  protected $textGroundingMetadataDataType = '';

  /**
   * @param GoogleCloudDiscoveryengineV1alphaAssistantContent
   */
  public function setContent(GoogleCloudDiscoveryengineV1alphaAssistantContent $content)
  {
    $this->content = $content;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1alphaAssistantContent
   */
  public function getContent()
  {
    return $this->content;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1alphaAssistantGroundedContentTextGroundingMetadata
   */
  public function setTextGroundingMetadata(GoogleCloudDiscoveryengineV1alphaAssistantGroundedContentTextGroundingMetadata $textGroundingMetadata)
  {
    $this->textGroundingMetadata = $textGroundingMetadata;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1alphaAssistantGroundedContentTextGroundingMetadata
   */
  public function getTextGroundingMetadata()
  {
    return $this->textGroundingMetadata;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDiscoveryengineV1alphaAssistantGroundedContent::class, 'Google_Service_DiscoveryEngine_GoogleCloudDiscoveryengineV1alphaAssistantGroundedContent');
