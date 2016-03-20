<?php

namespace Atw\DdnsUserAdminBundle\Tests\Dto;

use Atw\DdnsUserAdminBundle\Dto\DnsUserExportDto;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Entity\Support\EntitySupport;
use Atw\DdnsUserAdminBundle\Tests\Support\ContainerAwareKernelTestCase;

/**
 * Class DnsUserExportDtoTest
 */
class DnsUserExportDtoTest extends ContainerAwareKernelTestCase
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
     * @dataProvider exportTypeValidateDataProvider
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
    public function exportTypeValidateDataProvider()
    {
        return $invalidDatas = [
            [
                0,
                ["exportType" => DnsUserImportDto::FILE_TYPE_CSV],
                "正常系テスト(エラー数0)",
            ],
            [
                1,
                ["exportType" => null],
                "必須エラー",
            ],
            [
                2,
                ["exportType" => "a"],
                "文字型エラー(存在チェックのエラーも含む)",
            ],
            [
                1,
                ["exportType" => 99999999],
                "存在チェックエラー",
            ],
        ];
    }
}
