<?php

namespace Atw\DdnsUserAdminBundle\Tests\Dto;

use Atw\DdnsUserAdminBundle\Dto\DnsUserCriteriaDto;
use Atw\DdnsUserAdminBundle\Entity\Support\EntitySupport;
use Atw\DdnsUserAdminBundle\Tests\Support\ContainerAwareKernelTestCase;

/**
 * Class DnsUserCriteriaDtoTest
 */
class DnsUserCriteriaDtoTest extends ContainerAwareKernelTestCase
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
     * validな値をセットしてvalidateし
     * エラーの件数が0件であることを確認する
     * @test
     */
    public function validValueCase()
    {
        $dto = new DnsUserCriteriaDto();
        $dto->setCriteria("検索条件テスト");
        $dto->setDisplaycount(DnsUserCriteriaDto::DISPLAYCOUNT_MIN);

        $errors = $this->validator->validate($dto);
        $this->assertEquals(0, count($errors), "正常系テストFail");
    }

    /**
     * 表示件数の入力チェック
     * @test
     * @dataProvider displayCountValidateDataProvider
     */
    public function invalidDisplayCountCase($expected, $data, $msg)
    {
        $dto = new DnsUserCriteriaDto();
        EntitySupport::autoSet($dto, $data);

        $errors = $this->validator->validate($dto);
        $this->assertEquals($expected, count($errors), $msg);
    }

    /**
     * @return array チェックするデータ
     */
    public function displayCountValidateDataProvider()
    {
        return $invalidDatas = [
            [
                2,
                ["criteria" => "", "displaycount" => "a"],
                "数値以外の場合(リストにも存在しないため期待値は2)",
            ],
            [
                1,
                ["criteria" => "", "displaycount" => -1],
                "マイナスの場合(リスト存在エラーになるため期待値は1)",
            ],
            [
                2,
                ["criteria" => "", "displaycount" => "0.5"],
                "小数の場合(リストにも存在しないため期待値は2)",
            ],
            [
                1,
                ["criteria" => "", "displaycount" => DnsUserCriteriaDto::DISPLAYCOUNT_MIN + 1],
                "リストに存在しない値の場合",
            ],
        ];
    }
}
