<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/translate/v3/adaptive_mt.proto

namespace Google\Cloud\Translate\V3;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The request for importing an AdaptiveMt file along with its sentences.
 *
 * Generated from protobuf message <code>google.cloud.translation.v3.ImportAdaptiveMtFileRequest</code>
 */
class ImportAdaptiveMtFileRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The resource name of the file, in form of
     * `projects/{project-number-or-id}/locations/{location_id}/adaptiveMtDatasets/{dataset}`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     */
    private $parent = '';
    protected $source;

    /**
     * @param string $parent Required. The resource name of the file, in form of
     *                       `projects/{project-number-or-id}/locations/{location_id}/adaptiveMtDatasets/{dataset}`
     *                       Please see {@see TranslationServiceClient::adaptiveMtDatasetName()} for help formatting this field.
     *
     * @return \Google\Cloud\Translate\V3\ImportAdaptiveMtFileRequest
     *
     * @experimental
     */
    public static function build(string $parent): self
    {
        return (new self())
            ->setParent($parent);
    }

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required. The resource name of the file, in form of
     *           `projects/{project-number-or-id}/locations/{location_id}/adaptiveMtDatasets/{dataset}`
     *     @type \Google\Cloud\Translate\V3\FileInputSource $file_input_source
     *           Inline file source.
     *     @type \Google\Cloud\Translate\V3\GcsInputSource $gcs_input_source
     *           Google Cloud Storage file source.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Translate\V3\AdaptiveMt::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The resource name of the file, in form of
     * `projects/{project-number-or-id}/locations/{location_id}/adaptiveMtDatasets/{dataset}`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required. The resource name of the file, in form of
     * `projects/{project-number-or-id}/locations/{location_id}/adaptiveMtDatasets/{dataset}`
     *
     * Generated from protobuf field <code>string parent = 1 [(.google.api.field_behavior) = REQUIRED, (.google.api.resource_reference) = {</code>
     * @param string $var
     * @return $this
     */
    public function setParent($var)
    {
        GPBUtil::checkString($var, True);
        $this->parent = $var;

        return $this;
    }

    /**
     * Inline file source.
     *
     * Generated from protobuf field <code>.google.cloud.translation.v3.FileInputSource file_input_source = 2;</code>
     * @return \Google\Cloud\Translate\V3\FileInputSource|null
     */
    public function getFileInputSource()
    {
        return $this->readOneof(2);
    }

    public function hasFileInputSource()
    {
        return $this->hasOneof(2);
    }

    /**
     * Inline file source.
     *
     * Generated from protobuf field <code>.google.cloud.translation.v3.FileInputSource file_input_source = 2;</code>
     * @param \Google\Cloud\Translate\V3\FileInputSource $var
     * @return $this
     */
    public function setFileInputSource($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Translate\V3\FileInputSource::class);
        $this->writeOneof(2, $var);

        return $this;
    }

    /**
     * Google Cloud Storage file source.
     *
     * Generated from protobuf field <code>.google.cloud.translation.v3.GcsInputSource gcs_input_source = 3;</code>
     * @return \Google\Cloud\Translate\V3\GcsInputSource|null
     */
    public function getGcsInputSource()
    {
        return $this->readOneof(3);
    }

    public function hasGcsInputSource()
    {
        return $this->hasOneof(3);
    }

    /**
     * Google Cloud Storage file source.
     *
     * Generated from protobuf field <code>.google.cloud.translation.v3.GcsInputSource gcs_input_source = 3;</code>
     * @param \Google\Cloud\Translate\V3\GcsInputSource $var
     * @return $this
     */
    public function setGcsInputSource($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Translate\V3\GcsInputSource::class);
        $this->writeOneof(3, $var);

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->whichOneof("source");
    }

}
