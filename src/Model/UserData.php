<?php
declare(strict_types=1);

namespace AthenaSodium\Model;

use Application\Model\ApplicationModel;

class UserData extends ApplicationModel
{
    public function setS3BucketId(int $bucketid): void
    {
        $this -> getDataSet() -> set('s3_bucket_id', $bucketid);
    }

    public function setS3DataId(int $dataid): void
    {
        $this -> getDataSet() -> set('s3_data_id', $dataid);
    }

    public function setFolderId(int $folderid): void
    {
        $this -> getDataSet() -> set('folder_id', $folderid);
    }

    public function getS3BucketId(): int
    {
        return (int)$this -> getDataSet() -> get('s3_bucket_id');
    }

    public function getS3DataId(): int
    {
        return (int)$this -> getDataSet() -> get('s3_data_id');
    }

    public function getFolderId(): int
    {
        return (int)$this -> getDataSet() -> get('folder_id');
    }
}