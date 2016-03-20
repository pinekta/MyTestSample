<?php

namespace Atw\DdnsUserAdminBundle\Tests\Dto;

use Symfony\Component\HttpFoundation\File\File;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Entity\Support\EntitySupport;
use Atw\DdnsUserAdminBundle\Tests\Support\ContainerAwareKernelTestCase;

/**
 * Class DnsUserImportDtoTest
 */
class DnsUserImportDtoTest extends ContainerAwareKernelTestCase
{
    /** @var ValidatorInterface  $validator */
    private $validator;

    /**
     * テスト前処理
     */
    public function setUp()
    {
        parent::setup();
        $this->validator = $this->container->get('validator');
    }

    /**
     * プロパティの入力チェックを行う
     * @test
     * @dataProvider dataProvider
     */
    public function validateProperty($expected, $data, $msg)
    {
        $dto = new DnsUserExportDto();
        EntitySupport::autoSet($dto, $data);

        $errors = $this->validator->validate($dto);
        $this->assertEquals($expected, count($errors), $msg);
    }

    /**
     * @return array チェックするデータ
     */
    public function dataProvider()
    {
        return $datas = [
            [
                0,
                [
                    "importType" => DnsUserImportDto::FILE_TYPE_CSV,
                    "isIgnoreHeaderLine" => DnsUserImportDto::IS_IGNORE_HEADER_YES,
                    "isUpdated" => DnsUserImportDto::IS_UPDATED_NO,
                    "importFile" => new File(),
                ],
                "正常系テスト(エラー数0)",
            ],
            [
                4,
                [
                    "importType" => null,
                    "isIgnoreHeaderLine" => null,
                    "isUpdated" => null,
                    "importFile" => null,
                ],
                "必須テストエラー(エラー数4)",
            ],
            [
                7,
                [
                    "importType" => "unknown",
                    "isIgnoreHeaderLine" => "unknown",
                    "isUpdated" => "unknown",
                    "importFile" => "unknown",
                ],
                "型チェックエラー(エラー数7)",
            ],
            [
                3,
                [
                    "importType" => 99999999,
                    "isIgnoreHeaderLine" => 99999999,
                    "isUpdated" => 99999999,
                    "importFile" => new File(),
                ],
                "存在チェックエラー(エラー数3)",
            ],
            [
                1,
                [
                    "importType" => DnsUserImportDto::FILE_TYPE_CSV,
                    "isIgnoreHeaderLine" => DnsUserImportDto::IS_IGNORE_HEADER_YES,
                    "isUpdated" => DnsUserImportDto::IS_UPDATED_NO,
                    "importFile" => new ImageFile(),
                ],
                "アップロードファイルがtext/plainまたはtext/csvでない場合",
            ],
        ];
    }
}
