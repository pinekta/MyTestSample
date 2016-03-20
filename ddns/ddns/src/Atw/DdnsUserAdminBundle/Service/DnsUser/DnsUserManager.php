<?php

namespace Atw\DdnsUserAdminBundle\Service\DnsUser;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Entity\DnsUser;
use Atw\DdnsUserAdminBundle\Exception\FormValidationException;

/**
 * Class DnsUserManager
 */
class DnsUserManager implements DnsUserManagerInterface
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;

    /** @var \Symfony\Component\Validator\Validator\ValidatorInterface */
    private $validator;

    /**
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @param DnsUser $dnsUser
     * @throws FormValidationException
     */
    public function tryUpdateInsert(DnsUser $dnsUser)
    {
        $this->tryValidate($dnsUser);
        $this->em->persist($dnsUser);
        $this->em->flush();
    }

    /**
     * @param DnsUser $dnsUser
     * @throws \Exception
     */
    public function tryDelete(DnsUser $dnsUser)
    {
        $this->em->remove($dnsUser);
        $this->em->flush();
    }

    /**
     * @param DnsUser $dnsUser
     * @throws FormValidationException
     */
    public function tryValidate(DnsUser $dnsUser)
    {
        $errors = $this->validator->validate($dnsUser);
        if (count($errors)) {
            $exception = new FormValidationException();
            foreach ($errors as $error) {
                $exception->addErrorMessage($error->getMessage());
            }
            throw $exception;
        }
    }

    /**
     * インポートを試み、エラーの場合は適切なExceptionをthrowする
     * @param DnsUserImportDto $dto
     * @throws InvalidArgumentException
     * @throws FormValidationException
     * @todo メソッドが肥大化してきたので分割したい
     */
    public function tryImportAndGetResultCount(DnsUserImportDto $dto)
    {
        if (is_null($dto)) {
            throw \InvalidArgumentException('DtoオブジェクトがNullです。');
        }

        $filePath = $dto->getImportFile()->getRealPath();
        if (!file_exists($filePath)) {
            throw \InvalidArgumentException('インポートファイルが見つかりません。');
        }

        // 登録されているusernameデータを取得
        $userNames = $this->em->getRepository(DnsUser::class)->findUserNameAll();

        $file = new \SplFileObject($filePath);
        $file->setFlags(\SplFileObject::READ_CSV);

        $updateCount = 0;
        $this->em->transactional(function () use ($file, $dto, $userNames, &$updateCount) {
            $idx = 0;
            $updateCount = 0;
            foreach ($file as $row) {
                $idx++;
                // ヘッダ行を無視するオプションを選択かつヘッダ行の場合スキップ
                if ($dto->getIsIgnoreHeaderLine() === DnsUserImportDto::IS_IGNORE_HEADER_YES && $idx === 1) {
                    continue;
                }
                // 列数が想定通りでない場合スキップ
                if (count($row) !== DnsUserImportDto::FILE_COLUMN_COUNT) {
                    continue;
                }

                list(, $userName, $password, $controlNo, $comment, $encryptType) = $row;
                mb_convert_variables("UTF-8", "SJIS", $userName, $password, $controlNo, $comment, $encryptType);
                if (in_array($userName, $userNames)) {
                    // 既存のデータは上書きしないオプション選択の場合スキップ
                    if ($dto->getIsUpdated() === DnsUserImportDto::IS_UPDATED_NO) {
                        continue;
                    }
                    $entity = $this->em->getRepository(DnsUser::class)->findOneByUserName($userName);
                    $passwordBackup = $entity->getPassword();

                    $entity->setPassword($password)
                           ->recoveryPasswordIfNull($passwordBackup);
                } else {
                    $entity = (new DnsUser())->setUserName($userName)
                                             ->setPassword($password);
                }
                $entity->setControlNo($controlNo)
                       ->setComment($comment)
                       ->setEncryptType(intval($encryptType));

                $this->tryValidate($entity);
                $this->em->persist($entity);
                $updateCount++;
            }
        });
        return $updateCount;
    }
}
