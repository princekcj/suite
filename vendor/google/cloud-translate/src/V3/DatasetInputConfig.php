<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/translate/v3/automl_translation.proto

namespace Google\Cloud\Translate\V3;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Input configuration for datasets.
 *
 * Generated from protobuf message <code>google.cloud.translation.v3.DatasetInputConfig</code>
 */
class DatasetInputConfig extends \Google\Protobuf\Internal\Message
{
    /**
     * Files containing the sentence pairs to be imported to the dataset.
     *
     * Generated from protobuf field <code>repeated .google.cloud.translation.v3.DatasetInputConfig.InputFile input_files = 1;</code>
     */
    private $input_files;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array<\Google\Cloud\Translate\V3\DatasetInputConfig\InputFile>|\Google\Protobuf\Internal\RepeatedField $input_files
     *           Files containing the sentence pairs to be imported to the dataset.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Translate\V3\AutomlTranslation::initOnce();
        parent::__construct($data);
    }

    /**
     * Files containing the sentence pairs to be imported to the dataset.
     *
     * Generated from protobuf field <code>repeated .google.cloud.translation.v3.DatasetInputConfig.InputFile input_files = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getInputFiles()
    {
        return $this->input_files;
    }

    /**
     * Files containing the sentence pairs to be imported to the dataset.
     *
     * Generated from protobuf field <code>repeated .google.cloud.translation.v3.DatasetInputConfig.InputFile input_files = 1;</code>
     * @param array<\Google\Cloud\Translate\V3\DatasetInputConfig\InputFile>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setInputFiles($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Translate\V3\DatasetInputConfig\InputFile::class);
        $this->input_files = $arr;

        return $this;
    }

}
